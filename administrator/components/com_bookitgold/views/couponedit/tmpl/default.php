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

?>



<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

<fieldset class="adminform"><legend><?php echo JText::_( 'Coupon Basics' ); ?></legend>

<table class="admintable">

	<tr>

		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="name" id="name"

			size="30" maxlength="250" value="<?php echo $this->coupon->name;?>" />

		</td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="code"> <?php echo JText::_( 'Code' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="code" id="code"

			size="30" maxlength="250" value="<?php if ($this->coupon->name=="") echo  uniqid(""); else echo $this->coupon->code;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="value"> <?php echo JText::_( 'Value' ); ?>:

		</label></td>



		<td><?php echo JText::_('fixed:');?><input class="text_area"

			type="text" name="value_fix" id="value_fix" size="16" maxlength="50"

			value="<?php echo $this->coupon->value_fix;?>" /> <?php echo JText::_('or percentage:');?><input

			class="text_area" type="text" name="value_percent" id="value_percent"

			size="16" maxlength="50"

			value="<?php echo $this->coupon->value_percent;?>" /></td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="usable"> <?php echo JText::_( 'Is Usable?' ); ?>

		</label></td>



		<td><?php 

		$this->coupon->usable==""?$value = 1:$value=$this->coupon->usable;

		echo JHTML::_('select.booleanlist',  'usable', '', $value);?>

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="used"> <?php echo JText::_( 'Is Used?' ); ?>

		</label></td>



		<td><?php echo JHTML::_('select.booleanlist',  'used', 'class="inputbox"', $this->coupon->used);?> 

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="available"> <?php echo JText::_( 'Available' ); ?></label></td>

		<?php $params =& JComponentHelper::getParams('com_bookitgold');

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

		

		<td><?php echo JText::_('From'); ?> 

		<?php 

		$js2 = 'onChange="(updateToField(\''.$d1.'\'));"';

		if($this->coupon->valid_from!="") //Edit

		{

			echo JHTML::_('calendar',date($d1, strtotime($this->coupon->valid_from)),'valid_from','valid_from',$d2,'size="10"'.$js2);

		}

		else

			echo JHTML::_('calendar',date($d1),'valid_from','valid_from',$d2,'size="10"'.$js2);

		?> 

		<?php echo JText::_('To');?> 

		<?php if($this->coupon->valid_to!="")

		{

			echo JHTML::_('calendar',date($d1, strtotime($this->coupon->valid_to)),'valid_to','valid_to',$d2,'size="10"');

		}

		else  echo JHTML::_('calendar',date($d1, strtotime("+1 day")),'valid_to','valid_to',$d2,'size="10"');?>

		</td>

	</tr> 

</table>

</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_( 'Coupon Extra' ); ?></legend>

<table class="admintable">

	<tr>



		<td width="100" align="right" class="key"><label for="every_x_day"> <?php echo JText::_( 'Coupon applied every' ); ?>:

		</label></td>

		<td>

			<input class="text_area" type="text" name="every_x_day" id="every_x_day"

			size="30" maxlength="250" value="<?php echo $this->coupon->every_x_day;?>" /><?php echo JText::_( 'days of accommodation.' ); ?>

		</td>

	</tr>

	



</table>

</fieldset>



</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idcoupon"

	value="<?php echo $this->coupon->idcoupon; ?>" /> <input type="hidden"

	name="task" value="" /> <input type="hidden" name="controller"

	value="couponedit" /></form>

<script language='javascript'>   

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

</script>

