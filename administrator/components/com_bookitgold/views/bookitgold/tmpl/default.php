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

//echo "admin/views/BooKitGold/tmpl/default.php"; ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

<fieldset class="adminform"><legend><?php echo JText::_( 'Main Menu' ); ?></legend>



<table class="admintable" align="left" width="60%" border="0">

	<tr height="64px">

		<td valign="top" align="left"><a

			href="index.php?option=com_bookitgold&controller=roomcategory&task=listing">

			<?php $text = JText::_( 'Room Categories' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/roomcategories.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			</a></td>

		<td valign="top" align="center"><a

			href="index.php?option=com_bookitgold&controller=room&task=listing">

			<?php $text = JText::_( 'Rooms' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/rooms.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			 </a></td>

		<td valign="top"  align="right"><a

			href="index.php?option=com_bookitgold&controller=roomextra&task=listing">

			<?php $text = JText::_( 'Extra Services' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/roomextra.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			</a></td>

	</tr>

	<tr height="46px">

		<td valign="top" align="left" ><a

			href="index.php?option=com_bookitgold&controller=roomcategory&task=listing">

			<?php $text = JText::_( 'Room Categories' ); ?> 

			<?php echo $text; ?> </a></td>

		<td valign="top" align="center"><a

			href="index.php?option=com_bookitgold&controller=room&task=listing">

			<?php $text = JText::_( 'Rooms' ); ?> 

			<?php echo $text; ?> </a></td>

		<td valign="top"  align="right"><a

			href="index.php?option=com_bookitgold&controller=roomextra&task=listing">

			<?php $text = JText::_( 'Extra Services' ); ?> 

			<?php echo $text; ?> </a></td>

	</tr>



	<tr height="64px">

		<td  valign="top"  align="left"><a

			href="index.php?option=com_bookitgold&controller=guests&task=listing">

			<?php $text = JText::_( 'Guests' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/users.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			 </a></td>

		<td  valign="top"  align="center"><a

			href="index.php?option=com_bookitgold&controller=booking&task=listing">

			<?php $text = JText::_( 'Bookings' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/bookings.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			 </a></td>



		<td valign="top"  align="right"><a

			href="index.php?option=com_bookitgold&controller=availability&task=edit">

			<?php $text = JText::_( 'Settings & Availability' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/calendar.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			</a></td>

	</tr>

	<tr height="46px">

		<td  valign="top"  align="left"><a

			href="index.php?option=com_bookitgold&controller=guests&task=listing">

			<?php echo JText::_( 'Guests' ); ?> 

			 </a></td>

		<td  valign="top"  align="center"><a

			href="index.php?option=com_bookitgold&controller=booking&task=listing">

			<?php echo JText::_( 'Bookings' ); ?> 

			 </a></td>



		<td valign="top"  align="right"><a

			href="index.php?option=com_bookitgold&controller=availability&task=edit">

			<?php echo JText::_( 'Settings & Availability' ); ?> 

			</a></td>

	</tr>

	

	<tr height="64px">

		<td  valign="top"  align="left"><a

			href="index.php?option=com_bookitgold&controller=coupon&task=listing">

			<?php $text = JText::_( 'Coupons' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/coupon.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			</a></td>



		<td valign="top"  align="center"><a

			href="index.php?option=com_bookitgold&controller=specialoffer&task=listing">

			<?php $text = JText::_( 'Special Offers' ); ?> <img

			src="<?php echo JURI::root(); ?>/components/com_bookitgold/images/offers.png"

			alt="<?php echo $text; ?>" align="middle" name="image" border="0" />

			</a>

		</td>

	</tr>

	<tr height="46px">

		<td  valign="top"  align="left"><a

			href="index.php?option=com_bookitgold&controller=coupon&task=listing">

			<?php $text = JText::_( 'Coupons' ); ?> 

			<?php echo "<br/>".$text; ?></a></td>



		<td valign="top"  align="center"><a

			href="index.php?option=com_bookitgold&controller=specialoffer&task=listing">

			<?php $text = JText::_( 'Special Offers' ); ?> 

			<?php echo "<br/>".$text; ?> </a>

		</td>

	</tr>

</table>

</fieldset>

</div>





<input type="hidden" name="option" value="com_bookitgold" /> <input

	type="hidden" name="id" value="<?php echo $this->data->id; ?>" /> <input

	type="hidden" name="task" value="" /> <input type="hidden"

	name="controller" value="bookitgold" /></form>

