<?php
/**
* A selection field displaing the available languages
*
* @copyright 2013 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Language.php 39129 2014-02-06 17:36:09Z weinert $
*/

/**
* A selection field displaing the available languages
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldSelectLanguage extends PapayaUiDialogFieldSelect {

  const OPTION_ALLOW_ANY = 1;
  const OPTION_USE_IDENTIFIER = 2;

  public function __construct(
    $caption, $name, PapayaContentLanguages $languages = NULL, $options = 0
  ) {
    // @codeCoverageIgnoreStart
    if (NULL === $languages) {
      $languages = $this->papaya()->languages;
    }
    // @codeCoverageIgnoreEnd
    $items = array();
    if (PapayaUtilBitwise::inBitmask(self::OPTION_USE_IDENTIFIER, $options)) {
      foreach ($languages as $language) {
        $items[$language['identifier']] = $language;
      }
      $any = '*';
    } else {
      $items = $languages;
      $any = 0;
    }
    if (PapayaUtilBitwise::inBitmask(self::OPTION_ALLOW_ANY, $options)) {
      $values = new PapayaIteratorMultiple(
        PapayaIteratorMultiple::MIT_KEYS_ASSOC,
        array($any => new PapayaUiStringTranslated('Any')),
        $items
      );
    } else {
      $values = $items;
    }
    parent::__construct($caption, $name, $values);
  }

  public function appendTo(PapayaXmlElement $parent) {
    $this->callbacks()->getOptionCaption = array($this, 'callbackGetLanguageCaption');
    return parent::appendTo($parent);
  }

  public function callbackGetLanguageCaption($context, $language) {
    if (is_array($language)) {
      return $language['title'].' ('.$language['code'].')';
    } else {
      return (string)$language;
    }
  }
}