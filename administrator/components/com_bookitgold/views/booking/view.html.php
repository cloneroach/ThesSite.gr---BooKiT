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



jimport( 'joomla.application.component.view' );

class BookitgoldViewBooking extends JView

{

	/**

	 * This is the default method called from controller display()

	 * Simply calls the getData() method of the models/booking.php

	 * @see admin/views/BooKitGold/BookitgoldViewBooKitGold#display($tpl)

	 */

	function display($tpl = null)

	{

		JToolBarHelper::title( JText::_( 'Bookings Manager' ), 'generic.png' );



		JToolBarHelper::editListX();

		JToolBarHelper::addNewX();

		//JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );

		JToolBarHelper::deleteList();

		JToolBarHelper::cancel();

		global  $option;



		// Get data from the model

		$booking =& $this->get( 'Data');

		$pagination =& $this->get( 'Pagination');

		$this->assignRef( 'booking', $booking );

		$this->assignRef( 'pagination', $pagination );

		/* Call the state object */

		$state =& $this->get( 'state' );

		$lists = array();



		$app =& JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest('com_bookitgold.filter_order','filter_order', 'today');

		$filter_order_Dir = $app->getUserStateFromRequest('com_bookitgold.filter_order_Dir','filter_order_Dir', 'ASC');

		// set the table order values

		$lists['order_Dir'] = $filter_order_Dir;

		$lists['order'] = $filter_order;

		

		$lists['search'] = $state->get( 'search' );

		$this->assignRef( 'lists', $lists );





		parent::display($tpl);





	}



}

