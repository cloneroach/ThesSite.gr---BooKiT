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



defined('_JEXEC') or die('Restricted access'); ?>



<form action="index.php" method="post" name="adminForm">

<div id="editcell">

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

			<th width="5%"><input type="checkbox" name="toggle" value=""

				onclick="checkAll(<?php echo count( $this->extras ); ?>);" /></th>

			<th width="5%"><?php echo JText::_( 'ID' ); ?></th>



			<th width="40%"><?php echo JText::_( 'Extra Name' ); ?></th>



			<th width="20%"><?php echo JText::_( 'Extra Price' ); ?></th>

			<th width="20%"><?php echo JText::_( 'Value Per' ); ?></th>



		</tr>

	</thead>

	<?php

	/*

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

	 */





	$k = 0;

	for ($i=0, $n=count( $this->extras ); $i < $n; $i++)

	{

		$row =& $this->extras[$i];



		$checked    = JHTML::_( 'grid.id', $i, $row->idextra );

		$link = JRoute::_( 'index.php?option=com_bookitgold&controller=roomextra&task=edit&cid[]='. $row->idextra );





		?>

	<tr class="<?php echo "row$k"; ?>">

		<td width="5%" align="center"><?php echo $checked; ?></td>

		<td width="5%" align="center"><?php echo $row->idextra; ?></td>



		<td width="40%" align="center"><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>



		</td>



		<td width="20%" align="center"><?php 

		//echo date($d1,strtotime($row->valid_from));

		$fix = $row->value_fix;

		if ($fix>0)

		echo $fix;

		else

		echo $row->value_percent."%";

		?></td>

		<td width="20%" align="center"><?php 

		$valtype = $row->value_type;

		if ($valtype==1)

		echo JText::_( 'Booking' );

		elseif ($valtype==2)

		echo JText::_( 'Night' );

		elseif ($valtype==3)

		echo JText::_( 'Guest' );

		elseif ($valtype==4)

		echo JText::_( 'Adult' );

		elseif ($valtype==5)

		echo JText::_( 'Child' );

		elseif ($valtype==6)

		echo JText::_( 'Adult/Night' );

		elseif ($valtype==7)

		echo JText::_( 'Child/Night' );

		elseif ($valtype==8)

		echo JText::_( 'Quantity' );

		elseif ($valtype==9)

		echo JText::_( 'Quantity/Night' );

		elseif ($valtype==10)

		echo JText::_( 'Guest/Night' );

		else

		echo JText::_('Unknown');



		?></td>



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

	value="roomextra" /></form>



