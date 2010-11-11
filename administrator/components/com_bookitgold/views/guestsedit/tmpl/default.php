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

<fieldset class="adminform"><legend><?php echo JText::_( 'Guest Basics' ); ?></legend>

<table class="admintable">

	

	<tr>



		<td width="100" align="right" class="key"><label for="title"> <?php echo JText::_( 'Title' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="title" id="title"

			size="30" maxlength="250" value="<?php echo $this->guests->title;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'First name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="name" id="name"

			size="30" maxlength="250" value="<?php echo $this->guests->name;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="surname"> <?php echo JText::_( 'Last name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="surname" id="surname"

			size="30" maxlength="250" value="<?php echo $this->guests->surname;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="email"> <?php echo JText::_( 'Email' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="email" id="email"

			size="30" maxlength="250" value="<?php echo $this->guests->email;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="phone"> <?php echo JText::_( 'Phone' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="phone" id="phone"

			size="30" maxlength="250" value="<?php echo $this->guests->phone;?>" />

		</td>

	</tr>
	<tr>



		<td width="100" align="right" class="key"><label for="alt_phone"> <?php echo JText::_( 'Alternative Phone' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="alt_phone" id="alt_phone"

			size="30" maxlength="250" value="<?php echo $this->guests->alt_phone;?>" />

		</td>

	</tr>








</table>

</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_( 'Guest Extra' ); ?></legend>

<table class="admintable">

	<tr>



		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Address' ); ?>:

		</label></td>

		<td><textarea class="inputbox" cols="25" rows="4" name="addr"

			id="addr"><?php echo $this->guests->addr;?></textarea></td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="city"> <?php echo JText::_( 'City' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="city" id="city"

			size="30" maxlength="250" value="<?php echo $this->guests->city;?>" />

		</td>

	</tr>

	<tr>



		<td width="100" align="right" class="key"><label for="country"> <?php echo JText::_( 'Country' ); ?>:

		</label></td>



		<td><?php 



		$lists=array();

		$db =& JFactory::getDBO();

		$sql = "SELECT idcountry, name FROM #__bookitcountry";

		$db->setQuery($sql);

		

		$value = isset($this->guests->idcountry)?$this->guests->idcountry:'0';



			

		$results[] = JHTML::_('select.option', 0, '-Select an Item-', 'idcountry', 'name' );

		$results = array_merge( $results, $db->loadObjectList() );

			



		$lists['catid']  = JHTML::_('select.genericList', $results, 'idcountry', 'class="inputbox" size="1"', 'idcountry', 'name', $value);

		echo $lists['catid']  ;

		



		?></td>



	</tr>



</table>

</fieldset>



</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idguests"

	value="<?php echo $this->guests->idguests; ?>" /> <input type="hidden"

	name="task" value="" /> <input type="hidden" name="controller"

	value="guestsedit" /></form>

