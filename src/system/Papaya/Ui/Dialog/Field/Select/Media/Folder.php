<?php
/**
* A selection field for the media folders
*
* @copyright 2010 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Folder.php 37484 2012-08-27 22:21:02Z weinert $
*/

/**
* A selection field displayed as radio boxes, only a single value can be selected.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldSelectMediaFolder extends PapayaUiDialogField {

  private $_folders = NULL;

  public function __construct($caption, $name) {
    $this->setCaption($caption);
    $this->setName($name);
  }

  /**
  * Append select field to DOM
  *
  * @param PapayaXmlElement $parent
  */
  public function appendTo(PapayaXmlElement $parent) {
    $field = $this->_appendFieldTo($parent);
    $select = $field->appendElement(
      'select',
      array(
        'name' => $this->_getParameterName($this->getName()),
        'type' => 'dropdown',
      )
    );
    $iterator = new RecursiveIteratorIterator(
      $this->mediaFolders(), RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $folderId => $folder) {
      $caption = '';
      if ($iterator->getDepth() > 0) {
        $caption .= str_repeat('  ', $iterator->getDepth() - 1).'->';
      }
      $caption .= PapayaUtilArray::get($folder, 'title', '');
      $option = $select->appendElement(
        'option', array('value' => $folderId), $caption
      );
      if ($folderId == $this->getCurrentValue()) {
        $option->setAttribute('selected', 'selected');
      }
    }
  }


  /**
   * Getter/Setter for the media folders data object, it implements IteratorAggregate and
   * returning a RecursiveIterator
   *
   * @param PapayaContentMediaFolders $folders
   * @return PapayaContentMediaFolders
   */
  public function mediaFolders(PapayaContentMediaFolders $folders = NULL) {
    if (isset($folders)) {
      $this->_folders = $folders;
      $this->setFilter(new PapayaFilterListKeys($this->_folders));
    } elseif (NULL == $this->_folders) {
      $this->_folders = new PapayaContentMediaFolders();
      $this->_folders->activateLazyLoad();
      $this->setFilter(new PapayaFilterListKeys($this->_folders));
    }
    return $this->_folders;
  }
}