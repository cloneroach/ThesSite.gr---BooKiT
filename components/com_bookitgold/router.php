<?php

/**

 * BookITGold - Joomla Booking Management Component

 *

 *

 * @version 4.0

 * @author Costas Kakousis (info@istomania.com)

 * @copyright (C) 2009-2010 by Costas Kakousis (http://www.istomania.com)

 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html

 *

 * If you fork this to create your own project,

 * please make a reference to BookIT someplace in your code

 * and provide a link to http://www.istomania.com

 *

 * This file is part of BookIT.

 * BookIT is free software: you can redistribute it and/or modify

 * it under the terms of the GNU General Public License as published by

 * the Free Software Foundation, either version 3 of the License, or

 * (at your option) any later version.

 *

 *  BookITGold is distributed in the hope that it will be useful,

 *  but WITHOUT ANY WARRANTY; without even the implied warranty of

 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

 *  GNU General Public License for more details.

 *

 *  You should have received a copy of the GNU General Public License

 *  along with BookITGold.  If not, see <http://www.gnu.org/licenses/>.

 *

 **/



/**

 * Builds route for BooKiTGold

 *

 * @access public

 * @param array Query associative array

 * @return array SEF URI segments

 */

function BookitgoldBuildRoute( &$query )

{

	



	$segments = array();

	 

	if(isset($query['controller']))

	{

		$segments[] = $query['controller'];

		unset( $query['controller'] );

	}

	if(isset($query['task']))

	{

		$segments[] = $query['task'];

		unset( $query['task'] );

	}

	 

	return $segments;

	 



}





function BookitgoldParseRoute( $segments )

{



	$vars = array();

	$vars['controller'] = $segments[0];

	$vars['task'] = $segments[1];



	return $vars;

}