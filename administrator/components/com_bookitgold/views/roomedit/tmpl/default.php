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



<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

<fieldset class="adminform"><legend><?php echo JText::_( 'Room Basics' ); ?></legend>

<table class="admintable">



	<tr>

		<td width="30" align="right" class="key"><label for="name"> <?php echo JText::_( 'Room name' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="name" id="name"

			size="32" maxlength="250" value="<?php echo $this->room->name;?>" />

		</td>

	</tr>



	<tr>



		<td width="100" align="right" class="key"><label for="category"> <?php echo JText::_( 'Room category' ); ?>:

		</label></td>



		<td><?php 

		$categories = $this->roomcategories;



		$lists=array();

		$params =& JComponentHelper::getParams('com_bookitgold');

		$width = $params->get('width');

		$height = $params->get('height');



		if (intval($width)!=0)

		$lists['width']=$width;

		else

		$lists['width']=96;

		if (intval($height)!=0)

		$lists['height']=$height;

		else

		$lists['height']=96;



		$db =& JFactory::getDBO();

		$sql = "SELECT idcategory, name FROM #__bookitcategory";

		$db->setQuery($sql);

		

		$value = isset($this->room->idcategory)?$this->room->idcategory:'0';

		$results[] = JHTML::_('select.option', 0, '-Select an Item-', 'idcategory', 'name' );

		$results = array_merge( $results, $db->loadObjectList() );

		$lists['catid']  = JHTML::_('select.genericList', $results, 'idcategory', 'class="inputbox" size="1"', 'idcategory', 'name', $value);

		echo $lists['catid']  ;





		?></td>



	</tr>





</table>

</fieldset>

</div>





<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idroom" value="<?php echo $this->room->idroom; ?>" />

<input type="hidden" name="task" value="" /> <input type="hidden"

	name="controller" value="roomedit" /></form>







