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

<fieldset class="adminform"><legend><?php echo JText::_( 'Extra Basics' ); ?></legend>

<table class="admintable">

	

	

	<tr>



		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Extra name' ); ?>:

		</label></td>



		<td><input class="text_area" type="text" name="name" id="name"

			size="20" maxlength="250" value="<?php echo $this->extra->name;?>" />

		</td>

	</tr>



	  <tr>

		<td width="30" align="right" class="key"><label for="cost"> <?php echo JText::_( 'Mandatory?' ); ?>:

		</label></td>

		<td><input type="radio" name="extra_type" id="extra_type" value="0"

		<?php  echo 'class="required"';  if ($this->extra->extra_type==0||$this->extra->extra_type==""){ echo "checked";}?>>

		<?php echo JText::_('No')?> <input type="radio"

			name="extra_type" id="extra_type" value="1"

			<?php echo 'class="required"'; if ($this->extra->extra_type==1){ echo "checked";}?>>

			<?php echo JText::_('Yes')?></td>

	</tr> 

	<tr>



		<td width="100" align="right" class="key"><label for="name"> <?php echo JText::_( 'Value' ); ?>:

		</label> 

		<span style="font-size: 10px; font-style: italic;"><br/>

			<?php echo JText::_('Keep the value to zero if you want this extra to appear in the Rooms Preferences section');?> <br />

			</span></td>



		<td><?php echo JText::_('fixed:');?><input class="text_area"

			type="text" name="value_fix" id="value_fix" size="16" maxlength="50"

			value="<?php echo $this->extra->value_fix;?>" /> <?php echo JText::_('or percentage:');?><input

			class="text_area" type="text" name="value_percent" id="value_percent"

			size="16" maxlength="50"

			value="<?php echo $this->extra->value_percent;?>" title="<?php echo JText::_("Extra value is computed as a percentage of booking cost, excluding special offers");?>" />

			

			</td>

			

	</tr>

	<tr>

		<td width="30" align="right" class="key"><label for="cost"> <?php echo JText::_( 'Apply extra per' ); ?>:

		</label></td>

		<td>

			<input type="radio" name="value_type" value="1"

			<?php echo 'class="required"'; if ($this->extra->value_type==1||$this->extra->value_type==""){ echo "checked";}?>/>

			<?php echo JText::_('Booking');?> 

			<span style="font-size: 10px; font-style: italic;"><?php echo JText::_('- Adds the specified amount to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="2"

			<?php echo 'class="required"'; if ($this->extra->value_type==2){ echo "checked";}?>/>

			<?php echo JText::_('Night');?>  

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of nights, to the total booking cost.');?> <br />

			</span>

			

				

			<input type="radio" name="value_type" value="3" 

			<?php echo 'class="required"'; if ($this->extra->value_type==3){ echo "checked";}?>/>

			<?php echo JText::_('Guest');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of guests, to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="4" 

			<?php echo 'class="required"'; if ($this->extra->value_type==4){ echo "checked";}?>/>

			<?php echo JText::_('Adult');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of adults, to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="5" 

			<?php echo 'class="required"'; if ($this->extra->value_type==5){ echo "checked";}?>/>

			<?php echo JText::_('Child');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of children, to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="8" 

			<?php echo 'class="required"'; if ($this->extra->value_type==8){ echo "checked";}?>/>

			<?php echo JText::_('Quantity');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the given quantity (upon booking), to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="10" 

			<?php echo 'class="required"'; if ($this->extra->value_type==10){ echo "checked";}?>/>

			<?php echo JText::_('Guest/Night');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of guests and the number of nights, to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="6" 

			<?php echo 'class="required"'; if ($this->extra->value_type==6){ echo "checked";}?>/>

			<?php echo JText::_('Adult/Night');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of adults and the number of nights, to the total booking cost.');?> <br />

			</span>

			

			<input type="radio" name="value_type" value="7" 

			<?php echo 'class="required"'; if ($this->extra->value_type==7){ echo "checked";}?>/>

			<?php echo JText::_('Child/Night');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the number of children and the number of nights, to the total booking cost.');?> <br />

			</span>



			

			<input type="radio" name="value_type" value="9" 

			<?php echo 'class="required"'; if ($this->extra->value_type==9){ echo "checked";}?>/>

			<?php echo JText::_('Quantity/Night');?> 

			<span style="font-size: 10px; font-style: italic;">

			<?php echo JText::_('- Adds the specified amount, multiplied by the given quantity and the number of nights, to the total booking cost.');?> <br />

			</span>

			

	

		

		</td>

	</tr>

		 <tr>

			<td width="100" align="right" class="key"><label for="description"> <?php echo JText::_( 'Description' ); ?>:

		</label></td>

            <td><?php echo $editor->display( 'description', $this->extra->description , '200', '150', '40', '5' ) ; ?></td>

		</tr>





</table>

</fieldset>



</div>



<div class="clr"></div>



<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="idextra"

	value="<?php echo $this->extra->idextra; ?>" /> <input type="hidden"

	name="task" value="" /> <input type="hidden" name="controller"

	value="roomextraedit" />

	</form>





