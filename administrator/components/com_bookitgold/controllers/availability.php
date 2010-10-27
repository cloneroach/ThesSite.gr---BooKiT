<?php
/**
 * BooKiTGold - Joomla Booking Management Component
 *
 *
 * @version 4.0
 * @author Costas Kakousis (info@istomania.com)
 * @copyright (C) 2009-2010 by Costas Kakousis (http://www.istomania.com)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 * If you fork this to create your own project,
 * please make a reference to BooKiTGold someplace in your code
 * and provide a link to http://www.istomania.com
 *
 * This file is part of BooKiTGold.
 * BooKiTGold is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 *  BooKiTGold is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with BooKiTGold.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * BooKiTGold Component Controller
 * Responsible for responding to user actions (i.e, page requests)
 * Determines what request is being made by the user and responds
 * appropriately by triggering methods in the model which modify
 * the data. Afterwards the model is passed into the view which
 * displays the data.
 *
 */

class BookitgoldControllerAvailability extends BookitgoldController
{

	function __construct()
	{
		//$this->registerTask( 'add'  , 	'edit' );
		//$this->registerTask( 'apply'  , 'save' );

		parent::__construct();

	}

	function apply ()
	{

		$model = $this->getModel('availability');
		$res = $model->store();


		$db =& JFactory::getDBO();
		//Check if dates are in living range
		$query = "SELECT MAX(today) as max_date, MIN(today) as min_date FROM #__bookitavailability";
		$db->setQuery($query);
		$range = $db->loadObjectList();

		$max_date = date("Y-m-d", strtotime($range['0']->max_date));
		$min_date = date("Y-m-d", strtotime($range['0']->min_date));
		if ($res["id"]==0)
		{
			$row =& JTable::getInstance('availability', 'Table');
			$lastid =$row->_db->insertid();
			$res["id"]=$lastid;
		}
		$link = 'index.php?option=com_bookitgold&controller=availability&task=edit&cid[]='.$res["id"];
		if ($res["code"]==0) {

			$msg = JText::_( 'Changes Saved' )."!";
			$this->setRedirect($link, $msg);
		} 
		else if ($res["code"]==-2) {
			$msg = JText::_( 'No records affected' ).".";
			// Check the table in so it can be edited.... we are done with it anyway
			$this->setRedirect($link, $msg);
		}
		else if ($res["code"]==-3)
		{
			$params =& JComponentHelper::getParams('com_bookitgold');
			$dateformatcode = $params->get('dateformat');
			if ($dateformatcode==""||$dateformatcode==3)
			{
				$d1='d-m-Y';

			}
			else if ($dateformatcode==1)
			{
				$d1='Y-m-d';

			}
			else if ($dateformatcode==2)
			{
				$d1='m/d/Y';
					
			}

			$x = date ($d1,strtotime($min_date));
			$y = date ($d1,strtotime($max_date));
			$msg = JText::_("Selected dates must be in the range").":".$x." - ".$y;
			$this->setRedirect($link, $msg);
		}
		else if ($res["code"]==-4)
		{

			$msg2 = JText::_("You cannot change the status of a single room. Please deselect the room field").".";
			$this->setRedirect($link, $msg2, 'notice');
		}
	else if ($res["code"]==-5)
		{

			$msg2 = JText::_("Changing the availability of booked dates is not allowed").".";
			$this->setRedirect($link, $msg2, 'notice');
		}
	
		else  {
			$msg = JText::_( 'Price must be a positive number and From date must be before To date' ).".";
			$this->setRedirect($link, $msg, 'notice');
		}
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{

		$model = $this->getModel('availability');
		$res = $model->store();

		$db =& JFactory::getDBO();
		//Check if dates are in living range
		$query = "SELECT MAX(today) as max_date, MIN(today) as min_date FROM #__bookitavailability";
		$db->setQuery($query);
		$range = $db->loadObjectList();

		$max_date = date("Y-m-d", strtotime($range['0']->max_date));
		$min_date = date("Y-m-d", strtotime($range['0']->min_date));
		if ($res["code"]==0) {

			$msg = JText::_( 'Changes Saved' )."!";
			// Check the table in so it can be edited.... we are done with it anyway
			$link = 'index.php?option=com_bookitgold';
			$this->setRedirect($link, $msg);
		} else if ($res["code"]==-2) {
			$msg = JText::_( 'No records affected' ).".";
			// Check the table in so it can be edited.... we are done with it anyway
			$link = 'index.php?option=com_bookitgold';
			$this->setRedirect($link, $msg);
		}
		else if ($res["code"]==-3)
		{
			$params =& JComponentHelper::getParams('com_bookitgold');
			$dateformatcode = $params->get('dateformat');
			if ($dateformatcode==""||$dateformatcode==3)
			{
				$d1='d-m-Y';

			}
			else if ($dateformatcode==1)
			{
				$d1='Y-m-d';

			}
			else if ($dateformatcode==2)
			{
				$d1='m/d/Y';
					
			}

			$x = date ($d1,strtotime($min_date));
			$y = date ($d1,strtotime($max_date));
			$msg = JText::_("Selected dates must be in the range").":".$x." - ".$y;
			$link = 'index.php?option=com_bookitgold&controller=availability&task=edit&cid[]='.$res["id"];
			$this->setRedirect($link, $msg, 'notice');

		}
		else if ($res["code"]==-4)
		{
			$msg2 = JText::_("You cannot change the status of a single room. Please deselect the room field").".";
			$link = 'index.php?option=com_bookitgold&controller=availability&task=edit&cid[]='.$res["id"];
			$this->setRedirect($link, $msg2, 'notice');
		}
		else if ($res["code"]==-5)
		{
			$msg2 = JText::_("Changing the availability of booked days is not allowed").".";
			$link = 'index.php?option=com_bookitgold&controller=availability&task=edit&cid[]='.$res["id"];
			$this->setRedirect($link, $msg2, 'notice');
		}
		
		else  {
			$msg = JText::_( 'Price must be a positive number and From date must be before To date' ).".";
			$link = 'index.php?option=com_bookitgold&controller=availability&task=edit&cid[]='.$res["id"];
			$this->setRedirect($link, $msg, 'notice');
		}
	}


	function cancel()
	{
		$link = 'index.php?option=com_bookitgold&controller=availability&task=listing';
		$this->setRedirect($link);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'availability' );
		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in
		JRequest::setVar('hidemainmenu', 1);
		// some complex views, multiple layouts might
		// be needed.

		parent::display();
	}

	function ajaxrooms()
	{
		$model = $this->getModel('availability');
		$idcategory = JRequest::getVar('idcategory');

		$db =& JFactory::getDBO();
		$sql = "SELECT idroom, name FROM #__bookitroom WHERE idcategory=".$idcategory;
		$db->setQuery($sql);

		$data = $db->loadObjectList();
		//create root
		$rooms = new JSimpleXMLElement('rooms', array('id' => 0));

		for ($i=0; $i<count($data); $i++)
		{
			// add children
			$room =& $rooms->addChild('room');
			$room->addAttribute('id',$data[$i]->idroom);
			$room->addAttribute('name',$data[$i]->name);
			/*$roomid =& $room->addChild('id');
				$name =& $room->addChild('name');
				// set child data values
				$roomid->setData($data[$i]->idroom);
				$name->setData($data[$i]->name);*/
		}
		echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
		echo $rooms->toString();
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('text/xml');

	}

	function calendar (){
		$output = '';
		$month = JRequest::getVar('month');
		$year = JRequest::getVar('year');
		$idroom = JRequest::getVar('idroom');
		$idcategory = JRequest::getVar('idcategory');
		$db =& JFactory::getDBO();

		//Get colors
		$params =& JComponentHelper::getParams('com_bookitgold');
		$available_color = $params->get('available_color','#CCFFCC');
		$booked_color = $params->get('booked_color','#FF6666');
		$pending_color = $params->get('pending_color','#FFCC66');
		$other_color = $params->get('other_color','#CCCCFF');
		$extra_availability = $params->get('extra_availability','Other');
		echo "<table>
				<tr>
					<td bgcolor='".$available_color."' width='20px' height='20px'> 
					</td><td>".JText::_( 'Available' )."</td>	
					<td bgcolor='".$booked_color."' width='20px' height='20px'> 
					</td><td>".JText::_( 'Booked' )."</td>
					<td bgcolor='".$pending_color."' width='20px' height='20px'> 
					</td><td>".JText::_( 'Pending' )."</td>
					<td bgcolor='".$other_color."' width='20px' height='20px'> 
					</td><td>".$extra_availability."</td>
				</tr></table>";



		if($month == '' && $year == '') {
			$time = time();
			$month = date('n',$time);
			$year = date('Y',$time);
		}

		$date = getdate(mktime(0,0,0,$month,1,$year));
		$today = getdate();
		$hours = $today['hours'];
		$mins = $today['minutes'];
		$secs = $today['seconds'];

		if(strlen($hours)<2) $hours="0".$hours;
		if(strlen($mins)<2) $mins="0".$mins;
		if(strlen($secs)<2) $secs="0".$secs;

		$days=date("t",mktime(0,0,0,$month,1,$year));
		$start = $date['wday']+1;
		$name = $date['month'];
		$year2 = $date['year'];
		$offset = $days + $start - 1;

		if($month==12) {
			$next=1;
			$nexty=$year + 1;
		} else {
			$next=$month + 1;
			$nexty=$year;
		}

		if($month==1) {
			$prev=12;
			$prevy=$year - 1;
		} else {
			$prev=$month - 1;
			$prevy=$year;
		}

		if($offset <= 28) $weeks=28;
		elseif($offset > 35) $weeks = 42;
		else $weeks = 35;

		$imagesRoot = JURI::root()."components/com_bookitgold/images/";
		$output .= "
<table class='bcal' cellspacing='1'>
<tr>
	<td colspan='7'>
		<table class='bcalhead'>
		<tr>
			<td>
				<a href='javascript:navigate($prev,$prevy)'><img src='".$imagesRoot."calLeft.gif'></a> <a href='javascript:navigate(\"\",\"\")'><img src='".$imagesRoot."calCenter.gif'></a> <a href='javascript:navigate($next,$nexty)'><img src='".$imagesRoot."calRight.gif'></a>
			</td>
			<td align='right'>
				<div>$name $year2</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr class='bdayhead'>
	<td>Sun</td>
	<td>Mon</td>
	<td>Tue</td>
	<td>Wed</td>
	<td>Thu</td>
	<td>Fri</td>
	<td>Sat</td>
</tr>";

		$col=1;
		$cur=1;
		$next=0;
		$backcolor='#FFFFFF';
		$title_text='';

		for($i=1;$i<=$weeks;$i++) {
			if($next==3) $next=0;
			if($col==1) $output.="<tr class='bdayrow'>";

			$output.="<td valign='top' onMouseOver=\"this.className='dayover'\" onMouseOut=\"this.className='dayout'\" ";

			if($i <= ($days+($start-1)) && $i >= $start) {

				$currentDay = date ("Y-m-d",strtotime($year."-".$month."-".$cur));

				if ($idroom>0)
				{
					$query =  "SELECT * FROM #__bookitavailability WHERE idroom='".$idroom."' AND today='".$currentDay."'";
					$db->setQuery( $query );
					$row = $db->loadRow();

					//Get the day state (availability) 0:av 1:bo 2:high 3:other
					if ($row['8']==0 || $row['8']=="" && $row['2']<=0)
					$backcolor = $available_color;
					else if ($row['8']==1)
					$backcolor = $booked_color;
					else if ($row['8']==2)
					$backcolor = $pending_color;
					else if ($row['8']==3)
					$backcolor = $other_color;


					$query =  "SELECT name FROM #__bookitroom WHERE idroom='".$idroom."'";
					$db->setQuery( $query );
					$res = $db->loadResult();

					$query =  "SELECT name FROM #__bookitspecialoffer WHERE idoffer='".$row['1']."'";
					$db->setQuery( $query );
					$res2 = $db->loadResult();

					$dayprice=$row[5];
					if ($dayprice==0)
					{
						$query =  "SELECT idcategory FROM #__bookitroom WHERE idroom='".$idroom."'";
						$db->setQuery( $query );
						$res3 = $db->loadResult();

						$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$res3."'";
						$db->setQuery( $query );
						$dayprice = $db->loadResult();
					}

					$title_text=JText::_('Booking ID').":".$row['2'].", ".JText::_('Room').":".$res.", ".JText::_('Price').":".$dayprice.", ".JText::_('Offer').":".$res2;
					$output.="bgcolor='".$backcolor."' title='".$title_text."'/>";
					$output.="<div class='bday';><b";

					if(($cur==$today['mday']) && ($name==$today['month'])) $output.=" style='color:#C00'";

					$output.=">$cur</b></div></td>";

					$cur++;
					$col++;

				}
				else if ($idcategory>0)
				{

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".
					$db->quote($idcategory)." AND today='".$currentDay."' AND availability != '1' ;";
					$db->setQuery($query);
					$count_available = $db->loadResult();

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".
					$db->quote($idcategory)." AND today='".$currentDay."' AND availability = '1' ;";
					$db->setQuery($query);
					$count_booked = $db->loadResult();

					if ($count_available>0)
					{
						$query =  "SELECT availability FROM #__bookitavailability WHERE idcategory='".$idcategory."' AND today='".$currentDay."'";
						$db->setQuery( $query );
						$state = $db->loadResult();
						//Get the day state (availability) 0:av 1:bo 2:high 3:other
						if ($state==2)
						$backcolor = $pending_color;
						else if ($state==3)
						$backcolor = $other_color;
						else
						$backcolor = $available_color;
					}
					else if ($count_available<=0 && $count_booked>0)
					$backcolor = $booked_color;

					else
					$backcolor = $available_color;

					$query = "SELECT idoffer, price FROM #__bookitavailability WHERE idcategory=".
					$db->quote($idcategory)." AND today='".$currentDay."' ;";
					$db->setQuery($query);
					$ave_res = $db->loadRow();
					
					$offer="";
					if ($ave_res['0']>0)
					{
						$query = "SELECT name FROM #__bookitspecialoffer WHERE idoffer=".
						$db->quote($ave_res['0'])." ;";
						$db->setQuery($query);
						$offer = $db->loadResult();
					}
					$price=$ave_res['1'];
					if ($price==0||$price=="")
					{
						$query = "SELECT cost FROM #__bookitcategory WHERE idcategory=".
						$db->quote($idcategory)." ;";
						$db->setQuery($query);
						$price = $db->loadResult();
					}
					
					$title_text=JText::_('Availability').": ".$count_available.", ".JText::_('Bookings')." ".$count_booked.", ".JText::_('Price').": ".$price.", ".JText::_('Offer').": ".$offer;
					$output.="bgcolor='".$backcolor."' title='".$title_text."'/>";
					$output.="<div class='bday';><b";

					if(($cur==$today['mday']) && ($name==$today['month'])) $output.=" style='color:#C00'";

					$output.=">$cur</b></div></td>";

					$cur++;
					$col++;
				}
				else
				{
					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE today='".$currentDay."' AND availability != '1' ;";
					$db->setQuery($query);
					$count_available = $db->loadResult();

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE today='".$currentDay."' AND availability = '1' ;";
					$db->setQuery($query);
					$count_booked = $db->loadResult();

					if ($count_available>0)
					{
						$query =  "SELECT availability FROM #__bookitavailability WHERE today='".$currentDay."'";
						$db->setQuery( $query );
						$state = $db->loadResult();
						//Get the day state (availability) 0:av 1:bo 2:high 3:other
						if ($state==2)
						$backcolor = $pending_color;
						else if ($state==3)
						$backcolor = $other_color;
						else
						$backcolor = $available_color;
					}
					else if ($count_available<=0 && $count_booked>0)
					$backcolor = $booked_color;

					else
					$backcolor = $available_color;

					$title_text=JText::_('Availability').": ".$count_available.", ".JText::_('Bookings').": ".$count_booked;
					$output.="bgcolor='".$backcolor."' title='".$title_text."'/>";
					$output.="<div class='bday';><b";

					if(($cur==$today['mday']) && ($name==$today['month'])) $output.=" style='color:#C00'";

					$output.=">$cur</b></div></td>";

					$cur++;
					$col++;

				}
			}

			else {
				$output.="&nbsp;</td>";
				$col++;
			}

			if($col==8) {
				$output.="</tr>";
				$col=1;
			}

		}

		$output.="</table>";

		echo $output;
	}

	function status ()
	{
		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');
		$idroom = JRequest::getVar('idroom');
		$idcategory = JRequest::getVar('idcategory');
		$db =& JFactory::getDBO();
		$price=0;
		$offer=0;
		$params =& JComponentHelper::getParams('com_bookitgold');
		$dateformatcode = $params->get('dateformat');
		if ($dateformatcode==""||$dateformatcode==3)
		$d1='d-m-Y';
		else if ($dateformatcode==1)
		$d1='Y-m-d';
		else if ($dateformatcode==2)
		$d1='m/d/Y';

		$valid_from_new = date ($d1,strtotime($valid_from));
		$valid_to_new = date ($d1,strtotime($valid_to));
		$fromDateTS = strtotime($valid_from_new);
		$toDateTS = strtotime($valid_to_new);

		//Room selected
		if ($idroom!="" && $idroom>0)
		{

			$msg='';

			$query =  "SELECT name FROM #__bookitroom WHERE idroom='".$idroom."'";
			$db->setQuery( $query );
			$rname = $db->loadResult();

			echo "<b><font color='blue'>".JText::_("Room").": </font></b>".$rname."\n<br>";
			for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				$msg .= "<b>".date($d1,$currentDateTS).": </b>";
				//Get the day price, if it is defined in the availability table use that price, otherwise get the price of the room  category
				$query =  "SELECT * FROM #__bookitavailability WHERE idroom='".$idroom."' AND today='".$currentDateStr."'";
				$db->setQuery( $query );
				$availability_res = $db->loadRow();
				//Price
				if ($availability_res['5']==0)
				{
					$query =  "SELECT idcategory FROM #__bookitroom WHERE idroom='".$idroom."'";
					$db->setQuery( $query );
					$r = $db->loadResult();
					$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$r."'";
					$db->setQuery( $query );
					$price = $db->loadResult();
				}
				else
				$price = $availability_res['5'];

				if ($availability_res['1']>0)//idoffer
				{
					$query =  "SELECT name FROM #__bookitspecialoffer WHERE idoffer='".$availability_res['1']."'";
					$db->setQuery( $query );
					$offer = $db->loadResult();

				}
				if ($availability_res['8']==1)//booked
				{
					$msg .= "<font color='red'>".JText::_("booked")." (id=".$availability_res['2'].") </font>";
					$query =  "SELECT * FROM #__bookitbooking WHERE idbook='".$availability_res['2']."'";
					$db->setQuery( $query );
					$res1 = $db->loadObjectList();

					$query2 =  "SELECT * FROM #__bookitguests WHERE idguests='".$res1['0']->idguests."'";
					$db->setQuery( $query2 );

					$res2 = $db->loadObjectList();

					if ($availability_res['1']>0)//idoffer
					$msg .= ", <b>Guest:</b>".$res2['0']->name." ".$res2['0']->surname.", <b>Price: </b>".$price.", <b>Offer: </b>".$offer."\n<br>";
					else
					$msg .= ", <b>Guest:</b>".$res2['0']->name." ".$res2['0']->surname.", <b>Price: </b>".$price."\n<br>";

				}
				else
				{
					if ($availability_res['1']>0)//idoffer
					$msg .= "<font color='green'>".JText::_("available")."</font>, <b>".JText::_("Price").": </b>".$price.", <b>".JText::_("Offer").": </b>".$offer."\n<br>";
					else
					$msg .= "<font color='green'>".JText::_("available")."</font>, <b>".JText::_("Price").": </b>".$price."\n<br>";

				}
				echo $msg;
				$msg='';

			}

		}
		//Category selected, no room selected
		else if ($idcategory!="" && $idcategory>0)
		{

			$msg='';
			$query =  "SELECT name FROM #__bookitcategory WHERE idcategory='".$idcategory."'";
			$db->setQuery( $query );
			$rname = $db->loadResult();

			echo "<b><font color='blue'>".JText::_("Room Category").": </font></b>".$rname."\n<br>";
			for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				
				$query =  "SELECT * FROM #__bookitavailability WHERE idcategory='".$idcategory."' AND today='".$currentDateStr."'";
				$db->setQuery( $query );
				$availability_res = $db->loadRow();				
				
				$msg .= "<b>".date($d1,$currentDateTS).": </b>";
				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".
				$db->quote($idcategory)." AND today='".$currentDateStr."' AND availability != '1' ;";
				$db->setQuery($query);
				$count_available = $db->loadResult();

				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".
				$db->quote($idcategory)." AND today='".$currentDateStr."' AND availability ='1' ;";
				$db->setQuery($query);
				$count_booked = $db->loadResult();

				if ($count_available>0)
				{
					$msg .= "<font color='green'>".JText::_("Availability").": ".$count_available."</font> - ".
					JText::_("Booked").": ".$count_booked;

				}

				else if ($count_available<=0 && $count_booked>0)
				{
					$msg .= "<font color='red'>".JText::_("Fully Booked")." - </font>".
					JText::_("Booked").": ".$count_booked;

				}
				//Price
				if ($availability_res['5']==0)
				{
					$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$idcategory."'";
					$db->setQuery( $query );
					$price = $db->loadResult();
					
				}
				else
				$price = $availability_res['5'];
				$msg .= "<b> ".JText::_("Price").":</b>".$price;

				if ($availability_res['1']>0)//idoffer
				{
					$query =  "SELECT name FROM #__bookitspecialoffer WHERE idoffer='".$availability_res['1']."'";
					$db->setQuery( $query );
					$offer = $db->loadResult();
					$msg .= "<b> ".JText::_("Offer").":</b>".$offer;

				}
				
			
				echo $msg."\n<br>";
				$msg='';

			}
		}
		//Nothing selected
		else
		{

			$msg='';


			echo "<b><font color='blue'>".JText::_("Hotel Availability").": </font></b>\n<br>";
			for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);

				$msg .= "<b>".date($d1,$currentDateTS).": </b>";
				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE today='".$currentDateStr."' AND availability != '1' ;";
				$db->setQuery($query);
				$count_available = $db->loadResult();

				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE today='".$currentDateStr."' AND availability = '1' ;";
				$db->setQuery($query);
				$count_booked = $db->loadResult();

					

				if ($count_available>0)
				{
					$msg .= "<font color='green'> ".JText::_("Availability").": ".$count_available."</font> - ".
					JText::_("Booked").": ".$count_booked;

				}

				else if ($count_available<=0 && $count_booked>0)
				{
					$msg .= "<font color='red'> ".JText::_("Fully Booked")." </font> - ".
					JText::_("Booked").": ".$count_booked;

				}


				echo $msg."\n<br>";
				$msg='';

			}

		}

	}




	function panoramic ()
	{
		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');

		$db =& JFactory::getDBO();
		$params =& JComponentHelper::getParams('com_bookitgold');
		$dateformatcode = $params->get('dateformat');
		if ($dateformatcode==""||$dateformatcode==3)
		$d1='d-m-Y';
		else if ($dateformatcode==1)
		$d1='Y-m-d';
		else if ($dateformatcode==2)
		$d1='m/d/Y';
		$available_color = $params->get('available_color','#CCFFCC');
		$booked_color = $params->get('booked_color','#FF6666');

		$valid_from_new = date ($d1,strtotime($valid_from));
		$valid_to_new = date ($d1,strtotime($valid_to));
		$fromDateTS = strtotime($valid_from_new);
		$toDateTS = strtotime($valid_to_new);
		//Table dimensions
		//Get number of categories
		$query = "SELECT idcategory FROM #__bookitcategory;";
		$db->setQuery($query);
		$categories = $db->loadResultArray();
		$cats_no =count($categories);
		$isBooked=false;

		echo "<table>";
		foreach ($categories as $cat_id)
		{
			$query = "SELECT name FROM #__bookitcategory WHERE idcategory=".$cat_id.";";
			$db->setQuery($query);
			$cat_name = $db->loadResult();
			echo "<tr>";
			echo "<td bgcolor='#FFFFFF' width='20px' height='20px'>".$cat_name."</td>";
				
			$query = "SELECT * FROM #__bookitroom WHERE idcategory=".$cat_id.";";
			$db->setQuery($query);
			$rooms = $db->loadRowList();
			foreach ($rooms as $room)
			{
				for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
				{
					$currentDateStr = date('Y-m-d',$currentDateTS);
					$query =  "SELECT * FROM #__bookitavailability WHERE idroom='".$room[0]."' AND today='".$currentDateStr."'";
					$db->setQuery( $query );
					$availability_res = $db->loadRow();
					if ($availability_res['8']==1)//booked
					{
						$isBooked=true;
						break;
					}	
					else
						continue;

				}
				if ($isBooked)
					echo "<td bgcolor='".$booked_color."' width='20px' height='20px'>".$room[2]."</td>";
				else
					echo "<td bgcolor='".$available_color."' width='20px' height='20px'>".$room[2]."</td>";
				$isBooked=false;
					
			}
			echo "</tr>";
		}
		echo "</table>";
	}


}
