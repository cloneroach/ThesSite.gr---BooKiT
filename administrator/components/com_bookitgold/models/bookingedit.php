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



class BookitgoldModelBookingedit extends JModel

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

		. ' FROM #__bookitbooking WHERE idbook='.$id;

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

			$query = ' SELECT * FROM #__bookitbooking '.

					'  WHERE idbook = '.$this->_id;

			$this->_db->setQuery( $query );

			$this->_data = $this->_db->loadObject();

		}

		if (!$this->_data) {

			$this->_data = new stdClass();

			$this->_data->idbook = 0;

			$this->_data->idcoupon = null;

			$this->_data->idguests = null;

			$this->_data->nguests = null;

			$this->_data->value_paid = null;

			$this->_data->value_pending = null;

			$this->_data->value_full = null;

			$this->_data->valid_from = null;

			$this->_data->valid_to = null;

			$this->_data->today = null;

			$this->_data->extra_ids = null;

			$this->_data->idroom = null;

			$this->_data->idcatgeory = null;

			$this->_data->nchilds = null;
			
			$this->_data->lchilds = null; // Thessite

			$this->_data->preferences = null;

			$this->_data->status = null;



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

		$idbook = JRequest::getVar('idbook');

		$idcoupon = JRequest::getVar('idcoupon');



		$idguests = JRequest::getVar('idguests');

		$nguests = JRequest::getVar('nguests');



		$value_paid = JRequest::getVar('value_paid');

		$value_pending = JRequest::getVar('value_pending');

		$value_full = JRequest::getVar('value_full');

		$valid_from = JRequest::getVar('valid_from');

		$valid_to = JRequest::getVar('valid_to');



		$idroom = JRequest::getVar('idroom');

		$idcategory = JRequest::getVar('idcategory');

		$status = JRequest::getVar('status');



		if ($idbook>0)

		{

			$query =  "SELECT idcoupon FROM #__bookitbooking WHERE idbook='".$idbook."'";

			$this->_db->setQuery( $query );

			$x_idcoupon = $this->_db->loadResult();

		}

		else

		{

			$x_idcoupon = 0;

		}





		if ($idguests==""|| $idguests==0 ||$nguests==""||$value_full=="" )

		{



			$res["code"]=-1;

			return $res;

		}

		else if (!is_numeric($value_full) || !is_numeric($nguests))

		{

			$res["code"]=-1;

			return $res;

		}

		else if ($value_full<=0 || $nguests<0)

		{

			$res["code"]=-1;

			return $res;

		}





		$new_valid_from = date("Y-m-d", strtotime($valid_from));

		$new_valid_to = date("Y-m-d", strtotime($valid_to));





		if ($new_valid_to <= $new_valid_from)

		{

			$res["code"]=-1;

			return $res;

		}

		else

		{

			$data['valid_from']  = $new_valid_from;

			$data['valid_to'] = $new_valid_to;

		}



		$data['today'] = date ("Y-m-d");

		

		//Check if already booked

		if ($idroom!="" && $idroom>0)

		{

			$fromDateTS = strtotime($new_valid_from);

			$toDateTS = strtotime($new_valid_to);

			for ($currentDateTS = $fromDateTS; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24))

			{

				$currentDateStr = date('Y-m-d',$currentDateTS);

				$query =  "SELECT idbook, availability FROM #__bookitavailability WHERE idroom='".$idroom."' AND today='".$currentDateStr."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->loadRow();



				if ($result['1']==1 && $result['0']!=$idbook)

				{

					$res["code"]=-3;

					return $res;

				}



			}

		}

		else //Check if there is availability for the selected category

		{

				

			$fromDateTS = strtotime($new_valid_from);

			$toDateTS = strtotime($new_valid_to);

			for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)

			{

				$currentDateStr = date('Y-m-d',$currentDateTS);



				if ($idbook>0) //booking editing

				{

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".

					$this->_db->quote($idcategory)." AND today='".$currentDateStr."' AND (availability!='1' OR (availability='1' AND idbook = '".$idbook."' )) ;";

					$this->_db->setQuery($query);

					$count_available = $this->_db->loadResult();

				}

				else

				{

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idcategory=".

					$this->_db->quote($idcategory)." AND today='".$currentDateStr."' AND availability!='1' ;";

					$this->_db->setQuery($query);

					$count_available = $this->_db->loadResult();

				}



				if ($count_available<=0 ) //Found a day with no availability

				{

					$res["code"]=-4;

					return $res;

				}

			}

				



		}



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



		//Update availability table

		//First clear all existing records for this booking, in case we are

		//editing an existing booking

		$query =  "UPDATE #__bookitavailability SET availability='0', idbook='0' WHERE idbook='".$row->idbook."'";

		$this->_db->setQuery( $query );

		$result = $this->_db->query();



		$fromDateTS = strtotime($new_valid_from);

		$toDateTS = strtotime($new_valid_to);

		$nights=round( ($toDateTS-$fromDateTS )/86400) ;

		if ($idroom>0)//Admin has selected room

		{

			for ($currentDateTS = $fromDateTS; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24))

			{

				$currentDateStr = date('Y-m-d',$currentDateTS);



				if ($idroom>0)

				{

						

					$query =  "UPDATE #__bookitavailability SET availability='".$status."', idbook='".$row->idbook."' WHERE idroom='".$idroom."' AND today='".$currentDateStr."'";

					$this->_db->setQuery( $query );

					$result = $this->_db->query();

				}

				if (!$result)

				{

					$res["code"]=-2;

					return $res;

				}



			}

		}

		else //The system has to decide which rooms to allocate

		{

			//All Rooms

			$query = "SELECT idroom FROM #__bookitroom WHERE idcategory=".

			$this->_db->quote($idcategory)." ORDER BY idroom;";

			$this->_db->setQuery($query);

			$all_rooms = $this->_db->loadResultArray();



			$avail_array = array();

			for ($i=0; $i<count($all_rooms); $i++)

			{

				$avail_array[$all_rooms[$i]]=0;

			}



			for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)

			{

				$currentDateStr = date('Y-m-d',$currentDateTS);

				foreach ($all_rooms as $room)

				{

					$query = "SELECT COUNT(*) FROM #__bookitavailability WHERE idroom=".

					$this->_db->quote($room)." AND today='".$currentDateStr."' AND availability != '1' ;";

					$this->_db->setQuery($query);

					$r = $this->_db->loadResult();

					if ($r>0)

					{

						$avail_array[$room] += 1;



					}

				}

			}

				

			$idroom_max = 0;

			$maxVal = 0;

			foreach ($avail_array as $key => $value)

			{

				if ($value== max($avail_array))

				{

					$idroom_max = $key;

					$maxVal= $value;

					break;

				}

			}

			

				

			if ($maxVal<$nights) //Room Change needed, try to book the idroom_max, if not available choose one room from the array

			{



				for ($currentDateTS = $fromDateTS, $cnt=0; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24), $cnt++)

				{

					$currentDateStr = date('Y-m-d',$currentDateTS);

					$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".

					$this->_db->quote($idroom_max)." AND today='".$currentDateStr."';";

					$this->_db->setQuery($query);

					$idbook_que = $this->_db->loadResult();

					

					if ($idbook_que!=1)//The max room is available

					{

						$query =  "UPDATE #__bookitavailability SET availability='".$status."', idbook='".$row->idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";

						$this->_db->setQuery( $query );

						$result = $this->_db->query();

					}

					else

					{

						foreach ($all_rooms as $room)

						{

							$query = "SELECT availability FROM #__bookitavailability WHERE idroom=".

							$this->_db->quote($room)." AND today='".$currentDateStr."';";

							$this->_db->setQuery($query);

							$idbook_que = $this->_db->loadResult();



							if ($idbook_que!=1)

							{

								$query =  "UPDATE #__bookitavailability SET availability='".$status."', idbook='".$row->idbook."' WHERE idroom='".$room."' AND today='".$currentDateStr."'";

								$this->_db->setQuery( $query );

								$result = $this->_db->query();

								break;



							}



						}

					}

				}

				$preferences = "*This booking might need room changes.";

				$query =  "UPDATE #__bookitbooking SET preferences='".$preferences."' WHERE idbook='".$row->idbook."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->query();





			}

			else //There is a room that can accommodate the guest for the whole period

			{

				//Set the room to booking

				$query =  "UPDATE #__bookitbooking SET idroom='".$idroom_max."' WHERE idbook='".$row->idbook."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->query();

				for ($currentDateTS = $fromDateTS; $currentDateTS < $toDateTS; $currentDateTS += (60 * 60 * 24))

				{

					$currentDateStr = date('Y-m-d',$currentDateTS);

					$query =  "UPDATE #__bookitavailability SET availability='".$status."', idbook='".$row->idbook."' WHERE idroom='".$idroom_max."' AND today='".$currentDateStr."'";

					$this->_db->setQuery( $query );

					$result = $this->_db->query();

						

						

				}

			}

		}





		//Manage Coupons

		//1. New booking

		if ($idbook==0)

		{

			//Set the coupon to "isUsed"

			if ($idcoupon!=""&&$idcoupon>0)

			{

				$query =  "UPDATE #__bookitcoupon SET used='1' WHERE idcoupon='".$idcoupon."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->query();

				if (!$result)

				{

					$res["code"]=-2;

					return $res;

				}

			}

		}

		//2. Edit booking

		else

		{





			if ($x_idcoupon>0 && $idcoupon!=$x_idcoupon)//change an existing coupon with another one

			{



				$query =  "UPDATE #__bookitcoupon SET used='0' WHERE idcoupon='".$x_idcoupon."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->query();

				if ($idcoupon>0)

				{

					$query =  "UPDATE #__bookitcoupon SET used='1' WHERE idcoupon='".$idcoupon."'";

					$this->_db->setQuery( $query );

					$result = $this->_db->query();

				}

				else if ($idcoupon==0)

				{

					$query =  "UPDATE #__bookitcoupon SET used='0' WHERE idcoupon='".$idcoupon."'";

					$this->_db->setQuery( $query );

					$result = $this->_db->query();

				}

			}

			else if ($x_idcoupon==0 && $idcoupon!=$x_idcoupon)

			{

				$query =  "UPDATE #__bookitcoupon SET used='1' WHERE idcoupon='".$idcoupon."'";

				$this->_db->setQuery( $query );

				$result = $this->_db->query();



			}

		}





		$res["id"]=$row->idbook;

		$res["code"]=0;

		return $res;



	}

}