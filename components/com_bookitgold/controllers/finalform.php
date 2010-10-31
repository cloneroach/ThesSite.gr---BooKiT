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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class BookitgoldControllerFinalform extends BookitgoldController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'finaldefault'  , 	'finaldefault' );
	}

	function finaldefault()
	{

		JRequest::setVar( 'view', 'finalform' );
		JRequest::setVar( 'layout', 'default'  );
		parent::display();
	}

	function makebooking (){

		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');
		$idcategory = JRequest::getVar('idcategory');
		$price = JRequest::getVar('price');
		$deposit = JRequest::getVar('deposit');
		$nguests = JRequest::getVar('nguests');
		$nchilds = JRequest::getVar('nchilds');
		$lchilds = JRequest::getVar('lchilds'); // Thessite
		$extra_ids = JRequest::getVar('extra_ids');
		$name = JRequest::getVar('name');
		$surname = JRequest::getVar('surname');
		$email = JRequest::getVar('email');
		$phone = JRequest::getVar('phone');
		$idcountry = JRequest::getVar('idcountry');
		$addr = JRequest::getVar('addr');
		$addr2 = JRequest::getVar('addr2');
		$city = JRequest::getVar('city');
		$pcode = JRequest::getVar('pcode');
		$preferences  = JRequest::getVar('preferences');
		$coupon_code =  JRequest::getVar('coupon_code');

		// Thessite- PayMethod Vars
		$ccno = JRequest::getVar('ccno');
		$card_type = JRequest::getVar('card_type');
		$pay_method = JRequest::getVar('pay_method');
		$exp_year = JRequest::getVar('exp_year');
		$exp_month = JRequest::getVar('exp_month');


		$db =& JFactory::getDBO();
		$params =& JComponentHelper::getParams('com_bookitgold');
		$currency = $params->get('currency','EUR');

		$valid_from_new = date ("Y-m-d",strtotime($valid_from));
		$valid_to_new = date ("Y-m-d",strtotime($valid_to));
		$fromDateTS = strtotime($valid_from_new);
		$toDateTS = strtotime($valid_to_new);
		$nights=round( ($toDateTS-$fromDateTS )/86400) ;

		//Search if guest already member
		$query = "SELECT idguests FROM #__bookitguests WHERE email='".$email."'";
		$db->setQuery($query);
		$result=$db->loadResult();
		if ($result!="" && $result>0)
		{
			//guest already in db
			$idguests = $result;
			$query =  "UPDATE #__bookitguests SET idcountry='".$idcountry."', name='".$name."'
			, surname='".$surname."', email='".$email."', addr='".$addr." ".$addr2." ".$pcode."'
			, city='".$city."' , phone='".$phone."' WHERE idguests='".$idguests."';";
			$db->setQuery( $query );
			$result = $db->query();
		}
		else
		{
			$data =new stdClass();
			$data->idguests=null;
			$data->idcountry=$idcountry;
			$data->name=$name;
			$data->surname=$surname;
			$data->email=$email;
			$data->addr=$addr." ".$addr2." ".$pcode;
			$data->city=$city;
			$data->phone=$phone;
			$db->insertObject( '#__bookitguests', $data, 'idguests' );
			$idguests= $db->insertid();
		}

		//Manage Coupons
		$query = "SELECT idcoupon, usable, used FROM #__bookitcoupon WHERE code='".$coupon_code."'";
		$db->setQuery($query);
		$coupon_res=$db->loadRow();


		if (count($coupon_res)>0 && $coupon_res['0']!="" && $coupon_res['0']>0 && $coupon_res['1']==1 && $coupon_res['2']==0)
		{
			$query =  "UPDATE #__bookitcoupon SET used='1' WHERE idcoupon='".$coupon_res['0']."'";
			$db->setQuery( $query );
			$result = $db->query();
			$idcoupon=$coupon_res['0'];

		}
		else
		$idcoupon=0;

		//Make the booking
		$data =new stdClass();
		$data->idbook = null;
		$data->idcoupon = $idcoupon;
		$data->idguests = $idguests;
		$data->idroom = 0;
		$data->idcategory = $idcategory;
		$data->nguests = $nguests;
		$data->nchilds = $nchilds;
		$data->lchilds = $lchilds; // Thessite
		$data->value_full = $price;
		$data->value_paid = 0;
		$data->value_pending = $price;
		$data->valid_from = $valid_from_new;
		$data->valid_to = $valid_to_new;
		$data->today = date("Y-m-d");
		$data->extra_ids = $extra_ids;
		$data->preferences = $preferences;
		$data->status = '2';

		// Thessite - Push data for pay_method
		//$data->pay_method = $pay_method;

		$db = JFactory::getDBO();
		$db->insertObject( '#__bookitbooking', $data, 'idbook' );
		$idbook =$db->insertid();

		//All Rooms
		$query = "SELECT idroom FROM #__bookitroom WHERE idcategory=".
		$db->quote($idcategory)." ORDER BY idroom;";
		$db->setQuery($query);
		$all_rooms = $db->loadResultArray();

		$avail_array = array();
		for ($i=0; $i<count($all_rooms); $i++)
		{
			$avail_array[$all_rooms[$i]]=0;
		}

		for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
		{
			$currentDateStr = date('Y-m-d',$currentDateTS);
			foreach ($all_rooms as $room)
			{
				$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idroom=".
				$db->quote($room)." AND today='".$currentDateStr."' AND availability != '1' ;";
				$db->setQuery($query);
				$r = $db->loadResult();
				if ($r>0)
				{
					$avail_array[$room] += 1;

				}
			}
		}
			
		$idroom_max = 0;
		$maxVal = 0;
		foreach ($avail_array as $key => $value)
		{
			if ($value== max($avail_array))
			{
				$idroom_max = $key;
				$maxVal= $value;
				break;
			}
		}
			
		if ($maxVal<$nights) //Room Change needed, try to book the idroom_max, if not available choose one room from the array
		{

			for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".
				$db->quote($idroom_max)." AND today='".$currentDateStr."';";
				$db->setQuery($query);
				$idbook_que = $db->loadResult();
				if ($idbook_que!=1)//The max room is available
				{
					$query =  "UPDATE #__bookitavailability SET availability='2', idbook='".$idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";
					$db->setQuery( $query );
					$result = $db->query();
				}
				else
				{


					foreach ($all_rooms as $room)
					{
						$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".
						$db->quote($room)." AND today='".$currentDateStr."';";
						$db->setQuery($query);
						$idbook_que = $db->loadResult();

						if ($idbook_que!=1)
						{
							$query =  "UPDATE #__bookitavailability SET availability='2', idbook='".$idbook."' WHERE idroom='".$room."' AND today='".$currentDateStr."'";
							$db->setQuery( $query );
							$result = $db->query();
							break;

						}

					}
				}
			}
			$preferences = "*This booking might need room changes.";
			$query =  "UPDATE #__bookitbooking SET preferences='".$preferences."' WHERE idbook='".$idbook."'";
			$db->setQuery( $query );
			$result = $db->query();


		}
		else //There is a room that can accommodate the guest for the whole period
		{
			//Set the room to booking
			$query =  "UPDATE #__bookitbooking SET idroom='".$idroom_max."' WHERE idbook='".$idbook."'";
			$db->setQuery( $query );
			$result = $db->query();
			for ($currentDateTS = $fromDateTS; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24))
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				$query =  "UPDATE #__bookitavailability SET availability='2', idbook='".$idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";
				$db->setQuery( $query );
				$result = $db->query();
					
			}
		}


		//Send Mails
		$params =& JComponentHelper::getParams('com_bookitgold');
		$currency = $params->get('currency');
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		$sender = array(
		$config->getValue( 'config.mailfrom' ),
		$config->getValue( 'config.fromname' ) );
		$mailer->setSender($sender);

		$recipients = array(/* $email, */$config->getValue( 'config.mailfrom' ) ); //Thessite
		$mailer->addRecipient($recipients);

		$title = JText::_("Booking Request Received");
		$intro1 = JText::_('Dear')." ".$name." ".$surname.",";
		$intro2 = JText::_('Thank you for your booking request. Your booking details are as follows.');
		$companyName=$config->getValue('config.fromname');
		$cop =JText::_("Copyright");
		$thessite = JText::_('<br /><a href="http://www.thessite.gr" target="_blank" style="font-size:10px; text-decoration:none">Design and Development by ThesSite.gr</a>');

/*
 * Start of Thessite Code.
 * 
 * Apostoli prwtou e-mail ston pelati
 * pou ton enimerwnei oti i kratisi pige sto sustima.
 * 
 * Parakatw o kwdikas gia na apostalei to e-mail.
 *  
 * $mailSender =& JFactory::getMailer();
 * $mailSender ->addRecipient( <recipient email> );
 * $mailSender ->setSender( array(  <sender email> ,<sender name>) );
 * $mailSender ->setSubject( <subject> );
 * $mailSender ->setBody(  <body> );
 * 
 * if (!$mailSender ->Send())
 * {
 * 	<Your error code management>
 * }
 */		
		
		if($pay_method == "1"){
			//$keimeno = JText::_('Dear')." ".$name." ".$surname.",";
			$keimeno = JText::_('Thank you for your reservation in "Akti Retzika".');
			$keimeno .= JText::_('In the following days you will receive the account number and following that your reservation confirmation at your e-mail.');
			$keimeno .= JText::_('We look forward to welcoming you!');
		} else {
			//$keimeno = JText::_('Dear')." ".$name." ".$surname.",";
			$keimeno = JText::_('Thank you for your reservation in "Akti Retzika".');
			$keimeno .= JText::_('Within the following days you will receive your reservation confirmation at your e-mail.');
			$keimeno .= JText::_('We look forward to welcoming you!');
		}
		
		// Thessite - Fetch the email logo
		$email_logo = $params->get('email_logo');
		$mail_body = "<table width='570' border='0' cellpadding='5' cellspacing='1'
		style='border: 1px solid #DEDEDE;
		font-family: verdana; 
		font-size: 10px; 
		background-color: #FFFFFF;' align='center'>";

		$file_path = JURI::base()."images/bookit/images/".$email_logo;
		$img_place ="<img src='".$file_path."' alt='logo' width='540' height='90'/>";
		if (isset($file_path) && $email_logo!="")
		{
			$body .="<tr bgcolor='#D9DDC2'>
				<td align='left' valign='top' bgcolor='#DEDEDE'>
				<div align='center'>".$img_place."</div></td>
				</tr>";
		}


		$mail_body .= "<table width='570' border='0' cellpadding='5' cellspacing='1'
		style='border: 1px solid #DEDEDE;
		font-family: verdana; 
		font-size: 10px; 
		background-color: #FFFFFF;' align='center'>
		<tr>
		<th style='background:#e8f1fa; color:#000; padding:8px; text-align:left;'>
			".$title."<br /><br />
			".$intro1."<br />
		</th>
		</tr>
		<tr>
		<tr bgcolor='#FFFFFF' style='border: 1px solid #DEDEDE;'>
				<td align='center' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$keimeno."</td>
		</tr>
		<tr align='center' valign='middle' bgcolor='#859032'>
		<td bgcolor='#AFAFAF'><span style='font-family: verdana; font-size: 10px; font-weight: normal; color: #FFFFFF;'>".$cop." &copy; ".$companyName."</span>".$thessite."</td>
		</tr>
		</table>";
		
		$info_mail =& JFactory::getMailer();
		$info_mail->addRecipient( $email );
		$info_mail->setSender( $sender );
		$info_mail->setSubject( "Booking Request Recieved" );
		$info_mail->setBody( $mail_body );
		$info_mail->isHTML( true );
		
		if( !$info_mail->Send() ){
			die("An error occured, please contact the developers at dev@thessite.gr !");
		}

		/* End of Thessite Code */

		//Accommodation Details
		$accommodation_header=JText::_("Accommodation Details");
		$check_in = JText::_("Date of Arrival"); // Thessite
		$check_out = JText::_("Date of Departure"); // Thessite
		$nights_str = JText::_("Nights");
		$guests_str = JText::_("Adults");
		$childs_str = JText::_("Children, 0 to 5 years old"); // Thessite
		$lchilds_str = JText::_("Children, 6 to 12 years old"); // Thessite
		$room_str = JText::_("Room type");
		$query = "SELECT name FROM #__bookitcategory WHERE idcategory='".$idcategory."'";
		$db->setQuery($query);
		$cat_name=$db->loadResult();
		$accommodation_table ="<table cellspacing='2' style='border:1px solid #ccc; font-size:12px;'>
         <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$room_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$cat_name."</td>
        </tr>
		<tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$check_in."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$valid_from."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$check_out."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$valid_to."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$nights_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$nights."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$guests_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$nguests."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$childs_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$nchilds."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$lchilds_str/* Thessite */."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$lchilds/* Thessite */."</td>
        </tr>
       </table>";

		//Guest Details
		$guest_header=JText::_("Guest Details");
		$fname_str = JText::_("First name");
		$lname_str = JText::_("Last name");
		$email_str = JText::_("Email");
		$phone_str = JText::_("Contact phone number");
		$addr_str = JText::_("Address");
		$query = "SELECT name FROM #__bookitcountry WHERE idcountry='".$idcountry."'";
		$db->setQuery($query);
		$country_name=$db->loadResult();
		$guest_table ="<table cellspacing='2' style='border:1px solid #ccc; font-size:12px;'>
         <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$fname_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$name."</td>
        </tr>
		<tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$lname_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$surname."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$email_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$email."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$phone_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$phone."</td>
        </tr>
        <tr>
          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$addr_str."</th>
          <td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$addr."<br/><br/>".$addr2."<br/><br/>".$pcode."<br/><br/>".$city."<br/><br/>".$country_name."</td>
        </tr>
       </table>";
		//Booking Preferences
		$extra_header=JText::_("Booking Preferences & Extra Services");
		$extra_left = JText::_("Service / Preference");
		$extra_right = JText::_("Quantity");
		$preferences_str =JText::_("Requests");
		$coupon_str =JText::_("Promotional Code");

		if ($extra_ids!="" || $preferences!="")
		{
			$extra_table ="<table cellspacing='2' style='border:1px solid #ccc;
					font-size:12px;'>
	         <tr>
	          <th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$extra_left."</th>
	          <td style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$extra_right."</td>
	        </tr>";
			$extras = explode (",", $extra_ids);
			foreach ($extras as $extra)
			{
				$nq = explode ("-", $extra);
				$query = "SELECT name FROM #__bookitextra WHERE idextra='".$nq['0']."';";
				$db->setQuery($query);
				$extra_name=$db->loadResult();

				if (count($nq)>1){
					$extra_table .= "<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$extra_name."</th>
	         		<td style='background:#fafafa;
					color:#000;
					padding:8px;'>".$nq['1']."</td>
	        		</tr>";
				}
				else {

					$extra_table .= "<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;'>".$extra_name."</th>
	         		<td style='background:#fafafa;
					color:#000;
					padding:8px;'></td>
	        		</tr>";
				}
			}
			if ($preferences!="")
			{
				$extra_table .= "<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;' colspan='2'>".$preferences_str."</th>
	        		</tr><tr><td style='background:#fafafa;
					color:#000;
					padding:8px;' colspan='2'>".$preferences."</td></tr>";
			}
			if ($coupon_code!="")
			{
				$extra_table .= "<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;' colspan='2'>".$coupon_str."</th>
	        		</tr><tr><td style='background:#fafafa;
					color:#000;
					padding:8px;' colspan='2'>".$coupon_code."</td></tr>";
			}

			$extra_table .="</table>";
		}
		else
		$extra_table="";

		//Cost
		$cost_header=JText::_("Cost");
		$total_str=JText::_("Grand Total");
		$deposit_str=JText::_("Deposit");
		$cost_table ="<table cellspacing='2' style='border:1px solid #ccc; font-size:12px;'>
      			<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;' colspan='2'>".$total_str."</th>
					<td style='background:#fafafa;
					color:#000;
					padding:8px;' colspan='2'>".$price." ".$currency."</td>
	          </tr>";
		if ($deposit>0)
		{
			$cost_table .= "<tr>
	          		<th style='background:#e8f1fa;
					color:#000;
					padding:8px;
					text-align:left;' colspan='2'>".$deposit_str." </th>
					<td style='background:#fafafa;
					color:#000;
					padding:8px;' colspan='2'>".$deposit." ".$currency."</td>
	          		</tr>";
		}
		$cost_table .="</table>";


		//$cancellation_policy = $params->get('cancellation_policy');
		//$terms_conditions = $params->get('terms_conditions');
		$contact_details=$params->get('contact_details');
		$email_logo = $params->get('email_logo');

		$cancellation_str=JText::_("Cancellation Policy");
		$terms_str = JText::_('Terms & Conditions'); //JText::_("Terms &amp; Conditions");
		$contact_str = JText::_("Contact Details");
		$subject = JText::_("Booking request confirmation");


		$refid = $idbook+1000;
		$refid_str = JText::_("Booking Reference ID:");

		$body ="<table width='570' border='0' cellpadding='5' cellspacing='1'
		style='border: 1px solid #DEDEDE;
		font-family: verdana; 
		font-size: 10px; 
		background-color: #FFFFFF;' align='center'>";

		$file_path = JURI::base()."images/bookit/images/".$email_logo;
		$img_place ="<img src='".$file_path."' alt='logo' width='540' height='90'/>";
		if (isset($file_path) && $email_logo!="")
		{
			$body .="<tr bgcolor='#D9DDC2'>
				<td align='left' valign='top' bgcolor='#DEDEDE'>
				<div align='center'>".$img_place."</div></td>
				</tr>";
		}
		$body .="<tr bgcolor='#FFFFFF'>
    			<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
    		 	<p style='font-family: verdana;	font-size: 12px; font-weight: bold;	color: #000033;'>".$refid_str." ".$refid."</p>
    			</td>
  				</tr>
				<tr bgcolor='#FFFFFF'>
				<td align='left' valign='top' bgcolor='#ffffee' >
				<p style='font-family: verdana;	
					font-size: 12px; 
					font-weight: normal;
					color: #000000;' >".$intro1."</p>
    			<p style='font-family: verdana;
					font-size: 12px;
					font-weight: normal;
					color: #000000;'>".$intro2."</p>
      			</td>    
				</tr>
			
				<tr bgcolor='#FFFFFF' style='border: 1px solid #DEDEDE;'>
				<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$accommodation_header."</p>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$accommodation_table."</p>
				</td>
				</tr>
								
				<tr bgcolor='#FFFFFF'>
				<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$guest_header."</p>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$guest_table."</p>
				</td>
				</tr>
				
				<tr bgcolor='#FFFFFF'>
				<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$extra_header."</p>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$extra_table."</p>
				</td>
				</tr>
				
				<tr bgcolor='#FFFFFF'>
				<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$cost_header."</p>
				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$cost_table."</p>
				</td>
				</tr>";

		/* Start of Thessite code
		 ** Bypass the bug from Joomfish not getting the ToC / CP from
		 ** the table components, also deposit percentages
		 */

		// Deposit Percentage
		//$deposit_percent = floatval(30);
		//$deposit_fixed = floatval(0);

		// Cancellation Policy
		$dateformatcode = $params->get('dateformat');

		if ($dateformatcode==""||$dateformatcode==3){
			$d1='d-m-Y';
			$d2='%d-%m-%Y';
		}
		else if ($dateformatcode==1){
			$d1='Y-m-d';
			$d2='%Y-%m-%d';
		}
		else if ($dateformatcode==2){
			$d1='m/d/Y';
			$d2='%m/%d/%Y';
		}

		//$afiksi = $this->valid_from;
		$afiksi = $valid_from;
		$cancel_date_1 = date($d1, strtotime("-20 day".$afiksi) ); // 100% pisw
		$cancel_date_2 = date($d1, strtotime("-19 day".$afiksi) ); // apo gia 50%
		$cancel_date_3 = date($d1, strtotime("-3 day".$afiksi) ); // mexri gia 50%
		$cancel_date_4 = date($d1, strtotime("-2 day".$afiksi) ); // 0% pisw

		$cancellation_policy = JText::_("Your deposit is fully refundable until");
		$cancellation_policy .= " ".$cancel_date_1.". ";
		$cancellation_policy .= JText::_("From");
		$cancellation_policy .= " ".$cancel_date_2." ";
		$cancellation_policy .= JText::_("until");
		$cancellation_policy .= " ".$cancel_date_3.", ";
		$cancellation_policy .= JText::_('50% of your deposit may be refunded. While from');
		$cancellation_policy .= " ".$cancel_date_4." ";
		$cancellation_policy .= JText::_("your deposit is not refundable.");

		/* End of Thessite code */

		if ($cancellation_policy!="")
		{
			$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$cancellation_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$cancellation_policy."</p></td>
  					</tr>";
		}
		/* Start of Thessite code */

		//$booking_pay_date = date( $d1, strtotime("+2 day") );
		//$terms_conditions = JText::_('For your booking confirmation you have to deposit 30% of the total price of your reservation until');
		//$terms_conditions .= " ".$booking_pay_date.".";

		/* Ypologismos tou posou pou prokatabalei
		 * Ean minei gia 1 mexri 4 imeres, dinei to poso pou antistixei se 1 bradia
		 * Ean minei gia 4+ imeres, dinei to 25% pou antistixei sto kostos tis diamonis
		 */
		//$timi = $this->price;
		//$nuxtes = $this->nnights;

		//$price_per_n = $timi / $nuxtes;
		$price_per_n = $price / $nights;


		//if( $this->nnights <= 4 ){
		if( $nights <= 4 ){
			$deposit_percent = 0;
			$deposit_fixed = $price_per_n;
			// ToC
			$booking_pay_date = date( $d1, strtotime("+2 day") );
			$terms_conditions = JText::_('For your booking confirmation you have to deposit')." ";
			$terms_conditions .= $price_per_n;
			$terms_conditions .= " EUR ".JText::_('until');
			$terms_conditions .= " ".$booking_pay_date.".";
			$terms_conditions .= "<br />".JText::_('(amount that equals one night stay)');

		} else {
			$deposit_percent = floatval(25);
			$deposit_fixed = 0;
			$teliko_poso = $total_price * $deposit_percent / 100;
			$rounded = floor($teliko_poso); // Strogkilop. pros ta katw [ 10,5 ==> 10 ]
			// ToC
			$booking_pay_date = date( $d1, strtotime("+2 day") );
			$terms_conditions = JText::_('For your booking confirmation you have to deposit')." ";
			$terms_conditions .= $rounded;
			$terms_conditions .= " EUR ".JText::_('until');
			$terms_conditions .= " ".$booking_pay_date.".";
			$terms_conditions .= "<br />".JText::_('(amount that equals 25% of you total stay)');
		}

		/* End of Thessite code */
		if ($terms_conditions!="")
		{
			$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$terms_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$terms_conditions."</p></td>
  					</tr>";
		}
		//		$bank_acc_deposit = JText::_("Deposit to Bank Account");
		//		$bank_body = "<strong>".JText::_("National Bank of Greece")."</strong><br />";
		//		$bank_body .= JText::_("<strong>Name:</strong> Sotiria Rentzika")."<br />";
		//		$bank_body .= "<strong>".JText::_("Account").":</strong>"." 23774787719<br />";
		//		$bank_body .= "<strong>".JText::_("IBAN").":</strong>"." GR4501102370000023774787719<br />";
		//		$bank_body .= "<strong>".JText::_("BIC").":</strong>"." ETHNGRAA<br /><br />";
		//		$bank_body .= JText::_("(Please note your name and Reference ID from the email you recieved).");
		//
		//		$body .= "  <tr bgcolor='#FFFFFF'>
		//					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
		//					<p style='font-family: verdana;
		//					font-size: 10px;
		//					font-weight: bold;
		//					color: #000033;'>".$bank_acc_deposit."</p>
		//      				<p style='font-family: verdana;
		//					font-size: 10px;
		//					font-weight: normal;
		//					color: #000000;'>".$bank_body."</p></td>
		//  					</tr>";

		if($pay_method == 1){
			$body .= "  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".JText::_("Deposit Method")."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'><strong>".JText::_("Deposit to Bank Account")."</strong></p></td>
  					</tr>";
		} else {
			$body .= "  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".JText::_("Deposit Method")."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'><strong>".JText::_('Credit Card').
						"</strong> <br />Number: ".$ccno.
						"<br />Card Type: ".$card_type.
						"<br />Exp. Month: ".$exp_month.
						"<br />Exp. Year: ".$exp_year.
						"</p></td>
  					</tr>";
		}

		if ($contact_details!="")
		{
			$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$contact_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$contact_details."</p></td>
  					</tr>";
		}

		$body .="<tr align='center' valign='middle' bgcolor='#859032'>
				<td bgcolor='#AFAFAF'><span style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #FFFFFF;'>".$cop." &copy; ".$companyName."</span>".$thessite."</td>
				</tr>
				</table>";

		$mailer->setSubject($subject);
		$mailer->isHTML(true);
		$mailer->setBody($body);
		if ($email_logo!="")
		{
			$mla = explode (".",$email_logo);
			if (count($mla)>1)
			{
				if ($mla['1']=='jpg' || $mla['1']=='jpeg' || $mla['1']=='')
				$img_ext = 'image/jpeg';
				else if ($mla['1']=='gif')
				$img_ext = 'image/gif';
				$mailer->AddEmbeddedImage(JURI::base().'images'.DS.'bookit'.DS.'images'.DS.$email_logo, 'logo_id', 'logo.jpg', 'base64', $img_ext );
			}
		}

		//create root
		$results = new JSimpleXMLElement('results', array('id' => 0));
		$send =& $mailer->Send();
		if ( $send != true )
		{
			$results->addAttribute('mail',-1);
		}
		else
		{
			$results->addAttribute('mail',1);
		}

		$results->addAttribute('idbook',$idbook);
		echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
		echo $results->toString();
		$document =& JFactory::getDocument();
		$document->setMimeEncoding('text/xml');

	}


	function confirmbooking (){
		$idbook = JRequest::getVar('idbook');
		$params =& JComponentHelper::getParams('com_bookitgold');
		$redirect = $params->get('payment_return');
		$payment_cancel = $params->get('payment_cancel');
		//		$deposit_fixed = floatval ($params->get('deposit_fixed'));
		//		$deposit_percent = floatval ($params->get('deposit_percent'));
		// Thessite code, bypass paypal bug from Joomfish

		//$deposit_fixed = floatval(0);
		//$deposit_percent = floatval(25);

		//Change booking status to confirmed
		$db = JFactory::getDBO();
		//Check if still not booked
		$query = "SELECT * from #__bookitbooking WHERE idbook='".$idbook."'";
		$db->setQuery( $query );
		$booking= $db->loadRow();

		$valid_from_new = date ("Y-m-d", strtotime($booking['9']));
		$valid_to_new = date ("Y-m-d",strtotime($booking['10']));
		$fromDateTS = strtotime($valid_from_new);
		$toDateTS = strtotime($valid_to_new);
		$nights=round( ($toDateTS-$fromDateTS )/86400) ;
		$stillAvailable=true;

		//Set redirect url
		if(stristr($redirect,JURI::base())) //If not relative path
		{

			$redirect = substr($redirect,strlen(JURI::base()));
			$redirect = JURI::base()."".$redirect;
		}
		else
		{
				
			$redirect = JURI::base()."".$redirect;
		}

		echo $redirect;
		if(stristr($payment_cancel,JURI::base())) //If not relative path
		{
				
			$payment_cancel = substr($payment_cancel,strlen(JURI::base()));
			$payment_cancel = JURI::base()."".$payment_cancel;
		}
		else
		{
				
			$payment_cancel = JURI::base()."".$payment_cancel;
		}

		for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
		{
			$currentDateStr = date('Y-m-d',$currentDateTS);
			$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".
			$db->quote($booking['3'])." AND today='".$currentDateStr."' AND availability != '1' ;";
			$db->setQuery($query);
			$count_available = $db->loadResult();

			if ($count_available<=0)
			{
				$stillAvailable=false;
				break;
			}
		}
		if ($stillAvailable)
		{
			//All Rooms
			$query = "SELECT idroom FROM #__bookitroom WHERE idcategory=".
			$db->quote($booking['3'])." ORDER BY idroom;";
			$db->setQuery($query);
			$all_rooms = $db->loadResultArray();

			$avail_array = array();
			for ($i=0; $i<count($all_rooms); $i++)
			{
				$avail_array[$all_rooms[$i]]=0;
			}

			for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				foreach ($all_rooms as $room)
				{
					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idroom=".
					$db->quote($room)." AND today='".$currentDateStr."' AND availability != '1' ;";
					$db->setQuery($query);
					$r = $db->loadResult();
					if ($r>0)
					{
						$avail_array[$room] += 1;

					}
				}
			}

			$idroom_max = 0;
			$maxVal = 0;
			foreach ($avail_array as $key => $value)
			{
				if ($value== max($avail_array))
				{
					$idroom_max = $key;
					$maxVal= $value;
					break;
				}
			}

			if ($maxVal < $nights) //Room Change needed, try to book the idroom_max, if not available choose one room from the array
			{

				for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
				{
					$currentDateStr = date('Y-m-d',$currentDateTS);
					$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".
					$db->quote($idroom_max)." AND today='".$currentDateStr."';";
					$db->setQuery($query);
					$idbook_que = $db->loadResult();
					if ($idbook_que!=1)//The max room is available
					{
						$query =  "UPDATE #__bookitavailability SET availability='1', idbook='".$idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";
						$db->setQuery( $query );
						$result = $db->query();
					}
					else
					{
						foreach ($all_rooms as $room)
						{
							$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".
							$db->quote($room)." AND today='".$currentDateStr."';";
							$db->setQuery($query);
							$idbook_que = $db->loadResult();

							if ($idbook_que!=1)
							{
								$query =  "UPDATE #__bookitavailability SET availability='1', idbook='".$idbook."' WHERE idroom='".$room."' AND today='".$currentDateStr."'";
								$db->setQuery( $query );
								$result = $db->query();
								break;

							}
						}
					}
				}
			}
			else //There is a room that can accommodate the guest for the whole period
			{
				//Set the room to booking
				$query =  "UPDATE #__bookitbooking SET idroom='".$idroom_max."' WHERE idbook='".$idbook."'";
				$db->setQuery( $query );
				$result = $db->query();
				for ($currentDateTS = $fromDateTS; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24))
				{
					$currentDateStr = date('Y-m-d',$currentDateTS);
					$query =  "UPDATE #__bookitavailability SET availability='1', idbook='".$idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";
					$db->setQuery( $query );
					$result = $db->query();
				}
			}
			//Update the paid, due fields
			$value_f = floatval ($booking['8']);
			if ($deposit_percent>0)
			{
				$value_p = round ($value_f * floatval ($deposit_percent)/100, "2");
			}
			else if ($deposit_fixed>0)
			{
				$value_p=$deposit_fixed;
			}
			$value_d = $value_f - $value_p;
				
			$query =  "UPDATE #__bookitbooking SET status='1', value_paid='".$value_p."', value_pending='".$value_d."' WHERE idbook='".$idbook."'";
			$db->setQuery( $query );
			$result = $db->query();

			//send confirmation emails
			$mailer =& JFactory::getMailer();
			$config =& JFactory::getConfig();
			$sender = array(
			$config->getValue( 'config.mailfrom' ),
			$config->getValue( 'config.fromname' ) );
			$mailer->setSender($sender);


			$params =& JComponentHelper::getParams('com_bookitgold');
			//			$cancellation_policy = $params->get('cancellation_policy'); //Thessite
			//			$terms_conditions = $params->get('terms_conditions');
			$contact_details=$params->get('contact_details');

			$query = "SELECT idguests FROM #__bookitbooking WHERE idbook='".$idbook."'";
			$db->setQuery($query);
			$idguest = $db->loadResult();
			$query = "SELECT * FROM #__bookitguests WHERE idguests='".$idguest."'";
			$db->setQuery($query);
			$guest = $db->loadRow();

			$recipients = array( /*$guest['4'],*/ $config->getValue( 'config.mailfrom' ) ); // Thessite
			$mailer->addRecipient($recipients);

			$email_logo = $params->get('email_logo');
			$cancellation_str=JText::_("Cancellation Policy");
			$terms_str=JText::_('Terms & Conditions');
			$contact_str = JText::_("Contact Details");
			$subject = JText::_("Booking confirmation");
			$companyName=$config->getValue('config.fromname');
			$cop =JText::_("Copyright");

			$refid = $idbook+1000;

			$title=JText::_("Booking Confirmation");
			$intro1 = JText::_('Dear')." ".$guest['2']." ".$guest['3'].",";
			$intro2 = JText::_('Your booking request with reference ID:')."<b>".$refid."</b>".JText::_(" is now confirmed!");
			$intro3 = JText::_("We look forward to welcoming you to our hotel!");

			$img_place ="<img src='cid:logo_id' alt='logo' width='540' height='90'/>";
			$body ="<table width='570' border='0' cellpadding='5' cellspacing='1'
		style='border: 1px solid #DEDEDE;
		font-family: verdana; 
		font-size: 10px; 
		background-color: #FFFFFF;' align='center'>";

			$file_path = JURI::base()."images/bookit/images/".$email_logo;
			$img_place ="<img src='".$file_path."' alt='logo' width='540' height='90'/>";
			if (isset($file_path))
			{
				$body .="<tr bgcolor='#D9DDC2'>
				<td align='left' valign='top' bgcolor='#DEDEDE'>
				<div align='center'>".$img_place."</div></td>
				</tr>";
			}
			$body .="<tr bgcolor='#FFFFFF'>
				<td align='left' valign='top' bgcolor='#ffffee' >
				<p style='font-family: verdana;	
					font-size: 12px; 
					font-weight: normal;
					color: #000000;' >".$intro1."</p>
    			<p style='font-family: verdana;
					font-size: 12px;
					font-weight: normal;
					color: #000000;'>".$intro2." ".$intro3."</p>
      			</td>    
				</tr>";

			if ($cancellation_policy!="")
			{
				$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$cancellation_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$cancellation_policy."</p></td>
  					</tr>";
			}
			if ($terms_conditions!="")
			{
				$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$terms_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$terms_conditions."</p></td>
  					</tr>";
			}
			if ($contact_details!="")
			{
				$body .="  <tr bgcolor='#FFFFFF'>
					<td align='left' valign='top' bgcolor='#ffffee' style='border-bottom: 1px solid #DEDEDE;'>
					<p style='font-family: verdana;
					font-size: 10px;
					font-weight: bold;
					color: #000033;'>".$contact_str."</p>
      				<p style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #000000;'>".$contact_details."</p></td>
  					</tr>";
			}

			$body .="<tr align='center' valign='middle' bgcolor='#859032'>
				<td bgcolor='#AFAFAF'><span style='font-family: verdana;
					font-size: 10px;
					font-weight: normal;
					color: #FFFFFF;'>".$cop." &copy; ".$companyName."</span>".$thessite."</td>
				</tr>
				</table>";

			$mailer->setSubject($subject);
			$mailer->isHTML(true);
			$mailer->setBody($body);
			if ($email_logo!="")
			{
				$mla = explode (".",$email_logo);
				if (count($mla)>1)
				{
					if ($mla['1']=='jpg' || $mla['1']=='jpeg' || $mla['1']=='')
					$img_ext = 'image/jpeg';
					else if ($mla['1']=='gif')
					$img_ext = 'image/gif';
					$mailer->AddEmbeddedImage(JURI::base().'images'.DS.'bookit'.DS.'images'.DS.$email_logo, 'logo_id', 'logo.jpg', 'base64', $img_ext );
				}
			}
			$send =& $mailer->Send();
			//redirect to Succeed URL if spacified
			if ($redirect!="")
			{
				$this->setRedirect(JRoute::_($redirect),'');

			}
			else
			{
				$redirect = JURI::base();
				$this->setRedirect(JRoute::_($redirect),'');
				//header ("Location: $redirect");
			}


		}//end of still available
		else
		{
			//Send mail to admin to resolve this
			$mailer =& JFactory::getMailer();
			$config =& JFactory::getConfig();
			$sender = array(
			$config->getValue( 'config.mailfrom' ),
			$config->getValue( 'config.fromname' ) );
			$mailer->addRecipient( $config->getValue( 'config.mailfrom' ));
			$subject = JText::_("Booking conflict");
			$mailer->setSubject($subject);
			$refid=$idbook+1000;
			$body   = JText::_("There is a problem with the booking with reference ID: ").$refid.".\n";
			$mailer->setBody($body);
			$send =& $mailer->Send();
			if ($payment_cancel!="")
			{
				$this->setRedirect(JRoute::_($payment_cancel),'');
			}
			else
			{
				$payment_cancel = JURI::base();
				$this->setRedirect(JRoute::_($payment_cancel),'');
			}
		}

	}

}
?>