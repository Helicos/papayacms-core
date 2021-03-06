<?php
/**
 * A simple button with a caption and without a name. That links to the specified reference.
*
* @copyright 2016 by papaya Software GmbH - All rights reserved.
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
*/

/**
* A simple button with a caption and without a name. That links to the specified reference.
*
* Usage:
*   $dialog->buttons()->add(new PapayaUiDialogButtonSubmit('Save'));
*
*   $dialog->buttons()->add(
*     new PapayaUiDialogButtonSubmit(
*       new PapayaUiStringTranslated('Save')
*     ),
*     PapayaUiDialogButton::ALIGN_LEFT
*   );
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogButtonLink extends PapayaUiDialogButton {

  /**
  * Button caption
  * @var string|PapayaUiString
  */
  protected $_caption = 'Submit';

  /**
   * @var PapayaUiReference
   */
  private $_reference;

  /**
  * Initialize object, set caption and alignment
  *
  * @param string|PapayaUiString $caption
  * @param integer $align
  */
  public function __construct($caption, $align = PapayaUiDialogButton::ALIGN_RIGHT) {
    parent::__construct($align);
    $this->_caption = $caption;
  }

  /**
  * Append button output to DOM
  *
  * @param PapayaXmlElement $parent
  */
  public function appendTo(PapayaXmlElement $parent) {
    $parent->appendElement(
      'button',
      array(
        'type' => 'link',
        'align' => ($this->_align == PapayaUiDialogButton::ALIGN_LEFT) ? 'left' : 'right',
        'href' => $this->reference()
      ),
      (string)$this->_caption
    );
  }

  /**
   * @param PapayaUiReference|NULL $reference
   * @return PapayaUiReference
   */
  public function reference(PapayaUiReference $reference = NULL) {
    if (isset($reference)) {
      $this->_reference = $reference;
    } elseif (NULL === $this->_reference) {
      $this->_reference = new PapayaUiReference();
      $this->_reference->papaya($this->papaya());
    }
    return $this->_reference;
  }
}