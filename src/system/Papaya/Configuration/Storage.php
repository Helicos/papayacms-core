<?php
/**
* Interface for the options storage (load)
*
* @copyright 2011 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Library
* @subpackage Configuration
* @version $Id: Storage.php 36051 2011-08-05 16:32:54Z weinert $
*/

/**
* Interface for the options storage (load)
*
* @package Papaya-Library
* @subpackage Configuration
*/
interface PapayaConfigurationStorage extends IteratorAggregate {

  /**
  * Load options from external data source
  */
  function load();
}