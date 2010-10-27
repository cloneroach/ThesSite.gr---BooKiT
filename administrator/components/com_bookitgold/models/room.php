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

class BookitgoldModelRoom extends JModel
{
	var $_data;
	var $_total = null;

	var $_pagination = null;


	function __construct()
	{
		parent::__construct();

		global $option;
		$mainframe = JFactory::getApplication();
		$view = $this->getName();
		$layout = JRequest::getCmd('layout','default');
		

		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}


	function _buildContentOrderBy()
	{
		$app =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		
		$orders = array('idroom', 'name', 'idcategory');
		$order = $app->getUserStateFromRequest('com_bookitgold.filter_order', 'filter_order', 'name');
		$order_Dir = strtoupper ($app->getUserStateFromRequest('com_bookitgold.filter_order_Dir', 'filter_order_Dir','ASC'));
		// validate the order direction, must be ASC or DESC
		if ($order_Dir != 'ASC' && $order_Dir != 'DESC')
		{
			$order_Dir = 'ASC';
		}
		// if order column is unknown use the default
		if (!in_array($order, $orders))
		{
			$order = 'name';	
		}
		
		return ' ORDER BY ' . $db->nameQuote($order) ." $order_Dir ";
	}

	function _buildQuery()
	{
		$query = ' SELECT * '
		. ' FROM #__bookitroom '.$this->_buildQueryWhere(). ' ' .
		$this->_buildContentOrderBy();
		return $query;

	}
	function _buildQueryWhere() {
		// get the application
		$app =& JFactory::getApplication();

		// get the free text search filter
		$search = $app->getUserStateFromRequest('com_bookitgold.search',
'search', '', 'string');
		$search = JString::strtolower($search);
		// prepare to build WHERE clause as an array
		$where = array();
		$db =& JFactory::getDBO();
		if (strlen($search)) {
			// make string safe for searching
			$search = '%' . $db->getEscaped($search, true). '%';
			$search = $db->Quote($search, false);
			$field = $db->nameQuote('name');
			// add search to $where array
			$where[] = "LOWER($field) LIKE $search";
		}
		if (count($where)) {
			// building from array
			$where = ' WHERE '. implode(' AND ', $where);
		} else {
			// array is empty... nothing to do!
			$where = '';
		}
		// all done, send the result back


		return $where;
	}
	function _buildCopyQuery($id)
	{
			
		$db =& JFactory::getDBO();
		$query = ' SELECT * '
		. ' FROM #__bookitroom WHERE idroom='.$id
		;
		$db->setQuery($query);
		$row = $db->loadRow();

		$data =new stdClass();
		$data->idroom = null;
		$data->idcategory = $row['1'];
		$data->name = 'Copy of '.$row['2'];
		$data->description = $row['3'];
		$data->url = $row['4'];

		$db->insertObject( '#__bookitroom', $data, 'idroom' );

		//Create entries in availability table
		$query = "SELECT MAX(idroom) as max_id FROM #__bookitroom";
		$db->setQuery($query);
		$max_id = $db->loadResult();

		$now = strtotime(date("Y-m-d"));
		$future = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +180 month");
		$daysLeft = floor (($future - $now) / (60 * 60 * 24));
		$yesterday =  date("Y-m-d", strtotime("now"));

		for ($i=0; $i<=$daysLeft; $i++ )
		{
			$a_data =new stdClass();
			$a_data->idavailability = null;
			$a_data->idoffer = null;
			$a_data->idbook = null;
			$a_data->idroom = $max_id;
			$a_data->today =  date("Y-m-d", strtotime( "+" .$i." day" , strtotime ( "now" )));
			$yesterday = $a_data->today;
			$a_data->price = 0;
			$a_data->price_deviation_1=null;
			$a_data->price_deviation_2=null;
			$a_data->availability=0;
			$a_data->idcategory = $row['1'];
			$db = JFactory::getDBO();
			$db->insertObject( '#__bookitavailability', $a_data, 'idavailability');
		}

		return true;
	}


	function _buildCategoriesQuery()
	{
		$query = ' SELECT * '
		. ' FROM #__bookitcategory' ;
		return $query;
	}
	function getRoomCategories()
	{
			
		// Lets load the data if it doesn't already exist
		if (empty( $this->_categories ))
		{

			$query = $this->_buildCategoriesQuery();
			$this->_categories = $this->_getList( $query );
		}

		return $this->_categories;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();
		$db =& JFactory::getDBO();
		foreach($cids as $cid) {
			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				return false;
			}
			//Delete records in availability for this room
			$query = "DELETE FROM #__bookitavailability WHERE idroom =".$cid;
			$db->setQuery($query);
			$result = $db->query();
		}
		return true;
	}

	function copy ()
	{
	 $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );



	 	if (!$this->_buildCopyQuery($cids['0']))
	 	{
	 		return false;
	 			
	 	}

	 return true;
	}


	function getCategoryName ($id)
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT DISTINCT name '
		. ' FROM #__bookitcategory WHERE idcategory='.$id
		;
		$db->setQuery($query);
		$res = $db->loadResult();
		return res;
	}

	/**
	 * 	PAGINATION
	 */
	function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$limitstart = $this->getState('limitstart');
			$limit = $this->getState('limit');
			$this->_data = $this->_getList($query, $limitstart, $limit);
		}
		return $this->_data;
	}
	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			// import the pagination library
			jimport('joomla.html.pagination');
			// prepare the pagination values
			$total = $this->getTotal();
			$limitstart = $this->getState('limitstart');
			$limit = $this->getState('limit');
			// create the pagination object
			$this->_pagination = new JPagination($total, $limitstart,$limit);
		}
		return $this->_pagination;
	}

}