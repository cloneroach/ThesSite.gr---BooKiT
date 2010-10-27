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

class BookitgoldModelGuests extends JModel
{
	var $_data;
	var $_total = null;

	var $_pagination = null;


	function __construct()
	{
		parent::__construct();

		global  $option;
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
		
		$orders = array('idguests', 'name', 'surname', 'email', 'phone');
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
		. ' FROM #__bookitguests '.$this->_buildQueryWhere(). ' ' .
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
		. ' FROM #__bookitguests WHERE idguests='.$id
		;
		$db->setQuery($query);
		$row = $db->loadRow();

		$data =new stdClass();


		$data->idguests = null;
		$data->idcountry = $row['1'];
		$data->name = $row['2'];
		$data->surname = $row['3'];
		$data->email = $row['4'];
		$data->city = $row['5'];
		$data->addr = $row['6'];
		$data->phone = $row['7'];

		$db->insertObject( '#__bookitguests', $data, 'idguests' );
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();

		foreach($cids as $cid) {
			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				return false;
			}
		}

		return true;
	}

	function copy ()
	{
	 $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

	 foreach($cids as $cid) {

	 	if (!$this->_buildCopyQuery($cid))
	 	{
	 		return false;
	 			
	 	}
	 }

	 return true;
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