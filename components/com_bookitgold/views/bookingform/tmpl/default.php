<?php

/**

 * BookITGold - Joomla Booking Management Component

 *

 *

 * @version 4.0

 * @author Costas Kakousis (info@istomania.com)

 * @copyright (C) 2009-2010 by Costas Kakousis (http://www.istomania.com)

 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html

 *

 * If you fork this to create your own project,

 * please make a reference to BookIT someplace in your code

 * and provide a link to http://www.istomania.com

 *

 * This file is part of BookIT.

 * BookIT is free software: you can redistribute it and/or modify

 * it under the terms of the GNU General Public License as published by

 * the Free Software Foundation, either version 3 of the License, or

 * (at your option) any later version.

 *

 *  BookITGold is distributed in the hope that it will be useful,

 *  but WITHOUT ANY WARRANTY; without even the implied warranty of

 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

 *  GNU General Public License for more details.

 *

 *  You should have received a copy of the GNU General Public License

 *  along with BookITGold.  If not, see <http://www.gnu.org/licenses/>.

 *

 **/

defined('_JEXEC') or die('Restricted access');

$stylesheet = 'booking_form.css';



$path = 'components/com_bookitgold/css/';

JHTML::stylesheet($stylesheet, $path);





$db =& JFactory::getDBO();

$params =& JComponentHelper::getParams('com_bookitgold');

$show_coupons = $params->get('show_coupons','1');

$dateformatcode = $params->get('dateformat');

//$cancellation_policy = $params->get('cancellation_policy');

//$terms_conditions = $params->get('terms_conditions');

//$deposit_fixed = floatval ($params->get('deposit_fixed'));

//$deposit_percent = floatval ($params->get('deposit_percent'));


/* Start of Thessite code
** Bypass the bug from Joomfish not getting the ToC / CP from
** the table components, also deposit percentages
*/

if ($dateformatcode==""||$dateformatcode==3){
	$d1='d-m-Y';
	$d2='%d-%m-%Y';
}
else if ($dateformatcode==1)
{
	$d1='Y-m-d';
	$d2='%Y-%m-%d';
}
else if ($dateformatcode==2)
{
	$d1='m/d/Y';
	$d2='%m/%d/%Y';
}

// Deposit Percentage
$deposit_percent = floatval(30);
$deposit_fixed = floatval(0);

// ToC
$booking_pay_date = date( $d1, strtotime("+2 day") );
$terms_conditions = JText::_('For your booking confirmation you have to deposit 30% of the total price of your reservation until');
$terms_conditions .= " ".$booking_pay_date.".";

// Cancellation Policy
$dateformatcode = $params->get('dateformat');

$afiksi = $this->valid_from;
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

/*if ($dateformatcode==""||$dateformatcode==3)

{

	$d1='d-m-Y';

	$d2='%d-%m-%Y';

}

else if ($dateformatcode==1)

{

	$d1='Y-m-d';

	$d2='%Y-%m-%d';

}

else if ($dateformatcode==2)

{

	$d1='m/d/Y';

	$d2='%m/%d/%Y';

}
*/




if ($this->nnights>1)

$night=JText::_("Nights");

else

$night=JText::_("Night");





$sql = 'SELECT * FROM #__bookitextra ORDER BY extra_type DESC';

$db->setQuery($sql);

$extra_array = $db->loadObjectList();



$sql = 'SELECT * FROM #__bookitextra WHERE value_fix=\'0\' AND value_percent=\'0\' ORDER BY name';

$db->setQuery($sql);

$pref_array = $db->loadObjectList();





$total_price=0;



function getTaxPrice($extraRow, $priceReg, $nights, $adults, $childs, $lchilds /*Thessite*/){
	
	$childs = $childs + $lchilds; // Thessite



	if ($extraRow->value_type==1)//Per booking

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg;

		}



	}

	else if ($extraRow->value_type==2)//Per night

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$nights;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$nights;

		}

	}				

	else if ($extraRow->value_type==3)//Per person

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*($adults+$childs);

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*($adults+$childs);

		}

	}

	else if ($extraRow->value_type==4)//Per adult

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$adults;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$adults;

		}

	}

	else if ($extraRow->value_type==5)//Per child

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$childs;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$childs;

		}

	}

	else if ($extraRow->value_type==6)//Per adult/night

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$adults*$nights;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$adults*$nights;

		}

	}

	else if ($extraRow->value_type==7)//Per child/night

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$childs*$nights;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$childs*$nights;

		}

	}

	else if ($extraRow->value_type==8)//Per quantity

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg;

		}

	}

	else if ($extraRow->value_type==9)//Per quantity/night

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*$nights;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*$nights;

		}

	}

	else if ($extraRow->value_type==10)//Per guest/night

	{

		if ($extraRow->value_fix>0)

		{

			return $extraRow->value_fix*($adults+$childs)*$nights;

		}

		else if ($extraRow->value_percent>0)

		{

			return $extraRow->value_percent/100*$priceReg*($adults+$childs)*$nights;

		}

	}



}



$menus = &JSite::getMenu();

$menu  = $menus->getActive();

$itemid = $menu->id;

$l_childs = $this->lchilds;
$n_childs = $this->nchilds;
$n_guests = $this->nguests;
$extra_ppl = 0;
$new_price = 0;
$total_guests = $l_childs + $n_childs + $n_guests;

//$sql_q = 'SELECT * FROM #__bookitcategory';
//$category_array = $db->loadObjectList();

//$db->setQuery($sql_q);

?>



<form action="<?php echo JRoute::_( 'index.php?option=com_bookitgold&controller=finalform&task=finaldefault' );?>" name="bookingForm" method="post">

<fieldset class="bookit_booking_fieldset"><legend class="bookit_booking_legent"><?php echo JText::_("Itinerary Review")?></legend>

<div class="bookit_booking_review_left">

<div class="bookit_booking_left_td"><?php echo JText::_("Room type");?></div>

<div class="bookit_booking_left_td"><?php echo JText::_("Date of Arrival");?></div>

<div class="bookit_booking_left_td"><?php echo JText::_("Date of Departure");?></div>

<div class="bookit_booking_left_td"><?php echo JText::_("Stay for");?></div>

<div class="bookit_booking_left_td"><?php echo JText::_("Adults");?></div>


<?php if( $this->nchilds > 0 ){ ?>
	<div class="bookit_booking_left_td"><?php echo JText::_("Children, 0 to 5 years old");?></div>
<?php } ?>
<?php if( $this->lchilds > 0 ){ ?>
	<div class="bookit_booking_left_td"><?php echo JText::_("Childern, 6 to 12 years old"); // Thessite?></div>
<?php } ?>
<div class="bookit_booking_left_td"><?php echo JText::_("Price");?></div>
<?php if( $total_guests > 4 ) { ?>
	<div class="bookit_booking_left_td"><?php echo JText::_("Price for the extra person");?></div>
<? } ?>
 
</div>

<div class="bookit_booking_review_right">

<div class="bookit_booking_right_td"><?php echo $this->category_name;?></div>

<div class="bookit_booking_right_td"><?php echo $this->valid_from;?></div>

<div class="bookit_booking_right_td"><?php echo $this->valid_to;?></div>

<div class="bookit_booking_right_td"><?php echo $this->nnights." ".$night;?></div>

<div class="bookit_booking_right_td"><?php echo $this->nguests;?></div>


<?php if( $this->nchilds > 0 ){ ?>
	<div class="bookit_booking_right_td"><?php echo $this->nchilds;?></div>
<?php } ?>
<?php if( $this->lchilds > 0 ){ ?>
	<div class="bookit_booking_right_td"><?php echo $this->lchilds; // Thessite?></div>
<?php } ?>


<div class="bookit_booking_right_td"><?php echo $this->price." ".$this->currency;?></div>
<?php

// Thessite - Calculate the price for the extra persons and show it to the guest.
//if( $total_guests > 4 ) { //
//	$extra_ppl = $total_guests - 4;
//	$new_price = 6 * ( $this->nnights * $extra_ppl );
$typos_dwmatiou = $this->category_name;
switch( $typos_dwmatiou ) {
	case "Studio": $capacity = 2;
	case "Apartment": $capacity = 4;
	case "Bungalow": $capacity = 2;
	case "Guest Room": $capanity = 2;
}
if( $total_guests > $capacity ) {
	$extra_ppl = $total_guests - $capacity;
	if( $capacity < 0 ){
		$new_extra_ppl = abs($extra_ppl); // Elegxei an einai arnitikos ari8mos kai ton antistrefei se 8etiko [ -2 se 2 ]
	}
	$new_price = 6 * ($this->nnights * $new_extra_ppl);

?>
	<div class="bookit_booking_right_td"><?php echo JText::_($new_price." ".$this->currency." (6 ".$this->currency." / Night)");?></div>
<?php } ?>


</div>



<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_header"><?php echo JText::_("Booking Preferences");?>

<br />

<?php

for ($i=0; $i<count($pref_array); $i++)

{

	$box_id="prefbox_".$pref_array[$i]->idextra;

	$qnt_box_id="qntprefbox_".$pref_array[$i]->idextra;

	$qnt_div="";

	if ($pref_array[$i]->value_type==8 || $pref_array[$i]->value_type==9) //Quantity

	{

		$js1='onClick="return (setQuantityOnly(\'plus\', \''.$qnt_box_id.'\'));"';

		$js2='onClick="return (setQuantityOnly(\'minus\', \''.$qnt_box_id.'\'));"';

		$plus_but="<button class='button' ".$js1.">+</button>";

		$minus_but="<button class='button' ".$js2.">-</button>";

		$qnt_div="<div class='bookit_booking_pref_qnt'> <input type='text' size='1' maxlength='3' value='1' readonly='readonly' id='".$qnt_box_id."'/>".

		$plus_but.$minus_but."</div>";		

	}

	echo "<div class='bookit_booking_pref_left'> <input type='checkbox' name='extra_box' id='".$box_id."'>".$pref_array[$i]->name.$qnt_div."</div>";

	echo "<div class='bookit_booking_pref_left_desc'>".$extra_array[$i]->description."</div>";

}

?>



</div>



<div class="bookit_booking_clear"></div>

<div id="bookit_booking_pref_requests">

<?php echo JText::_('Guest Comments / Special Requests'); ?><br />

<textarea rows="4" cols="32" name="preferences" id="preferences"></textarea>

</div>



<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_header"><?php echo JText::_("Extra Services");//echo JText::_("Taxes / Extra Services");?>
<br />

<div id="bookit_booking_pref_requests">
<?php
echo JText::_("In an attempt to make your stay as comfortable as possible, a list of extra services is given below for you to choose the one(s) that meet your needs and expectations");
?>
</div>

<br />



<?php

$extraRes=0;

$total_price=$this->price;

$box_id="";

for ($i=0; $i<count($extra_array); $i++)

{

	$qnt_div="";

	$qnt_box_id="qntbox_".$extra_array[$i]->idextra;

	$box_id="extrabox_".$extra_array[$i]->idextra;

	$extraRes=getTaxPrice($extra_array[$i],$this->pricereg,$this->nnights,$this->nguests,$this->nchilds, $this->lchilds /* Thessite */);

	if ($extraRes>0)

	{

		if ($extra_array[$i]->extra_type==1)//Required

		{

			echo "<div class='bookit_booking_taxes_left'> <input type='checkbox' name='extra_box' id='".$box_id."' disabled='disabled' checked>".$extra_array[$i]->name."

			</div>";

			echo "<div class='bookit_booking_taxes_right'>".$extraRes." ".$this->currency."</div>";

			echo "<div class='bookit_booking_taxes_left_desc'>".$extra_array[$i]->description."</div>";

			$total_price+=$extraRes;

		}

		elseif ($extra_array[$i]->extra_type==0)//Optional

		{

						

			$js1='onClick="return (setQuantity('.$extraRes.',\'plus\', \''.$qnt_box_id.'\', \''.$this->currency.'\'));"';

			$js2='onClick="return (setQuantity('.$extraRes.',\'minus\', \''.$qnt_box_id.'\', \''.$this->currency.'\'));"';

			$plus_but="<button class='button' ".$js1.">+</button>";

			$minus_but="<button class='button' ".$js2.">-</button>";

			

			if ($extra_array[$i]->value_type==8 || $extra_array[$i]->value_type==9) //Quantity

			{

				$qnt_div="<div class='bookit_booking_taxes_qnt'> <input type='text' size='1' maxlength='3' value='1' readonly='readonly' id='".$qnt_box_id."'/>".

				$plus_but.$minus_but."</div>";

			

			}



			$js='onClick="(editTotal('.$extraRes.',\''.$box_id.'\',\''.$this->currency.'\'));"';

			echo "<div class='bookit_booking_taxes_left'> <input type='checkbox' name='extra_box' id='".$box_id."'".$js.">".$extra_array[$i]->name.$qnt_div."</div>";

			echo "<div class='bookit_booking_taxes_right'>".$extraRes." ".$this->currency."</div>";

			echo "<div class='bookit_booking_taxes_left_desc'>".$extra_array[$i]->description."</div>";

		}

	}

	$extraRes=0;

}

?></div>

<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_left"></div>

<?php if ($show_coupons==1){?>

<div class="bookit_booking_review_header"><?php echo JText::_("Promotional Code");?></div>

<div class='bookit_booking_taxes_left'><input type="text" name="code" id="coupon_code" />

<button class="button" id="coupon_button"<?php $js='onClick=" return (couponValidation(\''.$this->valid_from.'\',\''.$this->valid_to.'\','.$this->idcategory.',\''.$this->currency.'\'));"'; echo $js;?>><?php echo JText::_('Apply'); ?></button>



</div>

<div id='bookit_booking_code_msg'></div>

<div class="bookit_booking_clear"></div>

<?php }?>



<div class="bookit_booking_review_right">

<div id="bookit_booking_review_final_price_header"><?php echo JText::_("Total Cost");?>

<?php

/* Start of Thessite Code
** Checks if Guests are more than 4.
** If so, add +6 euros for each person and each night
** at the total_price
*/
if( $total_guests > 4 ) {
	
	$extra_ppl = $total_guests - 4;
	$new_price = 6 * ( $this->nnights * $extra_ppl );
	$total_price = $total_price + $new_price;
	
}

/*
** End of Thessite Code
*/
?>

<div id="bookit_booking_total" class='bookit_booking_final_price'><?php echo $total_price." ".$this->currency;?></div>

</div>

</div>

<?php if ($deposit_percent>0 || $deposit_fixed>0) {?>

<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_right">

<div id="bookit_booking_review_final_price_header"><?php echo JText::_("Deposit");?>

<div id="bookit_booking_deposit" class='bookit_booking_final_price'><?php 

if ($deposit_percent>0)

	echo $total_price*$deposit_percent/100 ." ".$this->currency;

else if ($deposit_fixed>0)

	echo $deposit_fixed ." ".$this->currency;

?></div>

</div>

</div>

<?php }?>





<div class="bookit_booking_clear"></div>

<?php 

if ($terms_conditions!="" && $cancellation_policy!="")

{

	$accept_txt = JText::_('I agree to the following terms & conditions and cancellation policy.');

	$accept_error_txt = JText::_('Please indicate that you agree to the terms & conditions and cancellation policy.');

}

else if ($terms_conditions=="" && $cancellation_policy!="")

{

	$accept_txt = JText::_("I agree to the following cancellation policy.");

	$accept_error_txt = JText::_('Please indicate that you agree to the cancellation policy.');

}

else if ($terms_conditions!="" && $cancellation_policy=="")

{

	$accept_txt = JText::_('I agree to the following terms & conditions.');

	$accept_error_txt = JText::_('Please indicate that you agree to the terms & conditions.');

}

else

	$accept_txt="";

?>

<div id="bookit_booking_continue">

<?php if ($accept_txt!=""){ ?>

<div id="bookit_booking_accept"><input type='checkbox' id='accept_checkbox'><?php echo $accept_txt; ?></div> 

<div id="bookit_booking_accept_button" class='bookit_button'>

<button type="button" name="continue_button" id="continue_button"

onclick="checkTerms()"><?php echo JText::_("Continue")?></button>



</div>

<div class="bookit_booking_clear"></div>

<div id="accept_error" style="display: none">

<?php echo $accept_error_txt;?>

</div>

<?php }

else

{?><div id="bookit_booking_accept_button" class='bookit_button'>

<button type="button" name="continue_button" id="continue_button"

onclick="checkTerms()"><?php echo JText::_("Continue")?></button>



</div>

<div class="bookit_booking_clear"></div><?php }?>

</div>


<?php if ($terms_conditions!="") {?>

<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_left">

<div class="bookit_booking_review_header_terms"><?php echo JText::_("Terms & Conditions");?></div>

<div id="bookit_booking_terms_conditions"><?php echo $terms_conditions;?></div>

</div>

<?php }?>



<?php if ($cancellation_policy!="") {?>

<div class="bookit_booking_clear"></div>

<div class="bookit_booking_review_left">

<div class="bookit_booking_review_header_terms"><?php echo JText::_("Cancellation Policy");?></div>

<div id="bookit_booking_cancellation_policy"><?php echo $cancellation_policy;?></div>

<div class="bookit_booking_clear"></div>



</div>

<?php }?>



</fieldset>





<input type="hidden" name="layout" value="finalform" />
<input type="hidden" name="option" value="com_bookitgold" /> 

	<input type="hidden" name="valid_from" id="valid_from" value="<?php echo $this->valid_from;?>"></input>

	<input type="hidden" name="valid_to" id="valid_to" value="<?php echo $this->valid_to;?>"></input>

	<input type="hidden" name="nguests" id="nguests" value="<?php echo $this->nguests;?>"></input>

	<input type="hidden" name="nchilds" id="nchilds" value="<?php echo $this->nchilds;?>"></input>
   	<input type="hidden" name="lchilds" id="lchilds" value="<?php echo $this->lchilds; //Thessite ?>"></input>

	<input type="hidden" name="idcategory" id="idcategory" value="<?php echo $this->idcategory;?>"></input>

	<input type="hidden" name="coupon_code_valid" id="coupon_code_valid"></input>

	<input type="hidden" name="price" id="price"></input>

	<input type="hidden" name="deposit" id="deposit"></input>

	<input type="hidden" name="extra_ids" id="extra_ids"></input>

	

	

	

	

	</form>



<script language="javascript" type="text/javascript">



function checkTerms () {



	var price_js = parseFloat(document.getElementById('bookit_booking_total').innerHTML);

	if (document.getElementById('bookit_booking_deposit')!=null)

		var deposit_js = parseFloat(document.getElementById('bookit_booking_deposit').innerHTML);

	else

		var deposit_js = 0;

	document.getElementById('price').value=price_js;

	document.getElementById('deposit').value=deposit_js;

	

	

	//Create the extra_ids chain

	var elements = document.bookingForm.elements;

	var extra_ids_js="";

	for (i = 0; i < elements.length; i++){

		if (elements[i].type=="checkbox" && elements[i].name=="extra_box")

		{

			if (elements[i].checked)

			{

				

				if (extra_ids_js=="")

				{

					if (document.getElementById("qntbox_"+elements[i].id.split("_")[1])!=null)	

					{

						

						extra_ids_js += ""+elements[i].id.split("_")[1]+"-"+document.getElementById("qntbox_"+elements[i].id.split("_")[1]).value;

					}

					else if (document.getElementById("qntprefbox_"+elements[i].id.split("_")[1])!=null)

					{

						

							extra_ids_js += ""+elements[i].id.split("_")[1]+"-"+document.getElementById("qntprefbox_"+elements[i].id.split("_")[1]).value;

					}

					else

						extra_ids_js += ""+elements[i].id.split("_")[1];

				}

				else

				{

					if (document.getElementById("qntbox_"+elements[i].id.split("_")[1])!=null)	

						extra_ids_js += ","+elements[i].id.split("_")[1]+"-"+document.getElementById("qntbox_"+elements[i].id.split("_")[1]).value;

					else if (document.getElementById("qntprefbox_"+elements[i].id.split("_")[1])!=null)

						extra_ids_js += ""+elements[i].id.split("_")[1]+"-"+document.getElementById("qntprefbox_"+elements[i].id.split("_")[1]).value;

					else

						extra_ids_js += ","+elements[i].id.split("_")[1];

				}

			}

			

		}

	}



	if (document.getElementById('accept_checkbox')!=null)

	{

		document.getElementById('accept_error').style.display="none";

		if (!document.getElementById('accept_checkbox').checked)

		{

			document.getElementById('accept_error').style.display="inline";

			return false;

		}

	}

	document.getElementById('extra_ids').value=extra_ids_js;



	document.bookingForm.submit();

	

	//var button = document.getElementById('continue_button');

	//<![CDATA[

	//button.href="index.php?option=com_bookitgold&controller=finalform&task=finaldefault&view=finalform"+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js+"&nguests="+nguests_js+"&nchilds="+nchilds_js+"&idcategory="+idcategory_js+"&coupon_code="+coupon_code_js+"&price="+price_js+"&deposit="+deposit_js+"&extra_ids="+extra_ids_js;

	//]]>

	return true;	



}



function setQuantityOnly (mode,qnt_box_id ){



	var curVal=parseInt(document.getElementById(qnt_box_id.toString()).value);

	if (mode=="plus")

	{

		document.getElementById(qnt_box_id.toString()).value= curVal+1;

	}

	else if (mode=="minus")

	{

		if (curVal>1)

		{

			document.getElementById(qnt_box_id.toString()).value= curVal-1;

			

		}

		

	}

	return false;

}

function setQuantity (extra, mode, qnt_box_id, curr){



	var curVal=parseInt(document.getElementById(qnt_box_id.toString()).value);

	var depo = "<?php echo $deposit_percent;?>";

	if (depo!="")

		depo=parseFloat(depo);

	

	var box_id = "extrabox_"+qnt_box_id.split("_")[1];

	var box_element = document.getElementById(box_id.toString());

	var total=document.getElementById('bookit_booking_total').innerHTML;

	total=parseFloat(total);

	

	if (mode=="plus")

	{

		document.getElementById(qnt_box_id.toString()).value= curVal+1;

		if (box_element.checked)

		{

			newTotal=Math.round((total+extra)*Math.pow(10,2))/Math.pow(10,2);

			document.getElementById('bookit_booking_total').innerHTML=newTotal+" "+curr;

			if (depo>0 && document.getElementById('bookit_booking_deposit')!=null)

			{

				newDeposit=Math.round((newTotal*depo/100)*Math.pow(10,2))/Math.pow(10,2);

				document.getElementById('bookit_booking_deposit').innerHTML=newDeposit+" "+curr;

			}

		}

		

	}

	else if (mode=="minus")

	{

		if (curVal>1)

		{

			document.getElementById(qnt_box_id.toString()).value= curVal-1;

			if (box_element.checked)

			{

				newTotal=Math.round((total-extra)*Math.pow(10,2))/Math.pow(10,2);

				document.getElementById('bookit_booking_total').innerHTML=newTotal+" "+curr;

				if (depo>0 && document.getElementById('bookit_booking_deposit')!=null)

				{

					newDeposit=Math.round((newTotal*depo/100)*Math.pow(10,2))/Math.pow(10,2);

					document.getElementById('bookit_booking_deposit').innerHTML=newDeposit+" "+curr;

				}

					

			}

		}

		

	}

	return false;

}



function editTotal (extra, box_id, curr)

{

	var qnt_box_id = "qntbox_"+box_id.split("_")[1];

	var depo = "<?php echo $deposit_percent; ?>";

	

	var total=document.getElementById('bookit_booking_total').innerHTML;

	total=parseFloat(total);

	var newToral;

	var box_element = document.getElementById(box_id.toString());



	if (!isNaN(total))

	{

		if (box_element.checked)

		{

			if (document.getElementById(qnt_box_id)!=null)

				newTotal=Math.round((total+extra*document.getElementById(qnt_box_id).value)*Math.pow(10,2))/Math.pow(10,2);

			else

				newTotal=Math.round((total+extra)*Math.pow(10,2))/Math.pow(10,2);

			document.getElementById('bookit_booking_total').innerHTML=newTotal+" "+curr;

			if (depo>0 && document.getElementById('bookit_booking_deposit')!=null)

			{

				newDeposit=Math.round((newTotal*depo/100)*Math.pow(10,2))/Math.pow(10,2);

				document.getElementById('bookit_booking_deposit').innerHTML=newDeposit+" "+curr;

			}

		}

		else

		{

			if (document.getElementById(qnt_box_id)!=null)

				newTotal=Math.round((total-(extra*document.getElementById(qnt_box_id).value))*Math.pow(10,2))/Math.pow(10,2);

			else

				newTotal=Math.round((total-extra)*Math.pow(10,2))/Math.pow(10,2);

			document.getElementById('bookit_booking_total').innerHTML=newTotal+" "+curr;

			if (depo>0 && document.getElementById('bookit_booking_deposit')!=null)

			{

				newDeposit=Math.round((newTotal*depo/100)*Math.pow(10,2))/Math.pow(10,2);

				document.getElementById('bookit_booking_deposit').innerHTML=newDeposit+" "+curr;

			}

		}	

	}

}



function couponValidation(valid_from_js, valid_to_js, idcategory_js, curr){

	var xmlhttp=GetXmlHttpObject();

	var invalid_msg="<?php echo JText::_("invalid code");?>";

	var valid_msg="<?php echo JText::_("valid code");?>";

	var code_js = document.getElementById('coupon_code').value;



	

	if (code_js.length!=13)

	{

		document.getElementById('bookit_booking_code_msg').innerHTML = '<div id="bookit_invalid_code">'+invalid_msg+'</div>';

		return false;

	}

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return false;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

        

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





        var xmlQRes = xmlDoc.getElementsByTagName('coupons')[0];

      	var discount = xmlQRes.getAttributeNode('new_price').value;

      

		if (discount > 0)

		{



			

			var total=document.getElementById('bookit_booking_total').innerHTML;

			total=parseFloat(total);



			var newPrice = parseFloat (total - discount);

			document.getElementById('bookit_booking_total').innerHTML=newPrice+" "+curr;

	    	document.getElementById('bookit_booking_code_msg').innerHTML = '<div id="bookit_valid_code">'+valid_msg+'</div>';

	    	document.getElementById('coupon_code').disabled=true;

	    	document.getElementById('coupon_button').disabled=true;



	    	document.getElementById('coupon_code_valid').value= code_js;

	    	

			return false;

		}

		else

		{



	    	document.getElementById('bookit_booking_code_msg').innerHTML = '<div id="bookit_invalid_code">'+invalid_msg+'</div>';

	    	

			return false;

		}

      }

    };





		var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=bookingform&task=couponvalidation&format=raw&code="+code_js+"&idcategory="+idcategory_js+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js;

		

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

