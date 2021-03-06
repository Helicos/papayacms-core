<?php
/**
* Field factory profiles for a select field displayed as checkboxes.
*
* @copyright 2012 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Checkboxes.php 37474 2012-08-24 15:58:52Z weinert $
*/

/**
* Field factory profiles for a select field displayed as checkboxes. Beaucser of the
* nature of this field type, multiple selection are possible
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldFactoryProfileSelectCheckboxes
  extends PapayaUiDialogFieldFactoryProfileSelect {

  /**
   * Create a select field displayed as checkboxes
   *
   * @param array|Traversable $elements
   * @return PapayaUiDialogFieldSelect
   */
  protected function createField($elements) {
    return new PapayaUiDialogFieldSelectCheckboxes(
      $this->options()->caption,
      $this->options()->name,
      $elements
    );
  }
}