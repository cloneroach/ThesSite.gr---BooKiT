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



// define style sheet location

$stylesheet = 'calendar_style.css';

$path = 'components/com_bookitgold/css/';

// add style sheet

JHTML::stylesheet($stylesheet, $path);



$lists=array();

$db =& JFactory::getDBO();

$sql = "SELECT idcategory, name FROM #__bookitcategory";

$db->setQuery($sql);





$results[] = JHTML::_('select.option', 0, '-All Categories-', 'idcategory', 'name' );

$results = array_merge( $results, $db->loadObjectList() );

$js = 'onChange="(ajaxUpdateRooms());"';



$lists['catid']  = JHTML::_('select.genericList', $results, 'idcategory', 'class="inputbox" size="1"'.$js, 'idcategory', 'name');



$sql = "SELECT idroom, name FROM #__bookitroom";

$db->setQuery($sql);



$results2[] = JHTML::_('select.option', 0, '-All Rooms-', 'idroom', 'name' );



$results2 = array_merge( $results2, $db->loadObjectList() );

$lists['roomid']  = JHTML::_('select.genericList', $results2, 'idroom', 'class="inputbox" size="1"', 'idroom', 'name');



$sql = "SELECT idoffer, name FROM #__bookitspecialoffer";

$db->setQuery($sql);



$value = '0';

$results3[] = JHTML::_('select.option', -1, 'None', 'idoffer', 'name' );

$results3[] = JHTML::_('select.option', 0, '-Select Offer-', 'idoffer', 'name' );



$results3 = array_merge( $results3, $db->loadObjectList() );

$lists['offers']  = JHTML::_('select.genericList', $results3, 'idoffer', 'class="inputbox" size="1"', 'idoffer', 'name', $value);





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

<fieldset class="adminform"><legend><?php echo JText::_( 'Availability Properties' ); ?></legend>

<table class="admintable">

	<tr>

		<td width="100" align="right" class="key"><label for="period"> <?php echo JText::_( 'Period' ); ?>:

		</label></td>

		<td><?php echo JText::_('From'); ?> <?php 

		$js2 = 'onChange="(updateToField(\''.$d1.'\'));"';

		echo JHTML::_('calendar',date($d1),'valid_from','valid_from',$d2,'size="10"'.$js2);?>

		<?php echo JText::_('To');?> <?php echo JHTML::_('calendar',date($d1,strtotime("+ 1day")),'valid_to','valid_to',$d2,'size="10"');?>

		</td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idcategory"> <?php echo JText::_( 'Room Category' ); ?>:

		</label></td>

		<td><?php 

		echo $lists['catid']  ;

		?></td>



	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idroom"> <?php echo JText::_( 'Room' ); ?>:

		</label></td>

		<td><?php echo $lists['roomid'];?></td>



	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="price"> <?php echo JText::_( 'Price' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="price" id="price"

			size="10" maxlength="25"

			value="<?php echo $this->availability->price;?>" /></td>

	</tr>



	<tr>

		<td width="100" align="right" class="key"><label for="availability"> <?php echo JText::_( 'Availability' ); ?>:

		</label></td>

		<td>

		<input type="radio" name="availability" value="0"

		<?php echo 'class="required"';  ?> /> <?php echo JText::_('Available');?>

		

		<input type="radio" name="availability" value="2"

		<?php echo 'class="required"';  ?> /> <?php echo JText::_('Pending');?>



		<input type="radio" name="availability" value="3" 

		<?php echo 'class="required"';?> /> <?php if ($params->get('extra_availability')=="") echo JText::_('Other'); else echo $params->get('extra_availability');?>

		</td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="idoffer"> <?php echo JText::_( 'Offer' ); ?>:

		</label></td>

		<td><?php echo $lists['offers']  ;?></td>



	</tr>

	<tr>

		<td><input name="check_button" type="button"

			value="<?php echo JText::_('Check');?>" onclick="checkStatus();" /></td>

		<td>

		<div id="status"></div>

		</td>

	</tr>

	<tr>

		<td><input name="calendar_button" type="button"

			value=" <?php echo JText::_('Calendar');?>"

			onclick="navigate('','');" /></td>

		<td>

		<div id="calback">

		<div id="calendar"></div>

		</div>

		</td>

	</tr>

	<tr>

		<td><input name="panoramic_button" type="button"

			value="<?php echo JText::_('Rooms Availability');?>" onclick="checkPanoramic();" /></td>

		<td>

		<div id="panoramic"></div>

		</td>

	</tr>



</table>





</fieldset>



</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idavailability"

	value="<?php echo $this->availability->idavailability; ?>" /> <input

	type="hidden" name="task" value="" /> <input type="hidden"

	name="controller" value="availability" /></form>







<script language='javascript'>   





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

          rooms.options[0]=new Option("-All Rooms-", 0, true, false);

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





function checkStatus(){



	

	var idcategory_js = document.getElementById('idcategory').value;

	var idroom_js = document.getElementById('idroom').value;

	var valid_from_js = document.getElementById('valid_from').value;

	var valid_to_js = document.getElementById('valid_to').value;

	

	var xmlhttp=GetXmlHttpObject();

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

    	  document.getElementById('status').innerHTML = xmlhttp.responseText;

      }

    };



   var root ="<?php echo JURI::root()?>";



    document.getElementById('status').innerHTML = '<img src="'+root+'/components/com_bookitgold/images/calWaiting.gif" alt=""  />';



	var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=availability&task=status&view=availability&format=raw&idcategory="+idcategory_js+"&idroom="+idroom_js+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js;



	xmlhttp.open("GET",url,true);

	xmlhttp.send(null);

}



function checkPanoramic(){



	var valid_from_js = document.getElementById('valid_from').value;

	var valid_to_js = document.getElementById('valid_to').value;

	

	var xmlhttp=GetXmlHttpObject();

	if (xmlhttp==null)

  	{

  		alert ("Browser does not support HTTP Request");

  		return;

  	}

	

	xmlhttp.onreadystatechange = function(){

    

      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){

    	  document.getElementById('panoramic').innerHTML = xmlhttp.responseText;

      }

    };



   var root ="<?php echo JURI::root()?>";



    document.getElementById('panoramic').innerHTML = '<img src="'+root+'/components/com_bookitgold/images/calWaiting.gif" alt=""  />';

	var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=availability&task=panoramic&view=availability&format=raw&valid_from="+valid_from_js+"&valid_to="+valid_to_js;



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



function updateToField(format){



	



	if (format=='d-m-Y')

	{

		var arr =  document.getElementById('valid_from').value.split("-");

		newFromDate = new Date(arr[2],arr[1]-1,arr[0]);

		newFromDate.setDate(newFromDate.getDate());

		document.getElementById('valid_to').value=getPHPDay(newFromDate.getDate())+"-"+getPHPMonth(newFromDate.getMonth())+"-"+newFromDate.getFullYear();

	}

	else if (format=='Y-m-d')

	{

		var arr =  document.getElementById('valid_from').value.split("-");

		newFromDate = new Date(arr[0],arr[1]-1,arr[2]);

		

		newFromDate.setDate(newFromDate.getDate());

		document.getElementById('valid_to').value=newFromDate.getFullYear()+"-"+getPHPMonth(newFromDate.getMonth())+"-"+getPHPDay(newFromDate.getDate());

	}

	else if (format=='m\/d\/Y')

	{

		var arr =  document.getElementById('valid_from').value.split("/");

		newFromDate = new Date(arr[2],arr[0]-1,arr[1]);

		newFromDate.setDate(newFromDate.getDate());

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

        var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&controller=availability&task=calendar&view=availability&format=raw&month="+month+"&year="+year+"&idroom="+idroom_js+"&idcategory="+idcategory_js;

  

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

