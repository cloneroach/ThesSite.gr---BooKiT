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

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.model');



class BookitgoldModelRoomextraedit extends JModel

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

		. ' FROM #__bookitextra WHERE idextra='.$id;

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

			$query = ' SELECT * FROM #__bookitextra '.

					'  WHERE idextra = '.$this->_id;

			$this->_db->setQuery( $query );

			$this->_data = $this->_db->loadObject();

		}

		if (!$this->_data) {

			$this->_data = new stdClass();	

			$this->_data->idextra = 0;

			$this->_data->name = null;

			$this->_data->extra_type = 0;

			$this->_data->value_fix = 0;

			$this->_data->value_percent = 0;

			$this->_data->value_type = 0;

			$this->_data->description = null;



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

		$name = JRequest::getVar('name');

		$extra_type = JRequest::getVar('extra_type');

		$value_fix = JRequest::getVar('value_fix');

		$value_percent = JRequest::getVar('value_percent');

		$value_type = JRequest::getVar('value_type');



		$id = JRequest::getVar('idextra');

		$res["id"]=$id;



		if ($name==""|| $extra_type==""|| ($value_fix==""&&$value_percent=="")

		||$value_type=="")

		{

			$res["code"]=-1;

			return $res;

		}

		else if (!is_numeric($value_fix) && !is_numeric($value_percent))

		{

			

				$res["code"]=-1;

				return $res;

		

		}

		

		

$data['description']=JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML );

		

		// Bind the form fields to the BooKiTGoldbooking table

		if (!$row->bind($data)) {

				

			$this->setError($this->_db->getErrorMsg());

			$res["code"]=-2;

			return $res;

		}



		// Make sure the record is valid

		if (!$row->check()) {

				

			$this->setError($this->_db->getErrorMsg());

			$res["code"]=-2;

			return $res;

		}





		// Store to the database

		if (!$row->store()) {

				

			$this->setError($this->_db->getErrorMsg());

			$res["code"]=-2;

			return $res;

		}

		

		if ($res["id"]==0)

			$res["id"]=$row->idextra;

		$res["code"]=0;

		return $res;

	}



	







}