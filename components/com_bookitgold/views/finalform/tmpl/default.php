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

defined('_JEXEC') or die('Restricted access');

$stylesheet = 'final_form.css';

$path = 'components/com_bookitgold/css/';

JHTML::stylesheet($stylesheet, $path);

$params =& JComponentHelper::getParams('com_bookitgold');

$form_msg = $params->get('form_msg');

$paypal_mail = $params->get('paypal_mail');

$payment_return = $params->get('payment_return');

$payment_cancel = $params->get('payment_cancel');





$db =& JFactory::getDBO();

$sql = "SELECT idcountry, name FROM #__bookitcountry ORDER BY name";

$db->setQuery($sql);


$select_country = JText::_('-Select Country-');
//$results[] = JHTML::_('select.option', 0, '-Select Country-', 'idcountry', 'name' );
$results[] = JHTML::_('select.option', 0, $select_country, 'idcountry', 'name' );

$results = array_merge( $results, $db->loadObjectList() );

$lists['country']  = JHTML::_('select.genericList', $results, 'idcountry', 'class="bookit_final_input" size="1"', 'idcountry', 'name');



?>

<fieldset class="bookit_final_fieldset"><legend class="bookit_final_legent"><?php echo JText::_("Guest Information");?> </legend>

<form action="<?php echo JURI::base() ?>index.php" name="finalForm" method="post">

<div id="bookit_final_form">

<?php $star = "<span class='bookit_star'>*</span>" ?>

<?php

 if ($form_msg!="")

 {

?>

<div id="bookit_final_admin_msg" class="bookit_final_full">

<?php echo $form_msg; ?>



</div>

<?php }?>


<div id="bookit_final_fname_left" class="bookit_final_left"><?php echo JText::_("First Name").$star;?></div>	

<div id="bookit_final_fname_right" class="bookit_final_right">

<input type="text" name="name" id="name" class="bookit_final_input" /> </div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_fname_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter your first name.");?>

</div>

<div id="bookit_final_clear"></div>		



<div id="bookit_final_lname_left" class="bookit_final_left"><?php echo JText::_("Surname").$star;?></div>	

<div id="bookit_final_lname_right" class="bookit_final_right">

<input type="text" name="surname" id="surname" class="bookit_final_input" /> </div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_lname_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter your surname.");?>

</div>

<div id="bookit_final_clear"></div>	



<div id="bookit_final_email_left" class="bookit_final_left"><?php echo JText::_("Email Address").$star;?></div>	

<div id="bookit_final_email_right" class="bookit_final_right">

<input type="text" name="email" id="email" class="bookit_final_input" /></div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_email_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter your email address.");?>

</div>

<div id="bookit_final_email_invalid_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter a valid email address.");?>

</div>

<div id="bookit_final_clear"></div>	





<div id="bookit_final_cemail_left" class="bookit_final_left"><?php echo JText::_("Confirm Email Address").$star;?></div>	

<div id="bookit_final_cemail_right" class="bookit_final_right">

<input type="text" name="cemail" id="cemail" class="bookit_final_input" /></div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_cemail_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please confirm your email address.");?>

</div>

<div id="bookit_final_cemail_invalid_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please make sure that the two email addresses you have entered are identical, and correct.");?>

</div>

<div id="bookit_final_clear"></div>	



<div id="bookit_final_phone_left" class="bookit_final_left"><?php echo JText::_("Phone").$star;?></div>	

<div id="bookit_final_phone_right" class="bookit_final_right">

<input type="text" name="phone" id="phone" class="bookit_final_input" />

</div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_phone_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter your phone number.");?>

</div>

<div id="bookit_final_phone_invalid_right_error" class="bookit_final_error" style="display: none; margin-bottom: 10px">

<?php echo JText::_("Please enter a valid phone number.");?>

</div>

<div id="bookit_final_clear"></div>	



<div class="bookit_final_full_center"></div>



<div id="bookit_final_country_left" class="bookit_final_left"><?php echo JText::_("Country").$star;?></div>	

<div id="bookit_final_country_right" class="bookit_final_right">

<?php echo $lists['country'];?></div>

<div id="bookit_final_clear"></div>	

<div id="bookit_final_country_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please select your country.");?>

</div>

<div id="bookit_final_clear"></div>	



<div id="bookit_final_addr_left" class="bookit_final_left"><?php echo JText::_("Street Address").$star;?></div>	

<div id="bookit_final_addr_right" class="bookit_final_right">

<input type="text" name="addr" id="addr" class="bookit_final_input" /></div>

<div id="bookit_final_addr_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter your address.");?>

</div>

<div id="bookit_final_addr_left" class="bookit_final_left"></div>	

<div id="bookit_final_addr2_right" class="bookit_final_right">

<input type="text" name="addr2" id="addr2" class="bookit_final_input" /></div>

<div id="bookit_final_clear"></div>	



<div id="bookit_final_pcode_left" class="bookit_final_left"><?php echo JText::_("Post Code");?></div>	

<div id="bookit_final_pcode_right" class="bookit_final_right">

<input type="text" name="pcode" id="pcode" class="bookit_final_input" /></div>

<div id="bookit_final_clear"></div>	



<div id="bookit_final_city_left" class="bookit_final_left"><?php echo JText::_("City / Town / Village").$star;?></div>	

<div id="bookit_final_city_right" class="bookit_final_right">

<input type="text" name="city" id="city" class="bookit_final_input" /></div>

<div id="bookit_final_city_right_error" class="bookit_final_error" style="display: none;">

<?php echo JText::_("Please enter the name of your city.");?>

</div>



<div id="bookit_final_pcode_left" class="bookit_final_left"><?php echo JText::_("Province / County");?></div>	

<div id="bookit_final_pcode_right" class="bookit_final_right">

<input type="text" name="county" id="county" class="bookit_final_input" /></div>
<div id="bookit_final_pcode_left" class="bookit_final_left"><?php echo JText::_("Payment Method").$star;?></div>	

<?php //Script to Show/Hide <divs> ?>
<div id="bookit_final_pcode_right" class="bookit_final_right">
	<select onchange="show(this)" class="bookit_final_input" name="pay_method" id="pay_method" disabled="disabled">
    	<option value="UnderDev" selected="selected">NYI - Under Develop</option>
    	<option value="0"><?php echo JText::_("-Select Method-"); ?></option>
    	<option value="1"><?php echo JText::_("Deposit to Bank Account"); ?></option>
		<option value="2"><?php echo JText::_("Credit Card"); ?></option>
	</select>
</div>
<div id="bookit_final_paymethod_right_error" class="bookit_final_error" style="display: none;">
<?php echo JText::_("Please choose a payment method.");?>
</div>

<div id="bookit_final_clear"></div>

<div id="payMethod1" style="display:none">
    <div class="bookit_payment_header"><?php echo JText::_("Deposit to Bank Account")?></div>
    <div class="bookit_payment_txt">
    <?php
    echo "<strong>".JText::_("National Bank of Greece")."</strong><br />";
    echo JText::_("<strong>Name:</strong> Sotiria Rentzika")."<br />";
    echo "<strong>".JText::_("Account").":</strong>"." 23774787719<br />";
    echo "<strong>".JText::_("IBAN").":</strong>"." GR4501102370000023774787719<br />";
    echo "<strong>".JText::_("BIC").":</strong>"." ETHNGRAA<br /><br />";
    
    echo JText::_("(Please note your name and Reference ID from the email you recieved).");
	?>

    </div>
</div>

<div id="bookit_final_clear"></div>

<div id="payMethod2" style="display:none">
<div class="bookit_payment_header"><?php echo JText::_("Credit Card Payment");?></div>
<div id="bookit_final_pcode_left" class="bookit_final_left"><? echo JText::_("Credit Card Number").$star; ?></div>
<div class="bookit_final_pcode_right" id="bookit_final_right"><input type="text" name="ccno" id="ccno" class="bookit_final_input" /></div>
<div id="bookit_final_ccno_right_error" class="bookit_final_error" style="display: none;">
<?php echo JText::_("Please type your card number.");?>
</div>
<div class="bookit_final_pcode_left" class="bookit_final_left"><? echo JText::_("Card Type").$star; ?>
	<select class="bookit_final_input" name="card_type" id="card_type">
		<option value="Visa">Visa</option>
		<option value="Mastercard">Mastercard</option>
	</select>
</div>
<div id="bookit_final_cardtype_right_error" class="bookit_final_error" style="display: none;">
<?php echo JText::_("Please select the type of your card number.");?>
</div>
<div id="bookit_final_pcode_left" class="bookit_final_left"><?php echo JText::_("Expiry Date").$star;?></div>
<div class="bookit_final_pcode_right" id="bookit_final_right">
	<select class="bookit_final_input" name="exp_month" id="exp_month">
		<option value="01">01</option>
		<option value="02">02</option>
		<option value="03">03</option>
		<option value="04">04</option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
</select>
<? echo JText::_("&frasl;"); ?>
	<select class="bookit_final_input" name="exp_year" id="exp_year">
		<option value="2010">2010</option>
		<option value="2011">2011</option>
		<option value="2012">2012</option>
		<option value="2013">2013</option>
		<option value="2014">2014</option>
		<option value="2015">2015</option>
		<option value="2016">2016</option>
		<option value="2017">2017</option>
		<option value="2018">2018</option>
		<option value="2019">2019</option>
		<option value="2020">2020</option>
	</select>
</div>
<div id="bookit_final_expiry_right_error" class="bookit_final_error" style="display: none;">
<?php echo JText::_("Please select the expiry date of your card number.");?>
</div>
</div>


<div id="bookit_final_clear"></div>	

<div id="bookit_final_clear"></div>	

<div id="bookit_final_clear"></div>	

<div class="bookit_final_full_center"></div>



<div id="bookit_ajax_loader" style="display: none;"><?php echo JText::_("Sending request");?></div>

<div id="bookit_final_send_button" class='bookit_button'>





<button type="button" name="send_button" id="send_button" onclick="sendRequest()"><?php echo JText::_("Send Request")?></button>





</div>

<div id="bookit_final_clear"></div>	



<div id="bookit_warning" style="display: none;">

<?php echo JText::_("There was a problem with your request. Please try again later.")?>

</div>

<div id="bookit_succeed" style="display: none;">

<?php echo JText::_("Thank you for your request! A verification email has been sent to the specified address.")?>

</div>

<div id="bookit_final_clear"></div>	

</div>





<input type="hidden" name="layout" value="<?php echo $this->getLayout();?>" />
<input type="hidden" name="option" value="com_bookitgold" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="tmpl" value="" />
</form>

<!--  Payment -->

<div id="bookit_payment_div" style="display: none;">

<div class="bookit_payment_header"><?php echo JText::_("Confirm your booking")?></div>

<div id="bookit_final_clear"></div>	

<div class="bookit_payment_txt"><?php echo JText::_("Your booking will remain pending until you pay the requested amount of"); ?><strong><?php echo " ".$this->deposit." ".$this->currency;?></strong></div>

<div id="bookit_final_clear"></div>	

<?php 
//$paypal_mail = 'renji@thessite.gr'; // Thessite - Bypass the error with joomfish getting the params from the components table. 

if ($paypal_mail!="" && $this->deposit>0 && $this->currency!=""){?>

<div id="bookit_paypal_div" >

<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>

<input type='hidden' name='cmd' value='_xclick'>

<input type='hidden' name='business' value='<?php echo $paypal_mail; ?>'>

<input type='hidden' name='item_name' value='Booking'>

<input type='hidden' name='amount' value='<?php echo $this->deposit; ?>'>

<input type='hidden' name='currency_code' value='<?php echo $this->currency; ?>'>

<input type='hidden' name='button_subtype' value='services'>

<input type='hidden' name='no_note' value='1'>

<input type='hidden' name='no_shipping' value='2'>

<input type='hidden' name='rm' value='1'>

<input type='hidden' name='return' value='<?php echo JURI::base()?>"index.php?option=com_bookitgold&controller=finalform&task=confirmbooking"'>

<input type='hidden' name='cancel_return' value='<?php echo $payment_cancel; ?>'>

<input type='hidden' name='bn' value='PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted'>

<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>

<img alt='' border='0' src='https://www.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>

</form>

</div>

<?php }?>

</div>





</fieldset>



<script language="javascript" type="text/javascript">

function show( obj ){
	no = obj.options[obj.selectedIndex].value;
	count = obj.options.length;

	for(i = 1 ; i <= count ; i++){
		document.getElementById('payMethod'+i).style.display = 'none';
		if(no > 0){
			document.getElementById('payMethod'+no).style.display = 'block';
		}
	}
}


function sendRequest (){



	if (formValidation())

	{

		if (makeBooking())

			return true;

		else

			return false;

	}

	else

	{

		return false;

	}

	return false;

}



function formValidation ()

{
	// PayMethod*
	// Card Check 1* (If Selected)
	// Please select a payment method
	// Thessite
	
	var pay_MethodField = document.getElementById('pay_method');
	var exp_monthField = document.getElementById('exp_month');
	var exp_yearField = document.getElementById('exp_year');
	var card_typeField = document.getElementById('card_type');
	var ccnoField = document.getElementById('ccno');

	var pay_MethodErrorDiv = document.getElementById('bookit_final_paymethod_right_error');
	var expiryErrorDiv = document.getElementById('bookit_final_expiry_right_error');
	var card_typeErrorDiv = document.getElementById('bookit_final_cardtype_right_error');
	var ccnoErrorDiv = document.getElementById('bookit_final_ccno_right_error');
	
	pay_MethodErrorDiv.style.display="none";
	expiryErrorDiv.style.display="none";
	ccnoErrorDiv.style.display="none";
	card_typeErrorDiv.style.display="none";
	

	//First Name*

	//Please enter your first name.

	var nameField = document.getElementById('name');

	var nameErrorDiv = document.getElementById('bookit_final_fname_right_error');

	nameErrorDiv.style.display="none";



	//Last Name*

	//Please enter your last name.

	var surnameField = document.getElementById('surname');

	var surnameErrorDiv = document.getElementById('bookit_final_lname_right_error');

	surnameErrorDiv.style.display="none";

	

	//Email Address*

	//Please enter your email address.

	var emailField = document.getElementById('email');

	var emailErrorDiv = document.getElementById('bookit_final_email_right_error');

	var emailInvalidErrorDiv = document.getElementById('bookit_final_email_invalid_right_error');

	emailErrorDiv.style.display="none";

	emailInvalidErrorDiv.style.display="none";

	

	//Confirm Email Address*

	//Please confirm your email address.

	var cemailField = document.getElementById('cemail');

	var cemailErrorDiv = document.getElementById('bookit_final_cemail_right_error');

	var cemailInvalidErrorDiv = document.getElementById('bookit_final_cemail_invalid_right_error');

	cemailErrorDiv.style.display="none";

	cemailInvalidErrorDiv.style.display="none";

	

	//Phone*

	//Please enter your phone number.

	var phoneField = document.getElementById('phone');

	var phoneErrorDiv = document.getElementById('bookit_final_phone_right_error');

	var phoneInvalidErrorDiv = document.getElementById('bookit_final_phone_invalid_right_error');

	phoneErrorDiv.style.display="none";

	phoneInvalidErrorDiv.style.display="none";

	

	//Country*

	//Please select your country.

	var countryField = document.getElementById('idcountry');

	var countryErrorDiv = document.getElementById('bookit_final_country_right_error');

	countryErrorDiv.style.display="none";

	

	//Street Address*

	//Please enter your address.

	var addrField = document.getElementById('addr');

	var addrErrorDiv = document.getElementById('bookit_final_addr_right_error');

	addrErrorDiv.style.display="none";

	

	//City / Town / Village*

	//Please enter the name of your city.

	var cityField = document.getElementById('city');

	var cityErrorDiv = document.getElementById('bookit_final_city_right_error');

	cityErrorDiv.style.display="none";

	
if( pay_MethodField.value == "" )
	pay_MethodErrorDiv.style.display=="inline";

	else if( pay_MethodField.value == "2" )
		if( ccnoField.value == "" )
			ccnoErrorDiv.style.display=="inline";
		else if( (exp_monthField.value == "") || ( exp_yearField == "" ) )
			expiryrErrorDiv.style.display = "inline";
		else if( card_typeField.value == "" )
			card_typeErrorDiv.style.display == "inline";



	if (nameField.value=="")

		nameErrorDiv.style.display="inline";
		
//	else if (pay_MethodField.value=="0")
//		pay_MethodErrorDiv.style.display=="inline"; //Thessite



	else if (surnameField.value=="")

		surnameErrorDiv.style.display="inline";



	else if (emailField.value=="")

		emailErrorDiv.style.display="inline";



	else if (!isValidEmail())

		emailInvalidErrorDiv.style.display="inline";

	

	else if (cemailField.value=="")

		cemailErrorDiv.style.display="inline";



	else if (cemailField.value!=emailField.value)

	{	cemailInvalidErrorDiv.style.display="inline";

	

	}

	

	else if (phoneField.value=="")

		phoneErrorDiv.style.display="inline";



	else if (!isValidPhoneNumber())

		phoneInvalidErrorDiv.style.display="inline";



	else if (countryField.value=="0")

		countryErrorDiv.style.display="inline";



	else if (addrField.value=="")

		addrErrorDiv.style.display="inline";



	else if (cityField.value=="")

		cityErrorDiv.style.display="inline";



	else

		return true;



	return false;



}



function isValidEmail ()

{

	var address = document.getElementById("email").value;

	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

		if(reg.test(address) == false) 

	{

		return false;

	}

	else

	{

		return true;

	}

	

}





// Declaring required variables

var digits = "0123456789";

var phoneNumberDelimiters = "()- ";

var validWorldPhoneChars = phoneNumberDelimiters + "+";

// Minimum no of digits in an international phone no.

var minDigitsInIPhoneNumber = 6;



function isInteger(s)

{   var i;

    for (i = 0; i < s.length; i++)

    {   

        // Check that current character is number.

        var c = s.charAt(i);

        if (((c < "0") || (c > "9"))) return false;

    }

    // All characters are numbers.

    return true;

}

function trim(s)

{   var i;

    var returnString = "";

    // Search through string's characters one by one.

    // If character is not a whitespace, append to returnString.

    for (i = 0; i < s.length; i++)

    {   

        // Check that current character isn't whitespace.

        var c = s.charAt(i);

        if (c != " ") returnString += c;

    }

    return returnString;

}

function stripCharsInBag(s, bag)

{   var i;

    var returnString = "";

    // Search through string's characters one by one.

    // If character is not in bag, append to returnString.

    for (i = 0; i < s.length; i++)

    {   

        // Check that current character isn't whitespace.

        var c = s.charAt(i);

        if (bag.indexOf(c) == -1) returnString += c;

    }

    return returnString;

}



function checkInternationalPhone(strPhone){

var bracket=3;

strPhone=trim(strPhone);

if(strPhone.indexOf("+")>1) return false;

if(strPhone.indexOf("-")!=-1)bracket=bracket+1;

if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false;

var brchr=strPhone.indexOf("(");

if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false;

if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false;

s=stripCharsInBag(strPhone,validWorldPhoneChars);

return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);

}



function isValidPhoneNumber(){

	var Phone=document.getElementById('phone');

	

	if (checkInternationalPhone(Phone.value)==false){

		return false;

	}

	return true;

 }





function makeBooking (){

	var valid_from_js = "<?php echo $this->valid_from;?>";

	var valid_to_js = "<?php echo $this->valid_to;?>";

	var nguests_js = "<?php echo $this->nguests;?>";

	var nchilds_js = "<?php echo $this->nchilds;?>";
	
	var lchilds_js = "<?php echo $this->lchilds; ?>"; // Thessite

	var idcategory_js = "<?php echo $this->idcategory;?>";

	var price_js = "<?php echo $this->price;?>";

	var deposit_js = "<?php echo $this->deposit;?>";

	var extra_ids_js = "<?php echo $this->extra_ids;?>";

	var preferences_js = "<?php echo $this->preferences;?>";

	var coupon_code_js = "<?php echo $this->coupon_code;?>";

	
	var ccno_js = document.getElementById('ccno').value;
	var exp_month_js = document.getElementById('exp_month').value;
	var exp_year_js = document.getElementById('exp_year').value;
	var card_type_js = document.getElementById('card_type').value;
	var pay_method_js = document.getElementById('pay_method').value;

	var name_js = document.getElementById('name').value;

	var surname_js = document.getElementById('surname').value;

	var email_js = document.getElementById('email').value;

	var phone_js = document.getElementById('phone').value;

	var country_js = document.getElementById('idcountry').value;

	var addr_js = document.getElementById('addr').value;

	var city_js = document.getElementById('city').value+" "+document.getElementById('county').value;

	var addr2_js = document.getElementById('addr2').value;

	var pcode_js = document.getElementById('pcode').value;

	document.getElementById('bookit_ajax_loader').style.display="inline";

	

	var xmlhttp=GetXmlHttpObject();

	

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return false;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

    	//alert(xmlhttp.responseText);

          try //Internet Explorer

          {

            xmlDoc=new ActiveXObject("Microsoft.XMLDOM");

            xmlDoc.async="false";

            xmlDoc.loadXML(xmlhttp.responseText);

        	

          }

        catch(e)

          {

          try //Firefox, Mozilla, Opera, etc.

            {

        	

              parser=new DOMParser();

              xmlDoc=parser.parseFromString(xmlhttp.responseText,"text/xml");

          	

            }

          catch(e) {alert(e.message);}

          }

    	



        var xmlQRes = xmlDoc.getElementsByTagName('results')[0];

      	var idbook_res = xmlQRes.getAttributeNode('idbook').value;

      	var mail_res = xmlQRes.getAttributeNode('mail').value;



		document.getElementById('bookit_final_send_button').style.display="none";

		document.getElementById('bookit_ajax_loader').style.display="none";

	

	    if (mail_res>0 && idbook_res>0)

	    {

	    	document.getElementById('bookit_succeed').style.display="inline";



			if (document.getElementById('bookit_paypal_div')!=null)

			{

		    	//Set the Paypal button properties

		    	paypdiv = document.getElementById('bookit_paypal_div');

				for (var i = 0; i < paypdiv.getElementsByTagName("input").length; i++)

				{

					if (paypdiv.getElementsByTagName("input")[i].name=="return")

					{

						paypdiv.getElementsByTagName("input")[i].value += "&idbook="+idbook_res;

					}

				}

				document.getElementById('bookit_payment_div').style.display="inline";

			}

			

	    	

	    }

	    else

	    {

	    	document.getElementById('bookit_warning').style.display="inline";

	    	

	    }

      

      }

    };



	

		var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=finalform&task=makebooking&format=raw";

		url += "&idcategory="+idcategory_js+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js+"&nguests="+nguests_js;

		url += "&lchilds="+lchilds_js+"&nchilds="+nchilds_js+"&price="+price_js+"&deposit="+deposit_js+"&extra_ids="+extra_ids_js+"&name="+name_js+"&coupon_code="+coupon_code_js; // Thessite

		url += "&surname="+surname_js+"&email="+email_js+"&phone="+phone_js+"&idcountry="+country_js+"&addr="+addr_js+"&city="+city_js;

		url += "&addr2="+addr2_js+"&pcode="+pcode_js+"&preferences="+preferences_js;





		xmlhttp.open("GET",url,true);	

		xmlhttp.send(null);

		

		return false;



}



	



	function GetXmlHttpObject()

	{

		if (window.XMLHttpRequest)

	  	{

	  // code for IE7+, Firefox, Chrome, Opera, Safari

	  return new XMLHttpRequest();

	  }

	if (window.ActiveXObject)

	  {

	  // code for IE6, IE5

	  return new ActiveXObject("Microsoft.XMLHTTP");

	  }

	return null;

	}

</script>

