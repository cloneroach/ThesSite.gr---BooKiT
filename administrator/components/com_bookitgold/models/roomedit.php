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



class BookitgoldModelRoomedit extends JModel

{

	var $_data;

	var $_categories;

	var $_category;

	var $_extra;



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

		. ' FROM #__bookitroom WHERE idroom='.$id;

		return $query;

	}



	function _buildCategoriesQuery()

	{

		$query = ' SELECT * '

		. ' FROM #__bookitcategory' ;

		return $query;

	}



	function _buildCategoryQuery()

	{



		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$id=$cid[0];

		if ($id==0)

		{

			$cid = JRequest::getVar('cid', '', 'get');

			$id = $cid[0];

		}

		$db =& JFactory::getDBO();

		$query = ' SELECT idcategory '

		. ' FROM #__bookitroom WHERE idroom='.$id;

		$db->setQuery($query);

		$categoryid = $db->loadResult();



		$query2 = ' SELECT name '

		. ' FROM #__bookitcategory WHERE idcategory='.$categoryid;

		$db->setQuery($query2);

		$categoryname = $db->loadResult();



		return $categoryname;

	}









	function &getData()

	{

		// Load the data

		if (empty( $this->_data )) {

			$query = ' SELECT * FROM #__bookitroom '.

					'  WHERE idroom = '.$this->_id;

			$this->_db->setQuery( $query );

			$this->_data = $this->_db->loadObject();

		}

		if (!$this->_data) {

			$this->_data = new stdClass();





			$this->_data->idroom = 0;

			$this->_data->idcategory = 0;

			$this->_data->name = null;

			$this->_data->description = null;

			$this->_data->url = null;



		}





		return $this->_data;

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



	function getRoomCategory()

	{

			

		// Lets load the data if it doesn't already exist

		if (empty( $this->_category ))

		{

			$this->_category = $this->_buildCategoryQuery();

		}



		return $this->_category;

	}







	/**

	 * Method to store a record

	 */

	function store()

	{

		$row =& $this->getTable();



		$data = JRequest::get( 'post' );

		$name = JRequest::getVar('name');

		$catid = JRequest::getVar('idcategory');

		$idroom = JRequest::getVar('idroom');

		$res['id']=$idroom;





		if ($name==""||$catid==0)

		{

			$res['code']=-1;

			return $res;

		}

			

		// Bind the form fields to the BooKiTGoldbooking table

		if (!$row->bind($data))

		{



			$this->setError($this->_db->getErrorMsg());

			$res['code']=-2;

			return $res;

		}



		// Make sure the record is valid

		if (!$row->check()) {



			$this->setError($this->_db->getErrorMsg());

			$res['code']=-2;

			return $res;



		}





		// Store to the database

		if (!$row->store()) {



			$this->setError($this->_db->getErrorMsg());

			$res['code']=-2;

			return $res;



		}

		//Add records to the availability table

		

			$db =& JFactory::getDBO();

		if ($idroom==0) //Create availability entries only the first time the room is saved

		{

			

			$now = strtotime(date("Y-m-d"));

			$future = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +180 month");

			$daysLeft = floor (($future - $now) / (60 * 60 * 24));

	

			/*$query = "SELECT MAX(idroom) as max_id FROM #__bookitroom";

			$db->setQuery($query);

			$result = $db->loadResult();*/



			/*$r =& JTable::getInstance('roomcategory', 'Table');

			 $r->load($catid);

			 $price = $r->cost;*/



			$yesterday =  date("Y-m-d", strtotime("now"));

			for ($i=0; $i<=$daysLeft; $i++ )

			{

				$a_data =new stdClass();

				$a_data->idavailability = null;

				$a_data->idoffer = null;

				$a_data->idbook = null;

				$a_data->idroom = $row->idroom;

				$a_data->idcategory = $catid;

				$a_data->today =  date("Y-m-d", strtotime( "+" .$i." day" , strtotime ( "now" )));

				$yesterday = $a_data->today;

				$a_data->price = 0;

				$a_data->price_deviation_1=null;

				$a_data->price_deviation_2=null;

				$a_data->availability=0;

				$db = JFactory::getDBO();

				$db->insertObject( '#__bookitavailability', $a_data, 'idavailability');

			}



		}

		else //Update the availability records if the category is changed

		{

			

			$query = ' SELECT idcategory FROM #__bookitavailability WHERE idroom='.$idroom;

			$db->setQuery($query);

			$catid_av = $db->loadResult();

			if ($catid!=$catid_av)//We changed room category, update all availability records

			{

				$query = 'UPDATE #__bookitavailability SET idcategory='.$catid.'  WHERE idroom='.$idroom;

				$this->_db->setQuery( $query );

				$result = $this->_db->query();

			}



		}



		

		if ($idroom==0)

		{

			$res['id']=$row->idroom;

			$res['code']=0;

			return $res;

		}	

		$res['code']=0;

		return $res;

	}



}