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

 * 

 * Responsible for responding to user actions (i.e, page requests)

 * Determines what request is being made by the user and responds

 * appropriately by triggering methods in the model which modify

 * the data. Afterwards the model is passed into the view which

 * displays the data.

 *

 */



class BookitgoldControllerCouponedit extends BookitgoldController

{



	function __construct()

	{

		$this->registerTask( 'add'  , 	'edit' );

		$this->registerTask( 'apply'  , 'save' );





		parent::__construct();



	}



	function apply ()

	{

		$model = $this->getModel('couponedit');

		$res = $model->store();

		

		$link = 'index.php?option=com_bookitgold&controller=coupon&task=edit&cid[]='.$res["id"];

		if ($res["code"]==0) {



			$msg = JText::_( 'Coupon Saved' )."!";

			$this->setRedirect($link, $msg);

		} else if ($res["code"]==-2) {

			$msg = JText::_( 'Error Saving Coupon' );

			// Check the table in so it can be edited.... we are done with it anyway

				

			$this->setRedirect($link, $msg, 'error');

		}else  {

			$msg = JText::_( 'Please complete all basic fields. From date must be before To date' );



				

			$this->setRedirect($link, $msg, 'notice');

		}

	}



	/**

	 * save a record (and redirect to main page)

	 * @return void

	 */

	function save()

	{



		$model = $this->getModel('couponedit');

		$res = $model->store();



		if ($res["code"]==0) {



			$msg = JText::_( 'Coupon Saved' );

			// Check the table in so it can be edited.... we are done with it anyway

			$link = 'index.php?option=com_bookitgold&controller=coupon&task=listing';

			$this->setRedirect($link, $msg);

		} else if ($res["code"]==-2) {

			$msg = JText::_( 'Error Saving Coupon' );

			// Check the table in so it can be edited.... we are done with it anyway

			$link = 'index.php?option=com_bookitgold&controller=coupon&task=listing';

			$this->setRedirect($link, $msg, 'error');

		}else  {

			$msg = JText::_( 'Please complete all basic fields. From date must be before To date'  );



			$link = 'index.php?option=com_bookitgold&controller=coupon&task=edit&cid[]='.$res["id"];

			$this->setRedirect($link, $msg, 'notice');

		}



	}





	function cancel()

	{



		$link = 'index.php?option=com_bookitgold&controller=coupon&task=listing';

		$this->setRedirect($link);

	}



	function edit()

	{

		JRequest::setVar( 'view', 'coupon' );

		JRequest::setVar( 'layout', 'default'  );     // <-- The default form is named here, but in

		JRequest::setVar('hidemainmenu', 1);

		// some complex views, multiple layouts might

		// be needed.



		parent::display();

	}





}

