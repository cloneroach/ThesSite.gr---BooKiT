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

class BookitgoldModelBooking extends JModel
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
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$orderby = '';

		$app =& JFactory::getApplication();
		$db =& JFactory::getDBO();

		$orders = array('idbook', 'today', 'status', 'valid_from', 'valid_to', 'value_full', 'value_pending', 'name', 'surname', 'email');
		$order = $app->getUserStateFromRequest('com_bookitgold.filter_order', 'filter_order', 'today');
		$order_Dir = strtoupper ($app->getUserStateFromRequest('com_bookitgold.filter_order_Dir', 'filter_order_Dir','ASC'));
		// validate the order direction, must be ASC or DESC
		if ($order_Dir != 'ASC' && $order_Dir != 'DESC')
		{
			$order_Dir = 'ASC';
		}
		// if order column is unknown use the default
		if (!in_array($order, $orders))
		{
			$order = 'today';
				
		}
		
		return ' ORDER BY ' . $db->nameQuote($order) ." $order_Dir ";

	}

	function _buildQuery()
	{
		$query = "SELECT * FROM `jos_bookitbooking` INNER JOIN `jos_bookitguests` ON `jos_bookitbooking`.idguests = `jos_bookitguests`.`idguests`
	".$this->_buildQueryWhere(). " " .
		$this->_buildContentOrderBy();
		return $query;

	}

	function _buildQueryWhere() {
		// get the application
		$app =& JFactory::getApplication();

		// get the free text search filter
		$search = $app->getUserStateFromRequest('com_bookitgold.search','search', '', 'string');
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

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();
		$db =& JFactory::getDBO();



		foreach($cids as $cid) {
			$query =  "UPDATE #__bookitavailability SET availability='0', idbook='0' WHERE idbook='".$cid."'";
			$db->setQuery( $query );
			$result = $db->query();

			$query2 =  "SELECT idcoupon FROM #__bookitbooking WHERE idbook='".$cid."'";
			$db->setQuery( $query2 );
			$coup = $db->loadResult();

			$query =  "UPDATE #__bookitcoupon SET usable='0' WHERE idcoupon='".$coup."'";
			$db->setQuery( $query );
			$result = $db->query();

			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				return false;
			}
		}



		return true;
	}
	/*function _buildCopyQuery($id)
	 {
	  
		$db =& JFactory::getDBO();
		$query = ' SELECT * '
		. ' FROM #__bookitcoupon WHERE idcoupon='.$id
		;
		$db->setQuery($query);
		$row = $db->loadRow();

		$data =new stdClass();


		$data->idcoupon = null;
		$data->name = "Copy of ".$row['1'];
		$data->value_fix = $row['2'];
		$data->value_percent = $row['3'];
		$data->usable = $row['4'];
		$data->used = $row['5'];
		$data->valid_from = $row['6'];
		$data->valid_to = $row['7'];
		$data->code = uniqid("");
		$data->every_x_day = $row['9'];

		$db->insertObject( '#__bookitcoupon', $data, 'idcoupon' );
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
	 }*/


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