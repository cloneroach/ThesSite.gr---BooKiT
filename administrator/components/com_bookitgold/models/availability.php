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

jimport('joomla.application.component.model');

class BookitgoldModelAvailability extends JModel
{
	var $_data;
	var $_id;
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id=$cid[0];
		if ($id==0)
		{
			$cid = JRequest::getVar('cid', '', 'get');
			$id = $cid[0];
		}

			
		$query = ' SELECT * '
		. ' FROM #__bookitavailability WHERE idavailability='.$id;
		return $query;
	}

	/**
	 * Retrieves the bookings
	 * @return array Array of objects containing the data from the database
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__bookitavailability '.
					'  WHERE idavailability = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->idavailability = 0;
			$this->_data->idoffer = null;
			$this->_data->idbook = null;
			$this->_data->idroom = null;
			$this->_data->idcategory = null;
			$this->_data->today = null;
			$this->_data->price = null;
			$this->_data->price_deviation_1 = null;
			$this->_data->price_deviation_2 = null;
			$this->_data->availability = null;
		}


		return $this->_data;
	}
	/**
	 * Method to store a record
	 */
	function store()
	{
		$row =& $this->getTable();
		$data = JRequest::get( 'post' );
		$id = JRequest::getVar('idavailability');
		$idoffer = JRequest::getVar('idoffer');
		$idbook = null;
		$idroom = JRequest::getVar('idroom');
		$idcategory = JRequest::getVar('idcategory');
		$today = null;
		$valid_from = JRequest::getVar('valid_from');
		$valid_to = JRequest::getVar('valid_to');
		$price = JRequest::getVar('price');
		$price_deviation_1 = JRequest::getVar('price_deviation_1');
		$price_deviation_2 = JRequest::getVar('price_deviation_2');
		$availability = JRequest::getVar('availability');

		$db =& JFactory::getDBO();

		//Check if dates are in living range
		$query = "SELECT MAX(today) as max_date, MIN(today) as min_date FROM #__bookitavailability";
		$db->setQuery($query);
		$range = $db->loadObjectList();

		$max_date = date("Y-m-d", strtotime($range['0']->max_date));
		$min_date = date("Y-m-d", strtotime($range['0']->min_date));

		$res["id"]=$id;

		$new_valid_from = date("Y-m-d", strtotime($valid_from));
		$new_valid_to = date("Y-m-d", strtotime($valid_to));
		$setString='';

		if ($new_valid_to < $new_valid_from)
		{
			$res["code"]=-1;
			return $res;
		}
		else if ($new_valid_to>$max_date || $new_valid_from<$min_date)
		{
			$res["code"]=-3;
			return $res;
		}
		else if ($idroom>0)
		{
				
			$res["code"]=-4;
			return $res;
		}

		if ($price!="" && $price!=0)
		{
			if (!is_numeric($price))
			{
				$res["code"]=-1;
				return $res;

			}
			else if ($price<0)
			{
				$res["code"]=-1;
				return $res;
			}
			else
			$setString = 'price = '.$price;
		}
		if ($availability!= "" && ($availability==0 || $availability==2 || $availability==3))
		{
				
			if ($setString!="")
			$setString = $setString.', availability = '.$availability;
			else
			$setString = 'availability = '.$availability;
		}

		if ($idoffer!=0)
		{
			if ($idoffer==-1)
				$idoffer=0;
			if ($setString!="")
			$setString = $setString.', idoffer = '.$idoffer;
			else
			$setString = 'idoffer = '.$idoffer;
				
		}


		if ($setString=="")
		{
			$res["code"]=-2;
			return $res;
		}

		$fromDate = $new_valid_from;
		$toDate = $new_valid_to;


		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);

		$whereStringStd = '';
		$whereStringStd2 = '';

		//Room Selected
		if ($idroom!="" && $idroom>0)
		{
			$whereStringStd = 'idroom = '.$idroom;
		}
		//Category Selected
		else if ($idcategory!="" && $idcategory>0)
		{
			$query = "SELECT idroom FROM #__bookitroom WHERE idcategory='".$idcategory."'";
			$this->_db->setQuery( $query );
			$roomids = $this->_db->loadResultArray();

			if (count($roomids)==0)
			{
				$res["code"]=-2;
				return $res;
			}

			$whereStringStd = "(";
			for ($i=0; $i<count($roomids); $i++)
			{
				if ($i==0)
				{
					$whereStringStd2 = $whereStringStd2."idroom=".$roomids[$i];
				}
				else
				$whereStringStd2 =  $whereStringStd2." OR idroom=".$roomids[$i];
			}
			$whereStringStd2 =  $whereStringStd2.")";
			$whereStringStd = $whereStringStd.$whereStringStd2;
		}
		else //Nothing Selected
		{
			$whereStringStd='';
		}

		for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
		{
			$currentDateStr = date("Y-m-d",$currentDateTS);
			if ($availability!= "")
			{				
				$query = "SELECT availability FROM #__bookitavailability WHERE today='".$currentDateStr."'";
				$this->_db->setQuery( $query );
				$availabilities = $this->_db->loadResultArray();
				if (in_array('1',$availabilities))
				{
					$res["code"]=-5;
					return $res;
				}
			}
						
			
			if ($whereStringStd!='')
			$whereString= $whereStringStd." AND today='".$currentDateStr."'";
			else
			$whereString= "today='".$currentDateStr."'";

			$query = ' UPDATE #__bookitavailability SET '.$setString.
					'  WHERE '.$whereString;

			$this->_db->setQuery( $query );
			$result = $this->_db->query();

		
			if (!$result)
			{
				$res["code"]=-2;
				return $res;
			}


		}

	}


}