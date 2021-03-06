<?php
/**
* Abstract superclass for an administration page.
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
* @subpackage Administration
* @version $Id: Page.php 39818 2014-05-13 13:15:13Z weinert $
*/

/**
* Abstract superclass for an administration page.
*
* The administration page has thrre parts (content, navigation, information). The parts are executed
* one after another with the same parameters. Changes to the parameters of one part are assigned
* to the next.
*
* Here is an composed toolbar sets for each element.
*
* @package Papaya-Library
* @subpackage Administration
*/
abstract class PapayaAdministrationPage extends PapayaObject {

  /**
   * @var string|NULL
   */
  private $_moduleId;

  /**
   * @var PapayaTemplate
   */
  private $_layout = NULL;
  /**
   * @var PapayaAdministrationPageParts
   */
  private $_parts = NULL;

  /**
   * @var PapayaUiToolbar
   */
  private $_toolbar = NULL;

  /**
   * @var string
   */
  protected $_parameterGroup = '';

  /**
   * Create page object and store layout object for later use
   *
   * @param PapayaTemplate $layout
   * @param null|string $moduleId
   */
  public function __construct($layout, $moduleId = NULL) {
    $this->_layout = $layout;
    $this->_moduleId = $moduleId;
  }

  /**
   * @return null|string
   */
  public function getModuleId() {
    return $this->_moduleId;
  }

  /**
   * This method needs to be overloaded to create the content part of the page
   * If an valid part is returned, it will be used first.
   *
   * @return PapayaAdministrationPagePart|FALSE
   */
  protected function createContent() {
    return FALSE;
  }

  /**
   * This method needs to be overloaded to create the navigation part of the page.
   * If an valid part is returned, it will be used after the content part.
   *
   * @return PapayaAdministrationPagePart|FALSE
   */
  protected function createNavigation() {
    return FALSE;
  }

  /**
   * This method needs to be overloaded to create the content part of the page.
   * If an valid part is returned, it will be used last.
   *
   * @return PapayaAdministrationPagePart|FALSE
   */
  protected function createInformation() {
    return FALSE;
  }

  /**
   * Execute the module and add the xml to the layout object
   */
  public function execute() {
    $parts = $this->parts();
    $restoreParameters = ($this->papaya()->request->method == 'get') && !empty($this->_parameterGroup);
    $parametersName = array(get_class($this), 'parameters', $this->_parameterGroup);
    if ($restoreParameters && $parts->parameters()->isEmpty()) {
      $value = $this->papaya()->session->getValue($parametersName);
      $parts->parameters()->merge(is_array($value) ? $value : array());
      $this->papaya()->request->setParameters(
        PapayaRequest::SOURCE_QUERY,
        $this->papaya()->request->getParameters(PapayaRequest::SOURCE_QUERY)->set(
          $this->_parameterGroup, is_array($value) ? $value : array()
        )
      );
    }
    foreach ($parts as $name => $part) {
      if ($part instanceof PapayaAdministrationPagePart) {
        if ($xml = $part->getXml()) {
          $this->_layout->add($xml, $this->parts()->getTarget($name));
        }
      }
    }
    if ($restoreParameters) {
      $this->papaya()->session->setValue($parametersName, $parts->parameters()->toArray());
    }
    $this->parts()->toolbar()->toolbar($this->toolbar());
    $this->_layout->addMenu($this->parts()->toolbar()->getXml());
  }

  /**
   * Getter/Setter for the parts list
   *
   * @param PapayaAdministrationPageParts $parts
   * @return PapayaAdministrationPageParts
   */
  public function parts(PapayaAdministrationPageParts $parts = NULL) {
    if ($parts) {
      $this->_parts = $parts;
    } elseif (NULL === $this->_parts) {
      $this->_parts = new PapayaAdministrationPageParts($this);
      $this->_parts->papaya($this->papaya());
      if (!empty($this->_parameterGroup)) {
        $this->_parts->parameterGroup($this->_parameterGroup);
      }
    }
    return $this->_parts;
  }

  /**
   * A method called by the parts list, if an part is needed and not already existing in the list.
   *
   * It calls different protected methods that can be overload to create the part. If it returns
   * FALSE the part is ignored.
   *
   * @param string $name
   * @return FALSE|PapayaAdministrationPagePart
   */
  public function createPart($name) {
    switch ($name) {
    case PapayaAdministrationPageParts::PART_CONTENT :
      return $this->createContent();
    case PapayaAdministrationPageParts::PART_NAVIGATION :
      return $this->createNavigation();
    case PapayaAdministrationPageParts::PART_INFORMATION :
      return $this->createInformation();
    }
    return FALSE;
  }

  /**
   * Getter/Setter for the action toolbar. The parts append buttons to sets the sets are
   * appended to the toolbar.
   *
   * @param PapayaUiToolbar $toolbar
   * @return PapayaUiToolbar
   */
  public function toolbar(PapayaUiToolbar $toolbar = NULL) {
    if ($toolbar) {
      $this->_toolbar = $toolbar;
    } elseif (NULL === $this->_toolbar) {
      $this->_toolbar = new PapayaUiMenu();
      $this->_toolbar->papaya($this->papaya());
      $this->_toolbar->identifier = 'edit';
    }
    return $this->_toolbar;
  }
}