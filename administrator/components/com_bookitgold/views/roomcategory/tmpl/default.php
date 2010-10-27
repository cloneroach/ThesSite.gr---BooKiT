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

 

defined('_JEXEC') or die('Restricted access'); ?>



<form action="index.php" method="post" name="adminForm">

<div id="editcell">

    <table class="adminlist">

    <tfoot>

		<tr>

			<td colspan="13"><?php 

			//Pagination



			echo $this->pagination->getListFooter();?></td>

		</tr>

	</tfoot>

    <thead>

        <tr>

             <th width="2%">

              <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" />

            </th>

            <th width="2%">

                <?php echo JText::_( 'ID' ); ?>

            </th>

          

            <th width="35%">

                <?php echo JText::_( 'Category Name' ); ?>

            </th>

            <th width="15%">

                <?php echo JText::_( 'Adults' ); ?>

            </th>

            <th width="15%">

                <?php echo JText::_( 'Children 0-5' ); // Thessite - itan sketo children?>

            </th>
            
            <th width="15%">
            	<?php echo JText::_( 'Chldren 6-12' );  // Thessite ?>
            </th>

              <th width="15%">

                <?php echo JText::_( 'Cost' ); ?>

            </th>

           

        </tr>            

    </thead>

    <?php

   

    $k = 0;

    for ($i=0, $n=count( $this->categories ); $i < $n; $i++)

    {

        $row =& $this->categories[$i];

        

        $checked    = JHTML::_( 'grid.id', $i, $row->idcategory );

        $link = JRoute::_( 'index.php?option=com_bookitgold&controller=roomcategory&task=edit&cid[]='. $row->idcategory );

 

    

        ?>

        <tr class="<?php echo "row$k"; ?>">

            <td width="2%" align="center">

              <?php echo $checked; ?>

            </td>

            <td width="2%" align="center">

               <?php echo $row->idcategory; ?>

            </td>

            

            <td width="35%" align="center">

              <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>  

             

            </td>

            <td width="15%" align="center">

                <?php echo $row->nguests; ?>

            </td>

             <td width="15%" align="center">

                <?php echo $row->nchilds; ?>

            </td>
            
            <td width="15%" align="center">
            	<?php echo $row->lchilds; // Thessite ?>
            </td>

            <td width="15%" align="center">

                <?php echo $row->cost; ?>

            </td>

            

        </tr>

        <?php

        $k = 1 - $k;

    }

    ?>

    </table>

</div>

 

<input type="hidden" name="option" value="com_bookitgold" />

<input type="hidden" name="task" value="listing" />

<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="controller" value="roomcategory" />

 

</form>



 