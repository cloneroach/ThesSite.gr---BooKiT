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



<form action="index.php" method="post" name="adminForm">

<div id="editcell">

<table>

	<tr>

		<td width="100%"><?php echo JText::_('Filter'); ?>: <input type="text"

			name="search" id="search"

			value="<?php echo $this->lists['search'];?>" class="text_area"

			onchange="document.adminForm.submit();" />

		<button onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>

		<button

			onclick="document.getElementById('search').value='';

			this.form.submit();"><?php echo JText::_('RESET'); ?></button>

		</td>

	</tr>

</table>

<table class="adminlist">

	<tfoot>

		<tr>

			<td colspan="14"><?php 

			echo $this->pagination->getListFooter();?></td>

		</tr>

	</tfoot>

	<thead>

	<?php $cost_total =JText::_("Cost(total)");

	$cost_due =JText::_("Cost(due)");

	$guest_name =JText::_("Guest name");

	$guest_surname =JText::_("Guest surname");

	$guest_mail =JText::_("Guest mail");

	?>

		<tr>

		  	<th width="2%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->booking ); ?>);" /></th>

			<th width="2%"><?php echo JHTML::_( 'grid.sort', 'ID', 'idbook', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="3%"><?php echo JText::_('Reference ID'); ?></th>

			<th width="7%"><?php echo JHTML::_( 'grid.sort', 'Booked on', 'today', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="7%"><?php echo JHTML::_( 'grid.sort', 'Status', 'status', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JHTML::_( 'grid.sort', 'Check-in', 'valid_from', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JHTML::_( 'grid.sort', 'Check-out', 'valid_to', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JText::_('Room'); ?></th>

			<th width="3%"><?php echo JHTML::_( 'grid.sort', $cost_total, 'value_full', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="3%"><?php echo JHTML::_( 'grid.sort', $cost_due, 'value_pending', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JHTML::_( 'grid.sort', $guest_name, 'name', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JHTML::_( 'grid.sort', $guest_surname, 'surname', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="10%"><?php echo JHTML::_( 'grid.sort', $guest_mail, 'email', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			
			<th width="30%"><?php echo JHTML::_( 'grid.sort', 'Pay Method', 'paymethod', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			



		</tr>

	</thead>

	<?php

	$g =& JTable::getInstance('guests', 'Table');

	$r =& JTable::getInstance('room', 'Table');

	

	$k = 0;

	for ($i=0, $n=count( $this->booking ); $i < $n; $i++)

	{

		

		

		$row =& $this->booking[$i];

		$g->load($row->idguests);

		$guest_name = $g->name;

		$guest_surname = $g->surname;

		$guest_email = $g->email;
		
		//$pay_method = $g->pay_method; // Thessite

		

		if ($row->idroom==0)

			$room_name= JText::_("Pending");

		else

		{

			$r->load($row->idroom);

			$room_name = $r->name;

		}

		

		$checked    = JHTML::_( 'grid.id', $i, $row->idbook );

		$link = JRoute::_( 'index.php?option=com_bookitgold&controller=booking&task=edit&cid[]='. $row->idbook );

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

	 		$d1='m-d-Y';

		}

		

	 ?>

	<tr class="<?php echo "row$k"; ?>">

		<td width="2%" align="center"><?php echo $checked; ?></td>

		<td width="2%" align="center"><?php echo $row->idbook; ?></td>

		<td width="3%" align="center"><?php echo $row->idbook+1000; ?></td>

		<td width="7%" align="center"><a href="<?php echo $link; ?>"><?php echo date($d1,strtotime($row->today)); ?></a></td>

		<td width="7%" align="center"><?php if ($row->status==2){ ?><font color="red"><?php echo JText::_("Pending"); ?></font> <?php } else if ($row->status==1) {?> <font color="green" ><?php  echo JText::_("Confirmed"); ?></font><?php }?></td>

		<td width="10%" align="center"><?php echo date($d1,strtotime($row->valid_from)); ?></td>

		<td width="10%" align="center"><?php echo date($d1,strtotime($row->valid_to)); ?></td>

		<td width="10%" align="center"><?php echo $room_name; ?></td>

		<td width="3%" align="center"><?php echo round($row->value_full,"2"); ?></td>

		<?php if ($row->value_pending==0){?>

			<td width="3%" align="center" > <font color="green"> <?php echo round($row->value_pending,"2"); ?></font></td>

		<?php } if ($row->value_pending>0){ ?>

			<td width="3%" align="center" ><font color="red"><?php echo round($row->value_pending,"2"); ?></font></td>

		<?php } ?>

		<td width="10%" align="center"><?php echo $guest_name; ?></td>

		<td width="10%" align="center"><?php echo $guest_surname; ?></td>

		<td width="10%" align="center"><?php echo $guest_email; ?></td>
		
		<?php
		if( $row->pay_method == 1 ){ ?>
			 <td width="30%" align="center"><?php echo "<strong>Bank Deposit</strong>"; ?></td>
		<?php } elseif ( $row->pay_method == 2 ) { ?>
			 <td width="30%" align="center"><?php echo "<strong>Credit Card</strong>".
			 											"<br />Credit Card Number: ".$row->cardnumber.
			 											"<br />Card Type: ".$row->cardname.
			 											"<br />Expiry: ".$row->exp_month."-".$row->exp_year; ?></td>
		<?php } else { ?>
			<td width="30%" align="center"><?php echo "Unknown"; ?></td>
			<?php } ?>
			


	</tr>

	<?php

	$k = 1 - $k;

	}

	?>





</table>

</div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="task" value="listing" /> <input type="hidden"

	name="boxchecked" value="0" /> <input type="hidden" name="controller"

	value="booking" /> <input type="hidden" name="filter_order"

	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"

	name="filter_order_Dir"

	value="" /></form>

	

