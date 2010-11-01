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
 *  BooKitGold is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with BooKitGold.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');


/**
 * BooKitGold Component Controller
 * Responsible for responding to user actions (i.e, page requests)
 * Determines what request is being made by the user and responds
 * appropriately by triggering methods in the model which modify
 * the data. Afterwards the model is passed into the view which
 * displays the data.
 *
 */

class BookitgoldControllerBooking extends BookitgoldController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'list'  , 	'listing' );

	}


	function listing()
	{
		JRequest::setVar( 'view', 'booking' );
		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in
		// some complex views, multiple layouts might
		// be needed.
		parent::display();

	}
	function edit()  // <-- edit, add, delete
	{
		JRequest::setVar( 'view', 'bookingedit' );
		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in
		JRequest::setVar('hidemainmenu', 1);
		// some complex views, multiple layouts might
		// be needed.
		parent::display();
	}


	function remove()
	{
		$model = $this->getModel('booking');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Bookings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Booking(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_bookitgold&controller=booking&task=listing', $msg );
	}

	function pricecalc()
	{
		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');
		$idroom = JRequest::getVar('idroom');
		$idcategory = JRequest::getVar('idcategory');
		$idguests = JRequest::getVar('idguests');
		$nguests = JRequest::getVar('nguests');
		$nchilds = JRequest::getVar('nchilds');
		
		$lchilds = JRequest::getVar('lchilds'); // Thessite

		$idcoupon = JRequest::getVar('idcoupon');
		$extra_ids = JRequest::getVar('extra_ids');
		$new_valid_from = date("Y-m-d", strtotime($valid_from));
		$new_valid_to = date("Y-m-d", strtotime($valid_to));
		$res=0;
		$error_msg='';
		$db =& JFactory::getDBO();

		//Check if dates are in living range
		$query = "SELECT MAX(today) as max_date, MIN(today) as min_date FROM #__bookitavailability";
		$db->setQuery($query);
		$range = $db->loadObjectList();

		$max_date = date("Y-m-d", strtotime($range['0']->max_date));
		$min_date = date("Y-m-d", strtotime($range['0']->min_date));



		if ($idcategory=="" || $idcategory<=0)
		{
			$res=0;
			$error_msg = JText::_("Please select room category").".";
		}
		else if ($new_valid_to <= $new_valid_from)
		{
			$res=0;
			$error_msg = JText::_("Check-in date must be before Check-out date").".";
		}
		else if ($new_valid_to>$max_date || $new_valid_from<$min_date)
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
			$res=0;
			$error_msg = JText::_("Selected dates must be in the range").": ".$x." - ".$y;
		}

		else
		{

			$fromDateTS = strtotime($new_valid_from);
			$toDateTS = strtotime($new_valid_to);
			$price=0;
			$idoffer=0;
			$dayprice=0;
			$res_noOffer=$res;
			$nights=round( ($toDateTS-$fromDateTS )/86400) ;

			for ($currentDateTS = $fromDateTS, $cnt=1; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);

				//Get the day price, if it is defined in the availability table use that price, otherwise get the price of the room  category
				$query =  "SELECT * FROM #__bookitavailability WHERE idcategory='".$idcategory."' AND today='".$currentDateStr."'";
				$db->setQuery( $query );
				$availability_res = $db->loadRow();

				$price = $availability_res['5'];

				if ($price==0)
				{

					$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$idcategory."'";

					$db->setQuery( $query );
					$price = $db->loadResult();

				}
				$dayprice = $price;
				$res_noOffer+=$dayprice;

				//On this price it may apply: coupon, special offer or extra service

				//Check for special offer
				$idoffer = $availability_res['1'];
				if ($idoffer >0) //an offer applies for the day, go get it
				{
					$query =  "SELECT * FROM #__bookitspecialoffer WHERE idoffer='".$idoffer."'";
					$db->setQuery( $query );
					$offer_res = $db->loadRow();
					if ($offer_res['2']>0)
					{
						$price = $price-$offer_res['2'];
					}
					else if ($offer_res['3']>0)
					{
						$price = $price-($offer_res['3']/100*$price);
					}
				}
				

				//Check for coupon
				if ($idcoupon!="" && $idcoupon>0)
				{
					$coupon_value=0;
					$query =  "SELECT * FROM #__bookitcoupon WHERE idcoupon='".$idcoupon."'";
					$db->setQuery( $query );
					$coupon_res = $db->loadRow();

					//Check if is valid in terms of dates

					$couponFromDateTS = strtotime($coupon_res['6']);
					$couponToDateTS = strtotime($coupon_res['7']);

					if ($coupon_res['4']==1 && $coupon_res['5']==0 &&
					$currentDateTS>=$couponFromDateTS && $currentDateTS<=$couponToDateTS)
					{

						//Get Fix or Percentage value
						if ($coupon_res['2']>0)
						{
							$coupon_value = $coupon_res['2'];
							if ($coupon_res['9']>0)
							{
								if ($nights%$cnt==0)
								$price = $price - $coupon_value;
							}
							else
							{
								$price = $price - ($coupon_value/$nights);
							}
						}
						else if ($coupon_res['3']>0)
						{
							$coupon_value = $coupon_res['3'];
							if ($coupon_res['9']>0)
							{
								if ($cnt%$coupon_res['9']==0)
								{

									$price = $price-($coupon_value/100*$price);

								}
							}
							else
							{

								$price = $price-($coupon_value/100*$price);

							}

						}

					}

				}
				$res = $res+$price;
				//$res = $res + $price + $lchilds_price + $nchilds_price; // Thessite
			}


			//Check for extras
			if ($extra_ids!="" && $extra_ids>0)
			{
				$qnt=0;
				$extr_id_arr = explode(",", $extra_ids);
				foreach ($extr_id_arr as $extra_id)
				{
					$r = explode("-", $extra_id);
					if (count($r)>1)
					{	$extra_id=$r['0'];
					$qnt=$r['1'];
					}
					else
					$qnt=0;

					$query =  "SELECT * FROM #__bookitextra WHERE idextra='".$extra_id."'";
					$db->setQuery( $query );
					$extra_res = $db->loadRow();
					if ($extra_res['5']==1)//Per booking
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3'];
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer);
						}

					}
					else if ($extra_res['5']==2)//Per Night
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3']*$nights;
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer)*$nights;
						}
					}

					else if ($extra_res['5']==3)//Per person
					{
						if ($extra_res['3']>0)//Fix
						{
							if ($nguests!=""&&$nguests>0)
							{
								$res = $res+$extra_res['3']*($nguests+$nchilds/* Thessite */+$lchilds/* Thessite */);
							}
						}
						else if ($extra_res['4']>0)//Percent
						{
							if ($nguests!=""&&$nguests>0)
							$res = $res+($extra_res['4']/100*$res_noOffer)*($nguests+$nchilds/* Thessite */+$lchilds/* Thessite */);
						}
					}
					else if ($extra_res['5']==4)//Per adult
					{
						if ($extra_res['3']>0)
						{
							if ($nguests!=""&&$nguests>0)
							$res = $res+$extra_res['3']*$nguests;
						}
						else if ($extra_res['4']>0)
						{
							if ($nguests!=""&&$nguests>0)
							$res = $res+($extra_res['4']/100*$res_noOffer)*$nguests;
						}
					}
					else if ($extra_res['5']==5)//Per child
					{
						if ($extra_res['3']>0)
						{
							if ($nchilds!=""&&$nchilds>0)
							$res = $res+$extra_res['3']*$nchilds;
						}
						else if ($extra_res['4']>0)
						{
							if ($nchilds!=""&&$nchilds>0)
							$res = $res+($extra_res['4']/100*$res_noOffer)*/*Thessite*/($nchilds+$lchilds)/*Thessite*/;
						}
					}
					else if ($extra_res['5']==6) //Per Adult/Night
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3']*$nguests*$nights;
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer)*$nguests*$nights;
						}
					}
					else if ($extra_res['5']==7) //Per Child/Night
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3']*$nchilds*$nights;
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer)*/*Thessite*/($nchilds+$lchilds)/*Thessite*/*$nights;
						}
					}
					else if ($extra_res['5']==8)//Per quantity
					{
						if ($extra_res['3']>0)
						{
							if ($qnt>0)
							$res = $res+$extra_res['3']*$qnt;
						}
						else if ($extra_res['4']>0)
						{
							if ($qnt>0)
							$res = $res+($extra_res['4']/100*$res_noOffer)*$qnt;
						}
					}
					else if ($extra_res['5']==9) //Quantity/night
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3']*$qnt*$nights;
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer)*$qnt*$nights;
						}
					}
					else if ($extra_res['5']==10) //Guest/night
					{
						if ($extra_res['3']>0)
						{
							$res = $res+$extra_res['3']*($nguests+$nchilds)*$nights;
						}
						else if ($extra_res['4']>0)
						{
							$res = $res+($extra_res['4']/100*$res_noOffer)*($nguests+$nchilds/* Thessite */+$lchilds/* Thessite */)*$nights;
						}
					}
				}
			}
			//End of extras
		}

		echo round($res,"2").",".$error_msg;
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

					//Get the day state (availability)
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

					$title_text=JText::_('Booking ID').": ".$row['2'].", ".JText::_('Room').": ".$res.", ".JText::_('Price').": ".$dayprice.", ".JText::_('Offer').": ".$res2;
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
						
					$title_text=JText::_('Availability').": ".$count_available.", ".JText::_('Bookings').": ".$count_booked.", ".JText::_('Price').": ".$price.", ".JText::_('Offer').": ".$offer;
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

					$title_text=JText::_('Availability').": ".$count_available.", ".JText::_('Bookings').", ".$count_booked;
					$output.="bgcolor='".$backcolor."' title='".$title_text."'/>";
					$output.="<div class='bday';><b";

					if(($cur==$today['mday']) && ($name==$today['month'])) $output.=" style='color:#C00'";

					$output.=">$cur</b></div></td>";

					$cur++;
					$col++;

				}
					
			} else {
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

}







