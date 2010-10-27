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

//used to layout the data from the view in a particular manner.

//The variables assigned by the JView::assignRef method can be accessed from

//the template using $this->{propertyname}



defined('_JEXEC') or die('Restricted access');



$stylesheet = 'search_form.css';

$path = 'components/com_bookitgold/css/';

JHTML::stylesheet($stylesheet, $path);

$stylesheet_cal = 'calendar_style_front.css';

JHTML::stylesheet($stylesheet_cal, $path);



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



$show_cal = $params->get('show_calendar',1);



$lists=array();

$db =& JFactory::getDBO();

$sql = "SELECT idcategory, name FROM #__bookitcategory ORDER BY cost";

$db->setQuery($sql);


$select_one = "-".JText::_('Select One')."-";
//$results[] = JHTML::_('select.option', 0, '-Select One-', 'idcategory', 'name' );
$results[] = JHTML::_('select.option', 0, $select_one, 'idcategory', 'name' );


$results = array_merge( $results, $db->loadObjectList() );

$js = 'onChange="(updateCategoryField());"'; 

$lists['catid']  = JHTML::_('select.genericList', $results, 'idcategory', 'class="bookit_search_inputbox" size="1"'.$js, 'idcategory', 'name');





$menus = &JSite::getMenu();

$menu  = $menus->getActive();

$itemid = $menu->id;



?>

<div align="center">
	<?php echo "<strong>".JText::_('Welcome to "Akti Retzika" reservations system!')."</strong>"; ?>
    <?php echo "<p>".JText::_("To be able to check the availability and proceed with your reservation please fill in the information required below.")."</p>"; ?>
</div>


<form action="<?php echo JRoute::_( 'index.php?option=com_bookitgold&amp;controller=bookingform&amp;task=bookingdefault');?>" name="searchForm"

	method="post">
<fieldset class="bookit_search_fieldset"><legend class="bookit_search_legent"><?php echo JText::_('Search Availability');?></legend>

<div id="bookit_search_div">

<div id="bookit_search_tdl">

<p><label for="valid_from" class="bookit_search_label"><?php echo JText::_("Date of Arrival");?></label>

</p>

<p><?php $startdate = date ($d1,strtotime("+5 day")); $fn="valid_from"; $fn2="valid_to";

$js = 'onChange="(updateDateField(\''.$fn.'\',\''.$d1.'\')); (setDayName(\''.$fn.'\',\''.$d1.'\')); (setDayName(\''.$fn2.'\',\''.$d1.'\'));"';

echo JHTML::_( 'calendar',$startdate,'valid_from','valid_from',$d2,'class="bookit_search_inputbox" size="10"'.$js);?>



</p>

</div>



<div id="bookit_search_tdr">

<p><label for="valid_to" class="bookit_search_label"><?php echo JText::_("Date of Departure")?></label>

</p>

<p><?php 

$js2 = 'onChange="(updateDateField(\''.$fn2.'\',\''.$d1.'\')); (setDayName(\''.$fn2.'\',\''.$d1.'\')); (setDayName(\''.$fn.'\',\''.$d1.'\'));"';

echo JHTML::_( 'calendar',date($d1,strtotime("+ 6day")),'valid_to','valid_to',$d2,'class="bookit_search_inputbox" size="10"'.$js2);?>

</p>

</div>

<div id="bookit_search_clear" ></div>



<div id="valid_from_day_div" class="bookit_search_tdl_day"><?php echo date("l",strtotime("+5 day"));?></div>

<div id="valid_to_day_div" class="bookit_search_tdr_day"><?php echo date("l",strtotime("+6 day"));?></div>

<div id="bookit_search_clear"></div>







<div id="bookit_search_tdr">

<p><label for="idcategory" class="bookit_search_label"><?php echo JText::_("Room Type")?></label>

</p><div id="category_error_div" class="bookit_search_error"></div>

<p><br />
<?php echo $lists['catid'];?></p>

</div>



<div id="bookit_search_tds">

<p><label for="nguests" class="bookit_search_label"><?php echo JText::_("Adults");?></label></p>

<p><br />
<select id="nguests" name="nguests" class="bookit_search_select">

	<option value="1" selected="selected">1</option>

	<option value="2">2</option>

	<option value="3">3</option>

	<option value="4">4</option>

</select></p>

</div>


<div id="bookit_search_tds">

<p><label for="nchilds" class="bookit_search_label"><?php echo JText::_("Children, 0 to 5 years old"); // Thessite?></label></p>

<p><select id="nchilds" name="nchilds" class="bookit_search_select">

	<option value="0" selected="selected">0</option>

	<option value="1">1</option>

	<option value="2">2</option>
    
   	<option value="3">3</option>
</select></p>
</div>
<?php // Start of Thessite Code ?>
<div id="bookit_search_tds">
<p><label for="lchilds" class="bookit_search_label"><?php echo JText::_("Children, 6 to 12 years old"); // Thessite?></label></p>
<p><select id="lchilds" name="lchilds" class="bookit_search_select">
	<option value="0" selected="selected">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>    
</select></p>
</div>
<?php // End of Thessite Code ?>


<div id="bookit_search_clear"></div>

<div id="valid_to_error_div" class="bookit_search_error"></div>

<div id="bookit_search_clear"></div>

<div id="" class="bookit_search_tdl_day" ></div>

<div id="bookit_search_clear"></div>

<div id="bookit_search_tdr">



<p>



<button class="button" id="bookit_search_button"  onclick="<?php echo "return submitbutton('send','".$d1."')";?>"><?php echo JText::_('Search'); ?>

</button>

</p>

<div id="bookit_search_loading"></div>



</div>



<div id="bookit_search_clear"></div>

<div id="quick_results" ></div>

<div id="bookit_search_clear"></div>

<div id="quick_results_error"></div>



</div>



<div id="bookit_search_calendar_div">

<div id="calback">

		<div id="calendar"></div>

		</div>

</div>

<div id="bookit_search_clear"></div>







</fieldset>



<div id="bookit_search_results" style="visibility:hidden">

<fieldset class="bookit_search_fieldset">

	<legend class="bookit_search_legent"><?php echo JText::_("Search Results");?></legend>

		<div id="bookit_search_result_found"></div>

		<div id="bookit_search_clear"></div>

		<div id="bookit_search_result_other"></div>

		

</fieldset>

</div>







<input type="hidden" name="layout" value="bookingform" />
<input type="hidden" name="option" value="com_bookitgold" /> 
<input type="hidden" name="nnights" id="nnights" /> 
<input type="hidden" name="price" id="price" /> 
<input type="hidden" name="pricereg" id="pricereg" /> 
<input type="hidden" id= "show_cal_element" name="show_cal_element" value="<?php echo $show_cal; ?>" /> 

</form>



<script language="javascript" type="text/javascript">

function submitForm (){



	document.searchForm.submit();

}



function submitbutton(pressbutton, format) {



			document.getElementById("valid_to_error_div").innerHTML="";

			var form = document.searchForm;

		    if (form.idcategory.value==0)

		    {

		    	document.getElementById("category_error_div").innerHTML = "<?php echo JText::_("Please select a room type"); ?>";

		    	return false;

		    }

		    else if (!checkPeriod(format))

		    {

		    	document.getElementById("valid_to_error_div").innerHTML = "<?php echo JText::_("Your stay cannot be longer than 120 nights."); ?>";

		    }

		    else

		    {

		    	var root ="<?php echo JURI::root()?>";

		    	document.getElementById('bookit_search_loading').innerHTML = '<img src="'+root+'components/com_bookitgold/images/calWaiting.gif" alt=""  />';

		    	quickResult();

				return false;

		    }

		    return false;

		

	}



	function checkPeriod (format)

	{

		if (format=='d-m-Y')

		{

			var arr =  document.getElementById('valid_to').value.split("-");

			newToDate = new Date(arr[2],arr[1]-1,arr[0]);

			var arr2 =  document.getElementById('valid_from').value.split("-");

			newFromDate = new Date(arr2[2],arr2[1]-1,arr2[0]);	

		}

		else if (format=='Y-m-d')

		{

			var arr =  document.getElementById('valid_to').value.split("-");

			newToDate = new Date(arr[0],arr[1]-1,arr[2]);

			var arr2 =  document.getElementById('valid_from').value.split("-");

			newFromDate = new Date(arr2[0],arr2[1]-1,arr2[2]);	

		}

		else if (format=='m\/d\/Y')

		{

			var arr =  document.getElementById('valid_to').value.split("/");

			newToDate = new Date(arr[2],arr[0]-1,arr[1]);

			var arr2 =  document.getElementById('valid_from').value.split("/");

			newFromDate = new Date(arr2[2],arr2[0]-1,arr2[1]);	

		}

		if (days_between(newToDate,newFromDate)>120)

				return false;

		

		else

			return true;

	}

	

	function updateDateField(field,format){



		var now = new Date();

		var today = new Date (now.getFullYear(), now.getMonth(), now.getDate()+5 );



		document.getElementById("valid_from").style.border="";

		document.getElementById("valid_to").style.border="";

		if (format=='d-m-Y')

		{

			if (field=='valid_from')

			{

				var arr =  document.getElementById('valid_from').value.split("-");

				newFromDate = new Date(arr[2],arr[1]-1,arr[0]);			

				if (newFromDate < today)

				{

					document.getElementById('valid_from').value=getPHPDay(now.getDate())+"-"+getPHPMonth(now.getMonth())+"-"+now.getFullYear();

					setDayName("valid_from", "d-m-Y");

					document.getElementById("valid_from").style.border='2px solid red';

					newFromDate = new Date ();

					newFromDate.setDate (now.getDate()+5);

					document.getElementById('valid_to').value=getPHPDay(newFromDate.getDate())+"-"+getPHPMonth(newFromDate.getMonth())+"-"+newFromDate.getFullYear();

					setDayName("valid_to", "d-m-Y");

				}

				else

				{

					newFromDate.setDate(newFromDate.getDate()+1);

					document.getElementById('valid_to').value=getPHPDay(newFromDate.getDate())+"-"+getPHPMonth(newFromDate.getMonth())+"-"+newFromDate.getFullYear();

				}

			}

			else if (field=='valid_to')

			{

				var arr =  document.getElementById('valid_to').value.split("-");

				newToDate = new Date(arr[2],arr[1]-1,arr[0]);

				var arr2 =  document.getElementById('valid_from').value.split("-");

				newFromDate = new Date(arr2[2],arr2[1]-1,arr2[0]);	



			 if (newToDate <= newFromDate)

				{

					newToDate = new Date ();

					newToDate.setDate(newFromDate.getDate()+5);

					document.getElementById('valid_to').value=getPHPDay(newToDate.getDate())+"-"+getPHPMonth(newToDate.getMonth())+"-"+newToDate.getFullYear();

					setDayName("valid_to", "d-m-Y");

					//document.getElementById('valid_to_error_div').innerHTML = "<?php echo JText::_("invalid date"); ?>";

					document.getElementById("valid_to").style.border='2px solid red';

							

				}

				

			}

		}

		else if (format=='Y-m-d')

		{

			

			if (field=='valid_from')

			{

				var arr =  document.getElementById('valid_from').value.split("-");

				newFromDate = new Date(arr[0],arr[1]-1,arr[2]);			

				if (newFromDate < today)

				{

					document.getElementById('valid_from').value=now.getFullYear()+"-"+getPHPMonth(now.getMonth())+"-"+getPHPDay(now.getDate());

					setDayName("valid_from", "Y-m-d");

					document.getElementById("valid_from").style.border='2px solid red';

					newFromDate = new Date ();

					newFromDate.setDate (now.getDate()+5);

					document.getElementById('valid_to').value=newFromDate.getFullYear()+"-"+getPHPMonth(newFromDate.getMonth())+"-"+getPHPDay(newFromDate.getDate());

					setDayName("valid_to", "Y-m-d");

				}

				else

				{

					newFromDate.setDate(newFromDate.getDate()+5);

					document.getElementById('valid_to').value=newFromDate.getFullYear()+"-"+getPHPMonth(newFromDate.getMonth())+"-"+getPHPDay(newFromDate.getDate());

				}

			}

			else if (field=='valid_to')

			{

				var arr =  document.getElementById('valid_to').value.split("-");

				newToDate = new Date(arr[0],arr[1]-1,arr[2]);

				var arr2 =  document.getElementById('valid_from').value.split("-");

				newFromDate = new Date(arr2[0],arr2[1]-1,arr2[2]);	

				if (newToDate <= newFromDate)

				{

					newToDate = new Date ();

					newToDate.setDate(newFromDate.getDate()+1);

					document.getElementById('valid_to').value=newToDate.getFullYear()+"-"+getPHPMonth(newToDate.getMonth())+"-"+getPHPDay(newToDate.getDate());

					setDayName("valid_to", "Y-m-d");

					document.getElementById("valid_to").style.border='2px solid red';

							

				}

			}

			

		}

		else if (format=='m\/d\/Y')

		{

			if (field=='valid_from')

			{

				var arr =  document.getElementById('valid_from').value.split("/");

				newFromDate = new Date(arr[2],arr[0]-1,arr[1]);			

				if (newFromDate < today)

				{

					document.getElementById('valid_from').value=getPHPMonth(now.getMonth())+"/"+getPHPDay(now.getDate())+"/"+now.getFullYear();

					setDayName("valid_from", "m\/d\/Y");

					document.getElementById("valid_from").style.border='2px solid red';

					newFromDate = new Date ();

					newFromDate.setDate (now.getDate()+5);

					document.getElementById('valid_to').value=getPHPMonth(newFromDate.getMonth())+"/"+getPHPDay(newFromDate.getDate())+"/"+newFromDate.getFullYear();

					setDayName("valid_to", "m\/d\/Y");

				}

				else

				{

					newFromDate.setDate(newFromDate.getDate()+5);

					document.getElementById('valid_to').value=getPHPMonth(newFromDate.getMonth())+"/"+getPHPDay(newFromDate.getDate())+"/"+newFromDate.getFullYear();

				}

			}

			else if (field=='valid_to')

			{

				var arr =  document.getElementById('valid_to').value.split("/");

				newToDate = new Date(arr[2],arr[0]-1,arr[1]);

				var arr2 =  document.getElementById('valid_from').value.split("/");

				newFromDate = new Date(arr2[2],arr2[0]-1,arr2[1]);	

				if (newToDate <= newFromDate)

				{

					newToDate = new Date ();

					newToDate.setDate(newFromDate.getDate()+5);

					document.getElementById('valid_to').value=getPHPMonth(newToDate.getMonth())+"/"+getPHPDay(newToDate.getDate())+"/"+newToDate.getFullYear();

					setDayName("valid_to", "m\/d\/Y");

					document.getElementById("valid_to").style.border='2px solid red';

							

				}

				

			}



		}

		

	}



	function days_between(date1, date2) {



	    // The number of milliseconds in one day

	    var ONE_DAY = 1000 * 60 * 60 * 24;



	    // Convert both dates to milliseconds

	    var date1_ms = date1.getTime();

	    var date2_ms = date2.getTime();



	    // Calculate the difference in milliseconds

	    var difference_ms = Math.abs(date1_ms - date2_ms);

	    

	    // Convert back to days and return

	    return Math.round(difference_ms/ONE_DAY);



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

			alert ("Date Error!:"+month);

		

	}



	function getPHPDay (day){

		if (day<10)

			return "0"+day;

		else

			return day;

	}



	function setDayName(field, format){



		var myDays=["<?php echo JText::_('Sunday');?>","<?php echo JText::_('Monday');?>","<?php echo JText::_('Tuesday');?>","<?php echo JText::_('Wednesday');?>","<?php echo JText::_('Thursday');?>","<?php echo JText::_('Friday');?>","<?php echo JText::_('Saturday');?>","<?php echo JText::_('Sunday');?>"];

		

			if (format=='d-m-Y')

			{

				var arr =  document.getElementById(field).value.split("-");

				newFromDate = new Date(arr[2],arr[1]-1,arr[0]);

				document.getElementById(field+"_day_div").innerHTML=myDays[newFromDate.getDay()];

			}

			else if (format=='Y-m-d')

			{

				var arr =  document.getElementById(field).value.split("-");

				newFromDate = new Date(arr[0],arr[1]-1,arr[2]);

				document.getElementById(field+"_day_div").innerHTML=myDays[newFromDate.getDay()];

			}

			else if (format=='m\/d\/Y')

			{

				var arr =  document.getElementById(field).value.split("/");

				newFromDate = new Date(arr[2],arr[0]-1,arr[1]);

				document.getElementById(field+"_day_div").innerHTML=myDays[newFromDate.getDay()];

			}



	}



	function updateCategoryField(){



		if (document.getElementById('idcategory').value!=0)

			document.getElementById("category_error_div").innerHTML ="";



	}



	//QUICK RESULT START

	function quickResult (){



		var valid_from_js = document.getElementById('valid_from').value;

		var valid_to_js = document.getElementById('valid_to').value;

		var idcategory_js = document.getElementById('idcategory').value;

		var nguests_js = document.getElementById('nguests').value;

		var nchilds_js = document.getElementById('nchilds').value;
		
		var lchilds_js = document.getElementById('lchilds').value; // Thessite

		var qresults=document.getElementById('quick_results');

		 document.getElementById('bookit_search_result_found').innerHTML= "";

		 document.getElementById('bookit_search_results').style.visibility='hidden';

		 document.getElementById('quick_results_error').innerHTML="";

		 qresults.innerHTML="";



		var xmlhttp=GetXmlHttpObject();

		if (xmlhttp==null)

	  	{

	  		alert ("Browser does not support HTTP Request");

	  		return;

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



	        var xmlQRes = xmlDoc.getElementsByTagName('results')[0];

	      	var isAvailable = xmlQRes.getAttributeNode('available').value;



		   if (isAvailable==-1)

		   {

			 document.getElementById('quick_results_error').innerHTML="<?php echo JText::_('The selected room type cannot accommodate the selected number of guests.');?>";

			

		   }

		   else if (isAvailable==1)

	       {



			   qresults.innerHTML = "<?php echo '<font color=\"green\">'.JText::_('Rooms are available for the selected dates.').'</font>';?>";     

			   document.getElementById('bookit_search_results').style.visibility='visible';

	    	   document.getElementById('bookit_search_result_found').innerHTML=xmlQRes.getAttributeNode('result').value;



	    	   document.getElementById('nnights').value=xmlQRes.getAttributeNode('nnights').value;

	    	   document.getElementById('price').value=xmlQRes.getAttributeNode('price').value;

	    	   document.getElementById('pricereg').value=xmlQRes.getAttributeNode('pricereg').value;

			 



				if (document.getElementById('show_cal_element').value==1)

				{	

		    	   navigate('','');

				}

	    	   

	       }

	       else

	       {

	    	   document.getElementById('bookit_search_results').style.visibility='hidden';

	    	   qresults.innerHTML = "<?php echo JText::_('Sorry, no vacancies.')?>"+"\n"+"<?php echo JText::_('Please try different dates or room type.');?>";  

	    	   if (document.getElementById('show_cal_element').value==1)

				{	

		    	   navigate('','');

				}

	    	  

	       }

		   document.getElementById("bookit_search_loading").innerHTML ="";

	     }

	    };

		

	 

		var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&task=searchResults&view=bookitgold&format=raw&idcategory="+idcategory_js+"&valid_from="+valid_from_js+"&valid_to="+valid_to_js+"&nguests="+nguests_js+"&nchilds="+nchilds_js+"&lchilds="+lchilds_js; // Thessite

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

	

	

	//QUICK RESULT END



	//CALENDAR-START

	var req;



	function navigate(month,year) {



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

        var url="<?php echo JURI::base()?>"+"index.php?option=com_bookitgold&task=calendar&view=bookitgold&format=raw&month="+month+"&year="+year+"&idcategory="+idcategory_js;



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

                        alert("There was a problem retrieving the data from the calendar.\n" + req.statusText);

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

	

	

	

	

	//CALENDAR-END





</script>



