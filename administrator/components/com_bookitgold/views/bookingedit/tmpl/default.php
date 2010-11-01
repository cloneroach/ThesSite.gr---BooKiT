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

jimport('joomla.html.pagination');



$doc =& JFactory::getDocument();

//$doc->addScript( JURI::root()."components/com_bookitgold/js/calendar.js");



// define style sheet location

$stylesheet = 'calendar_style.css';

$path = 'components/com_bookitgold/css/';

// add style sheet

JHTML::stylesheet($stylesheet, $path);





$lists=array();

$db =& JFactory::getDBO();

if ($this->booking->idcoupon==null)

$sql = "SELECT idcoupon, name FROM #__bookitcoupon WHERE usable=1 AND used=0";

else

$sql = "SELECT idcoupon, name FROM #__bookitcoupon WHERE (usable=1 AND used=0) OR (idcoupon=".$this->booking->idcoupon.")";

$db->setQuery($sql);



$coup_arr = $db->loadObjectList();

$value = isset($this->booking->idcoupon)?$this->booking->idcoupon:'0';

$results[] = JHTML::_('select.option', 0, '-Select Coupon-', 'idcoupon', 'name' );

if (is_array($coup_arr))

$results = array_merge( $results, $db->loadObjectList() );

//$js = 'onChange="(ajaxUpdateRooms());"';

$lists['coupon']  = JHTML::_('select.genericList', $results, 'idcoupon', 'class="inputbox" size="1"', 'idcoupon', 'name', $value);


/**
* Thessite
* Ka8orismos e-mail pelati gia to admin panel -> edit booking
* Emfanizei to e-mail, na ginei na emfanizei -- Onoma & Epi8eto -- ?
**/
$sql = "SELECT idguests, email, name, surname FROM #__bookitguests";

$db->setQuery($sql);

$value2 = isset($this->booking->idguests)?$this->booking->idguests:'0';

$results2[] = JHTML::_('select.option', 0, '-Select Guest-', 'idguests', 'email' );

$results2 = array_merge( $results2, $db->loadObjectList() );

$lists['guests']  = JHTML::_('select.genericList', $results2, 'idguests', 'class="inputbox" size="1"', 'idguests', 'email', $value2);



$sql = 'SELECT * FROM #__bookitextra';

$db->setQuery($sql);

$extra_array = $db->loadObjectList();



$sql = 'SELECT extra_ids FROM #__bookitbooking WHERE idbook='.$this->booking->idbook;

$db->setQuery($sql);

$booking_extras= $db->loadResult();

if ($booking_extras!="")

$extra_selections = explode(",", $booking_extras);



else 

{

$extra_selections = array();



}







$sql = "SELECT idcategory, name FROM #__bookitcategory";

$db->setQuery($sql);

$value3 = isset($this->booking->idcategory)?$this->booking->idcategory:'0';

$results3[] = JHTML::_('select.option', 0, '-Select Category-', 'idcategory', 'name' );

$results3 = array_merge( $results3, $db->loadObjectList() );

$js = 'onChange="(ajaxUpdateRooms());"';

$lists['catid']  = JHTML::_('select.genericList', $results3, 'idcategory', 'class="inputbox" size="1"'.$js, 'idcategory', 'name', $value3);



$sql = "SELECT idroom, name FROM #__bookitroom";

$db->setQuery($sql);

$value4 = isset($this->booking->idroom)?$this->booking->idroom:'0';

$results4[] = JHTML::_('select.option', 0, '-Let the System Decide-', 'idroom', 'name' );

$results4 = array_merge( $results4, $db->loadObjectList() );

$lists['roomid']  = JHTML::_('select.genericList', $results4, 'idroom', 'class="inputbox" size="1"', 'idroom', 'name', $value4);





$params =& JComponentHelper::getParams('com_bookitgold');

$dateformatcode = $params->get('dateformat');

if ($dateformatcode==""||$dateformatcode==3)

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



?>



<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

<fieldset class="adminform"><legend><?php echo JText::_( 'Booking Basics' ); ?></legend>

<table class="admintable">

	<tr>

		<td width="100" align="right" class="key"><label for="period"> <?php echo JText::_( 'Period' ); ?></label></td>

		<td><?php echo JText::_('Check-in'); ?> <?php 

		$js2 = 'onChange="(updateToField(\''.$d1.'\'));"';

		if($this->booking->valid_from!="") //Edit

		{



			echo JHTML::_('calendar',date($d1, strtotime($this->booking->valid_from)),'valid_from','valid_from',$d2,'size="10"'.$js2);

		}

		else

		echo JHTML::_('calendar',date($d1),'valid_from','valid_from',$d2,'size="10"'.$js2);

		?> <?php echo JText::_('Check-out');?> <?php if($this->booking->valid_to!="")

		{

			echo JHTML::_('calendar',date($d1, strtotime($this->booking->valid_to)),'valid_to','valid_to',$d2,'size="10"');

		}

		else  echo JHTML::_('calendar',date($d1, strtotime("+1 day")),'valid_to','valid_to',$d2,'size="10"');?>

		</td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idcategory"> <?php echo JText::_( 'Room Category' ); ?></label></td>

		<td><?php 

		echo $lists['catid'];

		?></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idroom"> <?php echo JText::_( 'Room' ); ?></label></td>

		<td><?php 

		echo $lists['roomid'];

		?></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idguests"> <?php echo JText::_( 'Guest' ); ?></label></td>

		<td><?php 

		echo $lists['guests'];

		?></td>

	</tr>



	<tr>

		<td width="100" align="right" class="key"><label for="nguests"> <?php echo JText::_( 'Adults no.' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="nguests" id="nguests"

			size="10" maxlength="25"

			value="<?php echo $this->booking->nguests;?>" /></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="nchilds"> <?php echo JText::_( 'Children no. (0-5)' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="nchilds" id="nchilds"

			size="10" maxlength="25"

			value="<?php echo $this->booking->nchilds;?>" /></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="lchilds"> <?php echo JText::_( 'Children no. (6-12)' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="lchilds" id="lchilds"

			size="10" maxlength="25"

			value="<?php echo $this->booking->lchilds;?>" /></td>

	</tr>

    
<!--
-- Thessite
-- Apo panw, emfanizei posa paidia (0-5) 8a minoun
-- Na balw na emfanizonte posa paidia ($lchilds // 6-12) 8a minoun
//-->

	<tr>

		<td width="30" align="right" class="key"><label for="preferences"> <?php echo JText::_( 'Requests' ); ?>:

		</label></td>

		<td><textarea class="inputbox" cols="40" rows="4" name="preferences"

			id="preferences"><?php echo $this->booking->preferences;?></textarea></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="status"> <?php echo JText::_( 'Status' ); ?>:

		</label></td>

		<td>

		<input type="radio" name="status" value="2"  <?php if ($this->booking->status==2 || $this->booking->status=="") {?> checked="checked" <?php }?>

		<?php echo 'class="required"';  ?> /> <?php echo JText::_('Pending');?>

		

		<input type="radio" name="status" value="1" <?php if ($this->booking->status==1) {?> checked="checked" <?php }?>

		<?php echo 'class="required"';  ?> /> <?php echo JText::_('Confirmed');?>



		</td>

	</tr>



	<tr>

		<td width="100" align="right" class="key"><label for="idcoupon"> <?php echo JText::_( 'Coupon' ); ?></label></td>

		<td><?php 

		echo $lists['coupon'];

		?></td>

	</tr>

	<tr>

		<td height="40" width="100" align="right" class="key"><label

			for="extra"> <?php echo JText::_( 'Extra services' ); ?></label></td>

		<td><?php

		$initial_ids="";

		$new_selection=array();

		for ($i=0; $i<count($extra_selections); $i++)

		{

			$pos=strpos($extra_selections[$i],"-");

			if ($pos>0)

			{

				$new_selection[$i] = substr($extra_selections[$i],0,$pos);

				$quantities[substr($extra_selections[$i],0,$pos)] = substr($extra_selections[$i],$pos+1);

			}

			else

			{

				$new_selection[$i]=$extra_selections[$i];

				

			}

		}

		

		for ($e=0; $e<count($extra_array); $e++)

		{

			$ex = $extra_array[$e];

			$idextra=$ex->idextra;



			if (in_array($ex->idextra,$new_selection))

			{

				$html = '<input type="checkbox" onClick="updateIDs(); calculatePrice();" name="extra_box" value="' . $ex->idextra . '" checked/>'.$ex->name." ";

				if ($initial_ids != "")

					 $initial_ids .= ",".$ex->idextra;

				else

					 $initial_ids .= $ex->idextra;

			}

			else

				$html = '<input type="checkbox" onClick="updateIDs(); calculatePrice();" name="extra_box" value="' . $ex->idextra  . '" />'.$ex->name." ";



			if ($ex->value_type==8 || $ex->value_type==9)

			{

				if(isset($quantities[$ex->idextra]))

				{					

					$html .= '<input type="text" onChange="calculatePrice(); updateIDs(); " size="5" value="' .$quantities[$ex->idextra] . '" id="'.$ex->idextra.'_qnt" /><br />';

					$initial_ids .= "-".$quantities[$ex->idextra];

				}

				else

				{

					$html .= '<input type="text" onChange="calculatePrice(); updateIDs(); " size="5"  id="'.$ex->idextra.'_qnt" /><br />';

				

				}

			}

			else

			$html .= '<br />';

			echo $html;

		}

		

		?></td>

	</tr>
	
	<tr>
		<td width="100" align="right" class="key">
			<label for="paymethod"><?php echo JText::_("Pay Method"); ?></label>
		</td>
		<td>
		<?php
		if( $this->booking->pay_method == 1 ){
			echo JText::_("<strong>Deposit to Bank Account</strong>");
		} elseif ( $this->booking->pay_method == 2 ){
			echo JText::_("<strong>Credit Card</strong>");
			echo JText::_("<br />Card Number: ").$this->booking->cardnumber;
			echo JText::_("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Card Type: ").$this->booking->cardname;
			echo JText::_("<br />Expiry: ").$this->booking->exp_month.'/'.$this->booking->exp_year;
		} else {
			echo JText::_("Pay Method Unknown!");
		}
		?>
		</td>
	</tr>

	<tr>

		<td rowspan="4" width="100" align="right" class="key"><label

			for="value"> <?php echo JText::_( 'Price' ); ?>: </label></td>
	</tr>



		<tr>

			<td><?php echo JText::_('Total:');?><input class="text_area"

				type="text" name="value_full" id="value_full" size="10"

				maxlength="20" value="<?php echo $this->booking->value_full;?>" /> <input

				name="calc_button" type="button" value="Calc."

				onclick="calculatePrice();" /></td>

		</tr>



		<tr>

			<td><?php echo JText::_('Paid:');?><input class="text_area"

				type="text" name="value_paid" id="value_paid" size="10"

				maxlength="20" value="<?php echo $this->booking->value_paid;?>"

				onchange="updateDue();" /></td>

		</tr>



		<tr>

			<td><?php echo JText::_('Due:');?><input class="text_area"

				type="text" name="value_pending" id="value_pending" size="10"

				maxlength="20" value="<?php echo $this->booking->value_pending;?>" /></td>

		</tr>

	</tr>

	<tr>

		<td><input name="calendar_button" type="button" value="Calendar"

			onclick="navigate('','');" /></td>

		<td>

		<div id="calback">

		<div id="calendar"></div>

		</div>

		</td>



	</tr>



</table>

<div id="error_msg_div" style="margin-left: 150px; color: #FF0000;"></div>



</fieldset>



</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idbook"

	value="<?php echo $this->booking->idbook; ?>" /> <input type="hidden"

	name="task" value="" /><input type="hidden" name="extra_ids"

	id="extra_ids" value="<?php echo $initial_ids;?>" /> <input type="hidden" name="controller"

	value="bookingedit" /></form>





<script language='javascript'>   



function updateIDs (){



	var adminForm_js = document.getElementById('adminForm').elements;

	var ids="";

	for(var i = 0, j=0; i < adminForm_js.length; i++) 

	{ 

		if (adminForm_js[i].type=="checkbox") 

		{

			if(adminForm_js[i].checked==true)

			{

				if (document.getElementById(adminForm_js[i].value+"_qnt")!=null)

				{

					if (document.getElementById(adminForm_js[i].value+"_qnt").value>0)

					{

						

						qnt=document.getElementById(adminForm_js[i].value+"_qnt").value;

						if (ids.length>0)

							ids +=",";

						ids += adminForm_js[i].value+"-"+qnt;

						//alert(ids);

						qnt=0;

					}

				}

				else

				{

					if (ids.length>0)

						ids +=",";

					ids += adminForm_js[i].value;

				}

				

			}

		}

	}

	document.getElementById('extra_ids').value=ids;

}



function ajaxUpdateRooms(){



	

	category_id = document.getElementById('idcategory').value;

	var xmlhttp=GetXmlHttpObject();

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

    	  

          var rooms=document.getElementById('idroom');

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



          rooms.options.length=0;

          rooms.options[0]=new Option("-Let the System Decide-", 0, true, false);

         var xmlRooms = xmlDoc.getElementsByTagName('room');



          for (var i=0; i < xmlRooms.length; i++){     

        	 	var room = xmlRooms[i];

        	 	var id = room.getAttributeNode('id').value;

        	 	var name = room.getAttributeNode('name').value;



				rooms.options[i+1]=new Option(name, id, false, false);

			}

        

          }

    };

 

	var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=availability&task=ajaxrooms&view=availability&format=raw&idcategory="+category_id;



	xmlhttp.open("GET",url,true);

						

	xmlhttp.send(null);

}



function calculatePrice(){

	var valid_from_js = document.getElementById('valid_from').value;

	var valid_to_js = document.getElementById('valid_to').value;

	var idcategory_js = document.getElementById('idcategory').value;

	var idroom_js = document.getElementById('idroom').value;

	var idguests_js = document.getElementById('idguests').value;

	var nguests_js = document.getElementById('nguests').value;

	var nchilds_js = document.getElementById('nchilds').value;
	
	// Thessite
	// Mipos prepei na mpei kai edw to $lchilds ???
	//
	
	var idcoupon_js = document.getElementById('idcoupon').value;

	var adminForm_js = document.getElementById('adminForm').elements;

	var services=new Array(); 

	var qnt=0;

	for(var i = 0, j=0; i < adminForm_js.length; i++) 

	{ 

		if (adminForm_js[i].type=="checkbox") 

		{

			

			if(adminForm_js[i].checked==true)

			{

				

				if (document.getElementById(adminForm_js[i].value+"_qnt")!=null)

				{

					if (document.getElementById(adminForm_js[i].value+"_qnt").value>0)

					{

							qnt=document.getElementById(adminForm_js[i].value+"_qnt").value;

							services[j]=adminForm_js[i].value+"-"+qnt;

							qnt=0;

							

							j++;

					}

				}

				else

				{

					services[j]=adminForm_js[i].value;

					

					j++;

				}

			}

		}

	}

	var xmlhttp=GetXmlHttpObject();

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){ 

         

    	  var full_msg = xmlhttp.responseText;

    	  

		  var msg_array = full_msg.split(",");

		  var full_price = msg_array[0];

    	  document.getElementById('value_full').value = full_price;

    	

    	  var paid = document.getElementById('value_paid').value;

    	  if (paid>0)

    		  document.getElementById('value_pending').value = full_price-paid;

    	  else

    		  document.getElementById('value_pending').value = full_price;



		  document.getElementById('error_msg_div').innerHTML = msg_array[1];



          }

    };

 

	var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=booking&task=pricecalc&view=booking&format=raw";

	var url=url+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js;

	var url=url+"&idcategory="+idcategory_js+"&idroom="+idroom_js;

	var url=url+"&idguests="+idguests_js+"&nguests="+nguests_js+"&idcoupon="+idcoupon_js;

	var url=url+"&nchilds="+nchilds_js;



	var extra_ids_js="";

	for (var k=0; k<services.length; k++)

	{

		if (k+1==services.length)

			extra_ids_js = extra_ids_js+services[k];

		else

			extra_ids_js = extra_ids_js+services[k]+",";





	}

	var url=url+"&extra_ids="+extra_ids_js;



	xmlhttp.open("GET",url,true);					

	xmlhttp.send(null);



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





function updateDue ()

{

	document.getElementById('value_pending').value = document.getElementById('value_full').value-document.getElementById('value_paid').value;

}

function updateToField(format){

	

	if (format=='d-m-Y')

	{

		var arr =  document.getElementById('valid_from').value.split("-");

		newFromDate = new Date(arr[2],arr[1]-1,arr[0]);

		newFromDate.setDate(newFromDate.getDate()+1);

		document.getElementById('valid_to').value=getPHPDay(newFromDate.getDate())+"-"+getPHPMonth(newFromDate.getMonth())+"-"+newFromDate.getFullYear();

	}

	else if (format=='Y-m-d')

	{

		var arr =  document.getElementById('valid_from').value.split("-");

		newFromDate = new Date(arr[0],arr[1]-1,arr[2]);

		

		newFromDate.setDate(newFromDate.getDate()+1);

		document.getElementById('valid_to').value=newFromDate.getFullYear()+"-"+getPHPMonth(newFromDate.getMonth())+"-"+getPHPDay(newFromDate.getDate());

	}

	else if (format=='m\/d\/Y')

	{

		var arr =  document.getElementById('valid_from').value.split("/");

		newFromDate = new Date(arr[2],arr[0]-1,arr[1]);

		newFromDate.setDate(newFromDate.getDate()+1);

		document.getElementById('valid_to').value=getPHPMonth(newFromDate.getMonth())+"/"+getPHPDay(newFromDate.getDate())+"/"+newFromDate.getFullYear();

	}

	

}



function getPHPMonth (month)

{

	if (month==0)

		return "01";

	else if (month==1)

		return "02";

	else if (month==2)

		return "03";

	else if (month==3)

		return "04";

	else if (month==4)

		return "05";

	else if (month==5)

		return "06";

	else if (month==6)

		return "07";

	else if (month==7)

		return "08";

	else if (month==8)

		return "09";

	else if (month==9)

		return "10";

	else if (month==10)

		return "11";

	else if (month==11)

		return "12";

	else

		alert ("Date Error!");

	

}



function getPHPDay (day){

	if (day<10)

		return "0"+day;

	else

		return day;

}



var req;



function navigate(month,year) {

	var idroom_js = document.getElementById('idroom').value;

	var valid_from_js = document.getElementById('valid_from').value;

	var idcategory_js = document.getElementById('idcategory').value;

	

	if (month=='' && year=='')

	{

		if (valid_from_js.indexOf("-")>0)

		{

			var valid_from_js_array = valid_from_js.split('-');

			month = valid_from_js_array['1'];

			if (valid_from_js_array['0'].length==4)

			{

				year = valid_from_js_array['0'];

			}

			else

				year = valid_from_js_array['2'];

			

		}

		else

		{

			

			var valid_from_js_array = valid_from_js.split('\/'); 

			month = valid_from_js_array['0'];

			year = valid_from_js_array['2'];

		}

	}

	//var url = "calendar.php?month="+month+"&year="+year;

        var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=booking&task=calendar&view=booking&format=raw&month="+month+"&year="+year+"&idroom="+idroom_js+"&idcategory="+idcategory_js;

  

        if(window.XMLHttpRequest) {

                req = new XMLHttpRequest();

        } else if(window.ActiveXObject) {

                req = new ActiveXObject("Microsoft.XMLHTTP");

        }

        req.open("GET", url, true);

        req.onreadystatechange = callback;

        req.send(null);

}



function callback() {        

        obj = document.getElementById("calendar");

        setFade(0);

        

		if(req.readyState == 4) {

                if(req.status == 200) {

                        response = req.responseText;

                        obj.innerHTML = response;

                        

                        fade(0);

                } else {

                        alert("There was a problem retrieving the data:\n" + req.statusText);

                }

        }

}



function fade(amt) {

	if(amt <= 100) {

		setFade(amt);

		amt += 10;

		setTimeout("fade("+amt+")", 5);

    }

}



function setFade(amt) {

	obj = document.getElementById("calendar");

	

	amt = (amt == 100)?99.999:amt;

  

	// IE

	obj.style.filter = "alpha(opacity:"+amt+")";

  

	// Safari<1.2, Konqueror

	obj.style.KHTMLOpacity = amt/100;

  

	// Mozilla and Firefox

	obj.style.MozOpacity = amt/100;

  

	// Safari 1.2, newer Firefox and Mozilla, CSS3

	obj.style.opacity = amt/100;

}





</script>

