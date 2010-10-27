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

			echo $this->pagination->getListFooter();?></td>

		</tr>

	</tfoot>

	<thead>

		<tr>

			<th width="5%"><input type="checkbox" name="toggle" value=""

				onclick="checkAll(<?php echo count( $this->guests ); ?>);" /></th>

			<th width="5%"><?php echo JHTML::_( 'grid.sort', 'ID', 'idguests', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th><?php echo JHTML::_( 'grid.sort', 'First Name', 'name', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th><?php echo JHTML::_( 'grid.sort', 'Last Name', 'surname', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="15%"><?php echo JText::_( 'Country' ); ?></th>

			<th><?php echo JHTML::_( 'grid.sort', 'Email', 'email', $this->lists['order_Dir'], $this->lists['order']); ?></th>

			<th width="15%"><?php echo JHTML::_( 'grid.sort', 'Phone', 'phone', $this->lists['order_Dir'], $this->lists['order']); ?></th>



		</tr>

	</thead>

	<?php



	$k = 0;

	for ($i=0, $n=count( $this->guests ); $i < $n; $i++)

	{

		$row =& $this->guests[$i];



		$checked    = JHTML::_( 'grid.id', $i, $row->idguests );

		$link = JRoute::_( 'index.php?option=com_bookitgold&controller=guests&task=edit&cid[]='. $row->idguests );



		$row2 =& JTable::getInstance('country', 'Table');

		$row2->load($row->idcountry);

		$country = $row2->name;



		?>

	<tr class="<?php echo "row$k"; ?>">

		<td width="5%" align="center"><?php echo $checked; ?></td>

		<td width="5%" align="center"><?php echo $row->idguests; ?></td>



		<td width="15%" align="center"><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>



		</td>



		<td width="15%" align="center"><?php echo $row->surname; ?></td>

		<td width="15%" align="center"><?php echo $country; ?></td>

		<td width="15%" align="center"><?php echo $row->email; ?></td>

		<td width="15%" align="center"><?php echo $row->phone; ?></td>









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

	value="guests" /> <input type="hidden" name="filter_order"

	value="<?php echo $this->lists['order']; ?>" /> <input type="hidden"

	name="filter_order_Dir"

	value="" /></form>

