<?php
/**
* A list of listview items, used for the $items property of a {@see PapayaUiListview}
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
* @subpackage Ui
* @version $Id: Items.php 39723 2014-04-07 13:51:24Z weinert $
*/

/**
* A list of listview items, used for the $items property of a {@see PapayaUiListview}
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiListviewItems
  extends PapayaUiControlCollection {

  /**
  * Only {@see PapayaUiListviewItem} objects are allowed in this list
  *
  * @var string
  */
  protected $_itemClass = 'PapayaUiListviewItem';

  /**
  * If a tag name is provided, an additional element will be added in
  * {@see PapayaUiControlCollection::appendTo()) that will wrapp the items.
  * @var string
  */
  protected $_tagName = 'items';

  /**
  * A basic reference (link object) for the listview items. The reference object is cloned and
  * modified by the item using it's $actionParameters.
  *
  * @var NULL|PapayaUiReference
  */
  protected $_reference = NULL;

  /**
   * Create object an set owner listview object.
   *
   * @param PapayaUiListview $listview
   * @return \PapayaUiListviewItems
   */
  public function __construct(PapayaUiListview $listview) {
    $this->owner($listview);
  }

  /**
   * Return the listview of this list
   *
   * @param PapayaUiListview $listview
   * @return PapayaUiListview
   */
  public function owner($listview = NULL) {
    PapayaUtilConstraints::assertInstanceOfOrNull('PapayaUiListview', $listview);
    return parent::owner($listview);
  }

  /**
  * Getter/Setter for the basic reference used by the list items. This will be the link on the
  * caption/image.
  *
  * @param PapayaUiReference $reference
  * @return PapayaUiReference
  */
  public function reference(PapayaUiReference $reference = NULL) {
    if (isset($reference)) {
      $this->_reference = $reference;
    }
    if (is_null($this->_reference)) {
      $this->_reference = $this->owner()->reference();
    }
    return $this->_reference;
  }
}