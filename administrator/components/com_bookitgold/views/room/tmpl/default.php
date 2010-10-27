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

			<td colspan="13"><?php 

			//Pagination





			echo $this->pagination->getListFooter();?></td>

		</tr>

	</tfoot>

	<thead>

		<tr>

			<th width="2%"><input type="checkbox" name="toggle" value=""

				onclick="checkAll(<?php echo count( $this->rooms ); ?>);" /></th>

			<th width="2%"><?php echo JHTML::_( 'grid.sort', 'ID', 'idroom', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="40%"><?php echo JHTML::_( 'grid.sort', 'Room Name', 'name', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="20%"><?php echo JHTML::_( 'grid.sort', 'Room Category', 'name', $this->lists['order_Dir'], $this->lists['order']); ?></th>





		</tr>

	</thead>

	<?php



	$k = 0;

	for ($i=0, $n=count( $this->rooms ); $i < $n; $i++)

	{

		$row =& $this->rooms[$i];



		$checked    = JHTML::_( 'grid.id', $i, $row->idroom );

		$link = JRoute::_( 'index.php?option=com_bookitgold&controller=room&task=edit&cid[]='. $row->idroom );





		?>

	<tr class="<?php echo "row$k"; ?>">

		<td width="2%" align="center"><?php echo $checked; ?></td>

		<td width="2%" align="center"><?php echo $row->idroom; ?></td>



		<td width="40%" align="center"><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>



		</td>

		<td width="20%" align="center"><?php 



		$row2 =& JTable::getInstance('roomcategory', 'Table');

		$row2->load( $row->idcategory );

		echo $row2->name; ?></td>



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

	value="room" /><input type="hidden" name="filter_order"

	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"

	name="filter_order_Dir"

	value="" /></form>





