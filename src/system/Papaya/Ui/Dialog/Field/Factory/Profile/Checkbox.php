<?php
/**
* Field factory profiles for a checkbox.
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
* @version $Id: Checkbox.php 37466 2012-08-24 11:06:15Z weinert $
*/

/**
* Field factory profiles for a checkbox.
*
* Each profile defines how a field {@see PapayaUiDialogField} is created for a specified
* type. Here is an options subobject to provide data for the field configuration.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldFactoryProfileCheckbox extends PapayaUiDialogFieldFactoryProfile {

  /**
   * Create a checkbox input field
   *
   * @see PapayaUiDialogFieldInputCheckbox
   * @see PapayaUiDialogFieldFactoryProfile::getField()
   */
  public function getField() {
    $field = new PapayaUiDialogFieldInputCheckbox(
      $this->options()->caption,
      $this->options()->name,
      $this->options()->default,
      $this->options()->mandatory
    );
    return $field;
  }
}