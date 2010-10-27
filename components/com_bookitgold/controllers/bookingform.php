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

class BookitgoldControllerBookingform extends BookitgoldController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask( 'bookingdefault'  , 	'bookingdefault' );
	}

	function bookingdefault()
	{
		JRequest::setVar( 'view', 'bookingform' );
		JRequest::setVar( 'layout', 'default'  );
		parent::display();
	}
	
	function couponvalidation (){
		
		$code = JRequest::getVar('code');
		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');
	
		$idcategory = JRequest::getVar('idcategory');

		$db =& JFactory::getDBO();
		$query =  "SELECT * FROM #__bookitcoupon WHERE code='".$code."'";
		$db->setQuery( $query );
		$coupon_res = $db->loadRow();
		
		$coupons = new JSimpleXMLElement('coupons', array('id' => 0));
		if (count($coupon_res)>0)
		{
			$new_valid_from = date("Y-m-d", strtotime($valid_from));
			$new_valid_to = date("Y-m-d", strtotime($valid_to));
			$fromDateTS = strtotime($new_valid_from);
			$toDateTS = strtotime($new_valid_to);
			$discount=0;
			$nights=round( ($toDateTS-$fromDateTS )/86400) ;
	
			for ($currentDateTS = $fromDateTS, $cnt=1; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)
			{
				$currentDateStr = date('Y-m-d',$currentDateTS);
				//Get the day price, if it is defined in the availability table use that price,
				//otherwise get the price of the room  category
				$query =  "SELECT price FROM #__bookitavailability WHERE idcategory='".$idcategory."' AND today='".$currentDateStr."'";
				$db->setQuery( $query );
				$day_price = $db->loadResult();

				if ($day_price==0)
				{
					$query =  "SELECT cost FROM #__bookitcategory WHERE idcategory='".$idcategory."'";
					$db->setQuery( $query );
					$day_price = $db->loadResult();
				}

				$couponFromDateTS = strtotime($coupon_res['6']);
				$couponToDateTS = strtotime($coupon_res['7']);

				if ($coupon_res['4']==1 && $coupon_res['5']==0 &&
				$currentDateTS>=$couponFromDateTS && $currentDateTS<=$couponToDateTS)
				{
					

					//Get Fix or Percentage value
					if ($coupon_res['2']>0)
					{
						$coupon_value = $coupon_res['2'];
						if ($coupon_res['9']>0)
						{
							if ($nights%$cnt==0)
							$discount += $coupon_value;
						}
						else
						{
							$discount += $coupon_value/$nights;
						}
					}
					else if ($coupon_res['3']>0)
					{
						$coupon_value = $coupon_res['3'];
						if ($coupon_res['9']>0)
						{
							if ($cnt%$coupon_res['9']==0)
							{

								$discount += $coupon_value/100*$day_price;

							}
						}
						else
						{

							$discount += $coupon_value/100*$day_price;

						}
							
					}

				}

			}
			
			$discount = round($discount,"2");
		 	$coupons->addAttribute('new_price',$discount);	
			echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
			echo $coupons->toString();
			$document =& JFactory::getDocument();
			$document->setMimeEncoding('text/xml');

		}
		else
		{
			$coupons->addAttribute('new_price',-1);	
			echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
			echo $coupons->toString();
			$document =& JFactory::getDocument();
			$document->setMimeEncoding('text/xml');

		}
	}
}



