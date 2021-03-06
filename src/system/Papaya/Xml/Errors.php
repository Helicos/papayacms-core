<?php
/**
* Encapsulation object for the libxml errors.
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
* @subpackage Xml
* @version $Id: Errors.php 39514 2014-03-04 17:03:13Z weinert $
*/

/**
* Encapsulation object for the libxml errors.
*
* This is a wrapper for the libxml error handling function, it converts the warnings and errors
* into PapayaMessage objects and dispatches them into the MessageManager.
*
* @package Papaya-Library
* @subpackage Xml
*/
class PapayaXmlErrors extends PapayaObject {

  /**
   * @var bool
   */
  private $_savedStatus = FALSE;

  /**
  * Map libxml error types to message types
  * @var array
  */
  private $_errorMapping = array(
    LIBXML_ERR_NONE => PapayaMessage::SEVERITY_INFO,
    LIBXML_ERR_WARNING => PapayaMessage::SEVERITY_WARNING,
    LIBXML_ERR_ERROR => PapayaMessage::SEVERITY_ERROR,
    LIBXML_ERR_FATAL => PapayaMessage::SEVERITY_ERROR
  );

  /**
  * Activate the libxml internal error capturing (and clear the current buffer)
  */
  public function activate() {
    $this->_savedStatus = libxml_use_internal_errors(TRUE);
    libxml_clear_errors();
  }

  /**
  * Deactivate the libxml internal error capturing (and clear the current buffer)
  */
  public function deactivate() {
    libxml_clear_errors();
    libxml_use_internal_errors($this->_savedStatus);
  }

  /**
   * Encapsulate a libxml method to capture errors into exceptions. Returns
   * NULL if a PapayaXmlException was captured, the result of the callback
   * otherwise.
   *
   * @param callable $callback
   * @param NULL|array $arguments
   * @param bool $emitErrors
   * @return mixed
   */
  public function encapsulate($callback, array $arguments = NULL, $emitErrors = TRUE) {
    $this->activate();
    try {
      $success = call_user_func_array(
        $callback,
        isset($arguments) ? $arguments : array()
      );
      if ($emitErrors) {
        $this->emit();
      }
      $this->deactivate();
    } catch (PapayaXmlException $e) {
      if ($emitErrors) {
        $context = new PapayaMessageContextGroup();
        if ($e->getContextFile()) {
          $context->append(
            new PapayaMessageContextFile(
              $e->getContextFile(), $e->getContextLine(), $e->getContextColumn()
            )
          );
        }
        $context->append(new PapayaMessageContextVariable($arguments));
        $context->append(new PapayaMessageContextBacktrace(1));
        $this->papaya()->messages->log(
          PapayaMessageLogable::GROUP_SYSTEM,
          PapayaMessage::SEVERITY_ERROR,
          $e->getMessage(),
          $context
        );
      }
      return NULL;
    }
    return $success;
  }

  /**
   * Dispatches messages for the libxml errors in the internal buffer.
   *
   * @param boolean $fatalOnly
   * @throws PapayaXmlException
   */
  public function emit($fatalOnly = FALSE) {
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
      if ($error->level == LIBXML_ERR_FATAL) {
        throw new PapayaXmlException($error);
      } elseif (!$fatalOnly && 0 !== strpos($error->message, 'Namespace prefix papaya')) {
        $this
          ->papaya()
          ->messages
          ->dispatch(
            $this->getMessageFromError($error)
          );
      }
    }
    libxml_clear_errors();
  }

  /**
   * @deprecated {@see self::emit()}
   * @param boolean $fatalOnly
   */
  public function omit($fatalOnly = FALSE) {
    $this->emit($fatalOnly);
  }

  /**
   * Converts a libxml error object into a PapayaMessage
   *
   * @param libXMLError $error
   * @return \PapayaMessageLog
   */
  public function getMessageFromError(libXMLError $error) {
    $messageType = $this->_errorMapping[$error->level];
    $message = new PapayaMessageLog(
      PapayaMessageLogable::GROUP_SYSTEM,
      $messageType,
      sprintf(
        '%d: %s in line %d at char %d',
        $error->code,
        $error->message,
        $error->line,
        $error->column
      )
    );
    if (!empty($error->file)) {
      $message
        ->context()
        ->append(
          new PapayaMessageContextFile(
            $error->file, $error->line, $error->column
          )
        );
    }
    $message
      ->context()
      ->append(
        new PapayaMessageContextBacktrace(3)
      );
    return $message;
  }
}