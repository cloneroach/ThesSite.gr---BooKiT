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

	$editor =& JFactory::getEditor();

	 ?>



<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

<fieldset class="adminform"><legend><?php echo JText::_( 'Category Basics' ); ?></legend>

<table class="admintable">

	<tr>



		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Category Name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="name" id="name"

			size="32" maxlength="250" value="<?php echo $this->category->name;?>" />

		</td>

	</tr>

	<tr>

		<td width="30" align="right" class="key"><label for="nguests"> <?php echo JText::_( 'No. of Adults (12+)' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="nguests" id="nguests"

			size="32" maxlength="250"

			value="<?php echo $this->category->nguests;?>" /></td>

	</tr>

	<tr>

		<td width="30" align="right" class="key"><label for="nchilds"> <?php echo JText::_( 'No. of Children (0-5)' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="nchilds" id="nchilds"

			size="32" maxlength="250"

			value="<?php echo $this->category->nchilds;?>" /></td>

	</tr>

	<tr>

		<td width="30" align="right" class="key"><label for="lchilds"> <?php echo JText::_( 'No. of Children (6-12)' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="lchilds" id="lchilds"

			size="32" maxlength="250"

			value="<?php echo $this->category->lchilds;?>" /></td>

	</tr>

	<tr>

		<td width="30" align="right" class="key"><label for="cost"> <?php echo JText::_( 'Cost per night' ); ?>:

		</label></td>

		<td><input class="text_area" type="text" name="cost" id="cost"

			size="32" maxlength="250" title="<?php echo JText::_('This is an average price. You can define rates per period in the availability menu.');?>"

			value="<?php echo $this->category->cost;?>" /></td>

	</tr>



</table>

</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_( 'Category Extra' ); ?></legend>

<table class="admintable">

	

	 <tr>

			<td width="100" align="right" class="key"><label for="description"> <?php echo JText::_( 'Description' ); ?>:

		</label></td>

			<!-- display() presents a rich text editor (joomla editor fck, etc) or a text area.

            If rich text editors are not desired. 1st element is the form field name, 2nd is the

            value for the field, width, height, columns no and rows no. Last two are applicable

            for Text Areas -->

            <td><?php echo $editor->display( 'description', $this->category->description , '200', '150', '40', '5' ) ; ?></td>

		</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="url"> <?php echo JText::_( 'Image' ); ?>:

		</label></td>



		<td><?php 



		$javascript			= 'onchange="changeDisplayImage();"';

		$directory			= '/images/bookit/images';

		$lists['url']	= JHTML::_('list.images',  'url', $this->category->url, $javascript, $directory, "bmp|gif|jpg|png|swf" );

		echo $lists['url'];

		?></td>



	</tr>



	<tr>

		<td valign="top" class="key"><?php echo JText::_( 'Banner Image' ); ?>:

		</td>

		<td valign="top"><?php

		if (preg_match("#gif|jpg|png#i",$this->category->url)) {

			?> <img src="../images/bookit/images/<?php echo $this->category->url; ?>"

			name="imagelib" /> <?php

		} else {

			?> <img src="../images/blank.png" name="imagelib" /> <?php

		}

		?></td>

	</tr>

	<tr>

		<td width="100" align="right" class="key"><label for="facilities"> <?php echo JText::_( 'Facilities' ); ?>:

		</label></td>

		<td><textarea class="inputbox" cols="70" rows="8" name="facilities"

			id="facilities"><?php echo $this->category->facilities;?></textarea></td>

	</tr>

		

</table>

</fieldset>

</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idcategory"

	value="<?php echo $this->category->idcategory; ?>" /> <input

	type="hidden" name="task" value="" /> <input type="hidden"

	name="controller" value="roomcategoryedit" /></form>



<script language="javascript" type="text/javascript">



		function changeDisplayImage() {

			if (document.adminForm.url.value !='') {

				document.adminForm.imagelib.src='../images/bookit/images/' + document.adminForm.url.value;

			} else {

				document.adminForm.imagelib.src='../images/blank.png';

			}

		}

</script>

