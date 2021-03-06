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

	jimport( 'joomla.application.component.view');

 

	class BookitgoldViewFinalform extends JView

	{

		function display($tpl = null)

		{

			$postedData = JRequest::get( 'post' );

			$idcategory = JRequest::getVar('idcategory');

			$valid_from = JRequest::getVar('valid_from');

			$valid_to = JRequest::getVar('valid_to');

			$nguests = JRequest::getVar('nguests');

			$nchilds = JRequest::getVar('nchilds');
			
			$lchilds = JRequest::getVar('lchilds'); // Thessite
			$arrival_time = JRequest::getVar('arrival_time'); // Thessite

			$price = JRequest::getVar('price');

			$deposit = JRequest::getVar('deposit');

			$extra_ids = JRequest::getVar('extra_ids');

			$coupon_code_valid = JRequest::getVar('coupon_code_valid');

			$preferences = JRequest::getVar('preferences');

			$db =& JFactory::getDBO();

			$query =  "SELECT name FROM #__bookitcategory WHERE idcategory='".$idcategory."'";

			$db->setQuery( $query );

			$category_name = $db->loadResult();

			$params =& JComponentHelper::getParams('com_bookitgold');

			$currency = $params->get('currency','EUR');

			

			$this->assignRef( 'idcategory', $idcategory );

			$this->assignRef( 'category_name', $category_name );

			$this->assignRef( 'valid_from', $valid_from );

			$this->assignRef( 'valid_to', $valid_to );

			$this->assignRef( 'nguests', $nguests );

			$this->assignRef( 'nchilds', $nchilds );
			
			$this->assignRef( 'lchilds' , $lchilds ); // Thessite
			$this->assignRef( 'arrival_time' , $arrival_time ); // Thessite

			$this->assignRef( 'price', $price );

			$this->assignRef( 'currency', $currency );

			$this->assignRef( 'deposit', $deposit );

			$this->assignRef( 'extra_ids', $extra_ids );

			$this->assignRef( 'coupon_code', $coupon_code_valid );

			$this->assignRef( 'preferences', $preferences );

		

			parent::display($tpl);

		}

		

	}

