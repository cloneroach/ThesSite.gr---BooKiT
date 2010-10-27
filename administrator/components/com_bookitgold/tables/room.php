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

defined('_JEXEC') or die('Restricted access');

 

/**

 * Booking Table class

 *  `idroom` INT UNSIGNED NOT NULL AUTO_INCREMENT ,

 * `idcategory` INT UNSIGNED NULL ,

 * `name` TEXT NULL ,

 */

class TableRoom extends JTable

{

    

    var $idroom = null;

    var $idcategory = null;

    var $name = null;

    var $description = null;

    var $url = null;



 

    /**

     * Constructor

     *

     * @param object Database connector object

     */

    function __construct( &$db ) {

        parent::__construct('#__bookitroom', 'idroom', $db);

    }

}