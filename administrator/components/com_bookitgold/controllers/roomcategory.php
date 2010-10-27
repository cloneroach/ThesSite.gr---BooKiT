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



/**

 * BooKitGold Component Controller

 * Responsible for responding to user actions (i.e, page requests)

 * Determines what request is being made by the user and responds

 * appropriately by triggering methods in the model which modify

 * the data. Afterwards the model is passed into the view which

 * displays the data.

 *

 */



class BookitgoldControllerRoomcategory extends BookitgoldController

{



	function __construct()

	{

		parent::__construct();



		// Register Extra tasks

		$this->registerTask( 'add'  , 	'edit' );

		$this->registerTask( 'list'  , 	'listing' );





	}





	function listing()

	{

		JRequest::setVar( 'view', 'roomcategory' );

		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in

		// some complex views, multiple layouts might

		// be needed.

		parent::display();



	}

	function edit()  // <-- edit, add, delete

	{





		JRequest::setVar( 'view', 'roomcategoryedit' );

		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in

		JRequest::setVar('hidemainmenu', 1);

		// some complex views, multiple layouts might

		// be needed.

		parent::display();

	}





	function remove()

	{

		$model = $this->getModel('roomcategory');

		if(!$model->delete()) {

			$msg = JText::_( 'Error: One or More Room Categories Could not be Deleted' );

		} else {

			$msg = JText::_( 'Room Category(ies) Deleted' );

		}



		$this->setRedirect( 'index.php?option=com_bookitgold&controller=roomcategory&task=listing', $msg );

	}



	function copy()

	{



		$model = $this->getModel('roomcategory');

		if(!$model->copy()) {

			$msg = JText::_( 'Error: One or More Room Categories Could not be Copied' );

		} else {

			$msg = JText::_( 'Category(ies) Copied' );

		}



		$this->setRedirect( 'index.php?option=com_bookitgold&controller=roomcategory&task=listing', $msg );

	}



}









