<?php
/**
* Papaya Interface Media Reference (Hyperlink Reference)
*
* @copyright 2009 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Media.php 35801 2011-06-15 08:50:21Z weinert $
*/

/**
* Papaya Interface Media Reference (Hyperlink Reference)
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiReferenceMedia extends PapayaUiReference {

  /**
  * Page identification data
  * @var array
  */
  protected $_pageData = array(
    'title' => 'index',
    'mode' => 'media',
    'media_id' => NULL,
    'version' => 0,
    'extension' => '',
    'preview' => FALSE
  );

  /**
  * Static create function to allow fluent calls.
  *
  * @param PapayaUrl $url
  * @return PapayaUiReference
  */
  public static function create(PapayaUrl $url = NULL) {
    return new self($url);
  }

  /**
   * @see papaya-lib/system/Papaya/Interface/PapayaUiReference#get()
   * @param bool $forPublic
   * @return null|string
   */
  public function get($forPublic = false) {
    if (!empty($this->_pageData['media_id'])) {
      $result = $this->url()->getHostUrl().$this->_basePath;
      $result .= $this->_pageData['title'];
      $result .= '.'.$this->_pageData['mode'];
      if ((!$forPublic) && $this->_pageData['preview']) {
        $result .= '.preview';
      }
      $result .= '.'.$this->_pageData['media_id'];
      if ($this->_pageData['version'] > 0) {
        $result .= 'v'.$this->_pageData['version'];
      }
      if (!empty($this->_pageData['extension'])) {
        $result .= '.'.$this->_pageData['extension'];
      }
      return $result;
    }
    return NULL;
  }

  /**
   * @see papaya-lib/system/Papaya/Interface/PapayaUiReference#load($request)
   * @param PapayaRequest $request
   * @return $this|PapayaUiReference
   */
  public function load(PapayaRequest $request) {
    parent::load($request);
    $this->setPreview(
      $request->getParameter('preview', FALSE, NULL, PapayaRequest::SOURCE_PATH)
    );
    return $this;
  }

  /**
  * Set media id
  *
  * @param string $mediaId
  * @return PapayaUiReferenceMedia
  */
  public function setMediaId($mediaId) {
    $this->prepare();
    if (!empty($mediaId) && preg_match('(^[a-fA-F\d]{32}$)D', $mediaId)) {
      $this->_pageData['media_id'] = strtolower($mediaId);
    }
    return $this;
  }

  /**
  * Set media version
  *
  * @param integer $version
  * @return PapayaUiReferenceMedia
  */
  public function setMediaVersion($version) {
    $this->prepare();
    if ($version > 0) {
      $this->_pageData['version'] = (int)$version;
    }
    return $this;
  }

  /**
  * Set file title (normalized string)
  *
  * @param string $title
  * @return PapayaUiReferenceMedia
  */
  public function setTitle($title) {
    $this->prepare();
    if (preg_match('(^[a-zA-Z\d_-]+$)D', $title)) {
      $this->_pageData['title'] = (string)$title;
    }
    return $this;
  }

  /**
  * Set mode
  *
  * @param string $mode
  * @return PapayaUiReferenceMedia
  */
  public function setMode($mode) {
    $this->prepare();
    if (in_array($mode, array('media', 'download'))) {
      $this->_pageData['mode'] = (string)$mode;
    } elseif (in_array($mode, array('thumb', 'thumbnail'))) {
      $this->_pageData['mode'] = 'media';
    }
    return $this;
  }

  /**
  * Set extension (normalized string)
  *
  * @param string $extension
  * @return PapayaUiReferenceMedia
  */
  public function setExtension($extension) {
    $this->prepare();
    if (preg_match('(^[a-zA-Z\d_]+$)D', $extension)) {
      $this->_pageData['extension'] = strtolower($extension);
    }
    return $this;
  }

  /**
  * Set media data from "uri" [id]v[version].[extension]
  * @param string $mediaUri
  * @return PapayaUiReferenceMedia
  */
  public function setMediaUri($mediaUri) {
    $this->prepare();
    $pattern = '(^
      (?P<media_id>[a-fA-F\d]{32})
      (?:v(?P<version>\d+))?
      (?:\.(?P<extension>[a-zA-Z\d]+))?
    $)Dix';
    if (preg_match($pattern, $mediaUri, $matches)) {
      $this->setMediaId($matches['media_id']);
      if (!empty($matches['version']) && $matches['version'] > 0) {
        $this->setMediaVersion($matches['version']);
      }
      if (!empty($matches['extension'])) {
        $this->setExtension($matches['extension']);
      }
    }
    return $this;
  }

  /**
  * Set preview mode
  *
  * @param boolean $isPreview
  * @return PapayaUiReferencePage
  */
  public function setPreview($isPreview) {
    $this->prepare();
    $this->_pageData['preview'] = (bool)$isPreview;
    return $this;
  }
}