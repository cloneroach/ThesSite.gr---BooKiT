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



/**

 * Hello View for Hello World Component

 *

 * @package    Joomla.Tutorials

 * @subpackage Components

 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4

 * @license		GNU/GPL

 */



// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view' );



/**

 * BooKitGold View

 *

 */

class BookitgoldViewBookitgold extends JView

{

	/**

	 * display method of BooKitGold view

	 * @return void

	 **/

	function display($tpl = null)

	{

		

		$data =& $this->get('Data');

		$roomsImagePath = JPATH_COMPONENT_ADMINISTRATOR.DS.'resources'.DS.'rooms.png'.DS;



		JToolBarHelper::title(   JText::_( 'BooKitGold' ).': <small><small> ' .JText::_( 'Easy Hotel Management' ).' </small></small>' );



		JToolBarHelper::preferences( 'com_bookitgold','500' );

		$this->assignRef('data', $data);

		$this->assignRef('roomsImagePath', $roomsImagePath);

		parent::display($tpl);

	}

}



