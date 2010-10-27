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



defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class BookitgoldControllerBookingedit extends BookitgoldController

{



	function __construct()

	{

		$this->registerTask( 'add'  , 	'edit' );

		$this->registerTask( 'apply'  , 'save' );

		parent::__construct();

	}



	function apply ()

	{

		$model = $this->getModel('bookingedit');

		$res = $model->store();

		$link = 'index.php?option=com_bookitgold&controller=booking&task=edit&cid[]='.$res["id"];

		if ($res["code"]==0) {



			$msg = JText::_( 'Booking Saved' )."!";

			$this->setRedirect($link, $msg);

		} else if ($res["code"]==-2) {

			$msg = JText::_( 'Error Saving Booking' );

			// Check the table in so it can be edited.... we are done with it anyway



			$this->setRedirect($link, $msg, 'error');

		}

		else if ($res["code"]==-3) {

			$msg = JText::_( 'Error Saving Booking. This room is already booked for some or all of the selected days' );

			// Check the table in so it can be edited.... we are done with it anyway



			$this->setRedirect($link, $msg, 'notice');

		}

	else if ($res["code"]==-4) {

			$msg = JText::_( 'Error Saving Booking. The hotel is fully booked for the selected category, on some or all of the selected dates' );

			$this->setRedirect($link, $msg, 'notice');

		}



		else  {

			$msg = JText::_( 'Please enter all required fields (Valid dates, guest, guests number and total price > 0)' );

			$this->setRedirect($link, $msg, 'notice');

		}

	}



	/**

	 * save a record (and redirect to main page)

	 * @return void

	 */

	function save()

	{

		$model = $this->getModel('bookingedit');

		$res = $model->store();

		if ($res["code"]==0) {



			$msg = JText::_( 'Booking Saved' )."!";

			// Check the table in so it can be edited.... we are done with it anyway

			$link = 'index.php?option=com_bookitgold&controller=booking&task=listing';

			$this->setRedirect($link, $msg);

		} else if ($res["code"]==-2) {

			$msg = JText::_( 'Error Saving Booking' );

			// Check the table in so it can be edited.... we are done with it anyway

			$link = 'index.php?option=com_bookitgold&controller=booking&task=listing';

			$this->setRedirect($link, $msg, 'error');

		}

		else if ($res["code"]==-3) {

			$msg = JText::_( 'Error Saving Booking. This room is already booked for some or all of the selected days' );

			$link = 'index.php?option=com_bookitgold&controller=booking&task=edit&cid[]='.$res["id"];



			$this->setRedirect($link, $msg, 'notice');

		}

		else if ($res["code"]==-4) {

			$msg = JText::_( 'Error Saving Booking. The hotel is fully booked for the selected category, on some or all of the selected dates' );

			$link = 'index.php?option=com_bookitgold&controller=booking&task=edit&cid[]='.$res["id"];



			$this->setRedirect($link, $msg, 'notice');

		}



		else  {

			$msg = JText::_( 'Please enter all required fields (Valid dates, guest, guests number and total price > 0)'  );



			$link = 'index.php?option=com_bookitgold&controller=booking&task=edit&cid[]='.$res["id"];

			$this->setRedirect($link, $msg, 'notice');

		}



	}



	function cancel()

	{

		$link = 'index.php?option=com_bookitgold&controller=booking&task=listing';

		$this->setRedirect($link);

	}



	function edit()

	{

		JRequest::setVar( 'view', 'booking' );

		JRequest::setVar( 'layout', 'default'  );

		JRequest::setVar('hidemainmenu', 1);

		// some complex views, multiple layouts might

		// be needed.



		parent::display();

	}



	

}

