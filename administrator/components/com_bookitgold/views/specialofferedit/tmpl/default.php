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

<fieldset class="adminform"><legend><?php echo JText::_( 'Special Offer Basics' ); ?></legend>

<table class="admintable">

	<tr>

		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="name" id="name"

			size="30" maxlength="250" value="<?php echo $this->offer->name;?>" />

		</td>

	</tr>

	

	<tr>



		<td width="100" align="right" class="key"><label for="value"> <?php echo JText::_( 'Value' ); ?>:

		</label></td>



		<td><?php echo JText::_('fixed:');?><input class="text_area"

			type="text" name="value_fix" id="value_fix" size="16" maxlength="50"

			value="<?php echo $this->offer->value_fix;?>" /> <?php echo JText::_('or percentage:');?><input

			class="text_area" type="text" name="value_percent" id="value_percent"

			size="16" maxlength="50"

			value="<?php echo $this->offer->value_percent;?>" /></td>

	</tr>

	

</table>

</fieldset>

</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idoffer"

	value="<?php echo $this->offer->idoffer; ?>" /> <input type="hidden"

	name="task" value="" /> <input type="hidden" name="controller"

	value="specialofferedit" /></form>

