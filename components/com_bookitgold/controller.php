<?php

/**

 * BooKitGold - Joomla Booking Management Component

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



// No direct access



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

class BookitgoldController extends JController

{

	/**

	 * Method to display the view

	 *

	 * @access public

	 */

	function display()

	{

		parent::display();

	}



	function searchResults ()

	{

		$valid_from = JRequest::getVar('valid_from');

		$valid_to = JRequest::getVar('valid_to');

		$idcategory = JRequest::getVar('idcategory');

		$nguests = JRequest::getVar('nguests');

		$nchilds = JRequest::getVar('nchilds');
		$lchilds = JRequest::getVar('lchilds'); // Thessite

		

	

		

		$db =& JFactory::getDBO();

		//Check for guests
		/**
		** Thessite
		** Na mpei kwdikas wste na diabazei ap ton pinaka 
		** to $lchilds ??
		**/
		$query = "SELECT nguests,nchilds,lchilds FROM #__bookitcategory WHERE idcategory=".

		$db->quote($idcategory).";";

		$db->setQuery($query);

		$r = $db->loadRow();

		if ($r['0'] < $nguests || $r['1'] < $nchilds || $r['2'] < $lchilds) // Thessite

		{

			$results = new JSimpleXMLElement('results', array('id' => 0));

			$results->addAttribute('available',-1);

			echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";

			echo $results->toString();

			$document =& JFactory::getDocument();

			$document->setMimeEncoding('text/xml');

		}

		else

		{
			$sunolo_paidia = $nchilds + $lchilds;
			
			if( $sunolo_paidia > 1 ){
				$child = JText::_("Children");
			} else if( $sunolo_paidia == 1 ){
				$child = JText::_("Child");
			} else if( $sunolo_paidia == 0 ){
				$child = JText::_("Childs");
			}
					
			if ($nguests>1) {
				$adult=JText::_("Adults");
			} else {
				$adult=JText::_("Adult");
			}
			

				

			$query = "SELECT * FROM #__bookitcategory WHERE idcategory=".

			$db->quote($idcategory).";";

			$db->setQuery($query);

			$category_details = $db->loadRow();



			$params =& JComponentHelper::getParams('com_bookitgold');

			$img_width = $params->get('width','96');

			$img_height = $params->get('height','96');

			$currency = $params->get('currency','EUR');

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



			//create root

			$results = new JSimpleXMLElement('results', array('id' => 0));

			$price=0;

			$pre_offer_price=0;	

			$res=0;

			$offer="";

			$below_text="";

			for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)

			{

				$currentDateStr = date('Y-m-d',$currentDateTS);



				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".

				$db->quote($idcategory)." AND today='".$currentDateStr."' AND availability != '1' ;";

				$db->setQuery($query);

				$count_available = $db->loadResult();

				

				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".

				$db->quote($idcategory)." AND today='".$currentDateStr."' AND availability = '1' ;";

				$db->setQuery($query);

				$count_booked = $db->loadResult();

					

				if ($count_available<=0 ) //Found a day with no availability

				{

					$results->addAttribute('available',0);

					echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";

					echo $results->toString();

					$document =& JFactory::getDocument();

					$document->setMimeEncoding('text/xml');

					return false;

				}

				//Price Calculation

				//Get the day price, if it is defined in the availability table use that price, otherwise get the price of the room  category

				$query =  "SELECT price, idoffer FROM #__bookitavailability WHERE idcategory='".$idcategory."' AND today='".$currentDateStr."'";

				$db->setQuery( $query );

				$price_offer = $db->loadRow();

				if ($price_offer['0']==0)

				{

					$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$idcategory."'";

					$db->setQuery( $query );

					$price = $db->loadResult();

				}

				else

					$price=$price_offer['0'];

				

				$pre_offer_price+=$price;

				//On this price it may special offer

				//Check for special offer

				if ($price_offer['1'] >0) //an offer applies for the day, go get it

				{

					$query =  "SELECT * FROM #__bookitspecialoffer WHERE idoffer='".$price_offer['1']."'";

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

		

				$date_str=substr(date('l',$currentDateTS),0,3)." ".date('d',$currentDateTS)." ".date('M',$currentDateTS);

				$below_text .= "<div class='bookit_search_result_below_cell'>".$date_str." <br />

				<div class='bookit_search_result_below_cell_price'>".$price." ".$currency."</div></div>";

				$res = $res+$price;



			}

			if ($cnt>1)

			$night=JText::_("Nights");

			else

			$night=JText::_("Night");

			$results->addAttribute('available',1);



			$facilities = preg_split('%[\n,]+%', $category_details['5']);

			if (count($facilities)>0)

			{

				$amen = JText::_("Amenities");

				$facilities_str = 	"<b>".$amen."</b><br />";

				foreach ($facilities as $k => $v) {

					$facilities_str.="&#8226; ".$v." <br />";

					

				}

			}

			else

				$facilities_str="";

			$img_src="";

			if ($category_details['7']!="")

			{

				$img_src=JURI::root()."/images/bookit/images/".$category_details['7'];

				//$img_size = getimagesize($img_src); 

				if ($img_width>=$img_height) 

					$img_ori= 'width'; 

				else 

					$img_ori= 'height';



				$img_str = "<img style='".$img_ori.": 100%;' src='".$img_src."' />";

			}

			else

			{

				$img_src=JURI::root()."/images/bookit/images/sample.png";

				//$img_size = getimagesize($img_src); 

				if ($img_width>=$img_height) 

					$img_ori= 'width'; 

				else 

					$img_ori= 'height';



				$img_str = "<img style='".$img_ori.": 100%;' src='".$img_src."' alt='".JText::_("Photos not available")."' />";

			}



			if ($pre_offer_price!=$res)//we wave discount

			{

				

				$price_str = "<div class='bookit_search_del'>".$pre_offer_price."</div>".$res."<br />".$currency;

				$offer = JText::_("Special")." ".JText::_("Offer!");

				

			}

			else

			{

				$price_str = $res." ".$currency;

				$offer="";

			}

	

		

			

			$count_childs = $nchilds + $lchilds; // Thessite

			$res_row = "<div class='bookit_search_result_row'>

							<div class='bookit_search_result_header'>

				 				<div class='bookit_search_result_header_text'>".$category_details['1']."&nbsp;".JText::_("Room for")."&nbsp;</div>

				 				<div class='bookit_search_result_header_desc'>".$nguests." ".$adult.", ".$count_childs." ".$child.", ".$cnt." ".$night."</div>

				 			</div>

				 			<div id='bookit_search_clear'></div>

				 			<a class='p1' hrem='#v'>".$img_str."<b><img class='large' src='".$img_src."' alt=''/></b></a>

							<div class='bookit_search_result_desc'> <b>".JText::_("Description")."</b> <br/>".

				 			$category_details['6']."

				 			</div>

				 			<div class='bookit_search_result_fas'> ".

				 			$facilities_str."

				 			</div>

				 			<div class='bookit_search_result_right'>

				 				<div class='bookit_search_result_price'>".$price_str."</div>

				 				<div id='bookit_search_clear'></div>

				 				<div class='bookit_search_result_nights'>".$cnt." ".$night."</div>

				 				<div id='bookit_search_clear'></div>

				 				<div class='bookit_search_result_offer'>".$offer."</div>

			  					<div id='bookit_button_div' class='bookit_button'>

			  					<div id='bookit_search_clear'></div>

			  					<button type='submit'>".JText::_("Book Now!")."</button>

			  					</div>

			  				</div>

			  				<div id='bookit_search_clear'></div>

			  				<div class='bookit_search_result_below'>

			  				<div class='bookit_search_result_header'>

				 				<div class='bookit_search_result_header_text'>".

								JText::_('Daily Rates')."

				 				</div>

				 			</div>

			  				<div class='bookit_search_result_below_cell_header'>

			  				".$below_text."</div></div>

			  			</div>";

				 

				 $results->addAttribute('result',$res_row);

				 $results->addAttribute('nnights',$cnt);

				 $results->addAttribute('price',$res);

				 $results->addAttribute('pricereg',$pre_offer_price);

				    

				 echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";

				 echo $results->toString();

				 $document =& JFactory::getDocument();

				 $document->setMimeEncoding('text/xml');

		}

			

	}



	function calendar (){

		$output = '';

		$month = JRequest::getVar('month');

		$year = JRequest::getVar('year');



		$idcategory = JRequest::getVar('idcategory');

		$db =& JFactory::getDBO();



		//Get colors

		$params =& JComponentHelper::getParams('com_bookitgold');

		$available_color = $params->get('available_color','#CCFFCC');

		$booked_color = $params->get('booked_color','#FF6666');

		$pending_color = $params->get('pending_color','#FFCC66');

		$other_color = $params->get('other_color','#CCCCFF');

		$extra_availability = $params->get('extra_availability','Other');

		$none_color="#FFFFFF";





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

				if ($idcategory>0)

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

						$query = "SELECT availability FROM #__bookitavailability WHERE idcategory=".

						$db->quote($idcategory)." AND today='".$currentDay."' AND availability != '1' ;";

						$db->setQuery($query);

						$state = $db->loadResult();



						//Get the day state (availability)

						if ($state==0 || $state=="")

						{

							if ($params->get('show_available','1')==1)

								$backcolor = $available_color;

							else

								$backcolor = $none_color;

						}

						else if ($state==2)

						{

							if ($params->get('show_pending','1')==1)

								$backcolor = $pending_color;

							else

								$backcolor = $available_color;

						}

						else if ($state==3)

						{

							if ($params->get('show_extra','0')==1)

								$backcolor = $other_color;

							else

								$backcolor = $available_color;

						}

					}

					else if ($count_available<=0 && $count_booked>0)

					{

						

						if ($params->get('show_booked','1')==1)

							$backcolor = $booked_color;

						else

							$backcolor = $available_color;

					}

					else

					{

						if ($params->get('show_available','1')==1)

							$backcolor = $available_color;

						else

							$backcolor = $none_color;

					}	





					$output.="bgcolor='".$backcolor."' />";

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

						$query = "SELECT availability FROM #__bookitavailability WHERE idcategory=".

						$db->quote($idcategory)." AND today='".$currentDay."' AND availability != '1' ;";

						$db->setQuery($query);

						$state = $db->loadResult();



						//Get the day state (availability)

						if ($state==0 || $state=="")

						{

							if ($params->get('show_available','1')==1)

								$backcolor = $available_color;

							else

								$backcolor = $none_color;

						}

						else if ($state==2)

						{

							if ($params->get('show_pending','1')==1)

								$backcolor = $pending_color;

							else

								$backcolor = $available_color;

						}

						else if ($state==3)

						{

							if ($params->get('show_extra','0')==1)

								$backcolor = $other_color;

							else

								$backcolor = $available_color;

						}

					}

					else if ($count_available<=0 && $count_booked>0)

					{

						if ($params->get('show_booked','1')==1)

							$backcolor = $booked_color;

						else

							$backcolor = $available_color;

					}

					else

					{

						if ($params->get('show_available','1')==1)

							$backcolor = $available_color;

						else

							$backcolor = $none_color;

					}	



					$output.="bgcolor='".$backcolor."' />";

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

		echo "<table>

				<tr>";

		if ($params->get('show_available','1')==1)

		echo "<td bgcolor='".$available_color."' width='10px' height='10px'>

							</td><td>".JText::_( 'Available' )."</td>";	

		if ($params->get('show_booked','1')==1)

		echo "<td bgcolor='".$booked_color."' width='10px' height='10px'>

							</td><td>".JText::_( 'Booked' )."</td>";

		if ($params->get('show_pending','1')==1)

		echo "<td bgcolor='".$pending_color."' width='10px' height='10px'>

							</td><td>".JText::_( 'Pending' )."</td>";

		if ($params->get('show_extra','0')==1)

		echo "<td bgcolor='".$other_color."' width='10px' height='10px'>

							</td><td>".$extra_availability."</td>";

		

		echo "</tr></table>";



	}



}

