<?php
/**
* The PluginFactory is a superclass for specialized plguin factories. It allows to define
* an array of name => guid pairs and access the plugin by the "local" name.
*
* This allows to avoid conflicts, while still using names for plugin access and not guids.
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
* @subpackage Plugins
* @version $Id: Factory.php 39730 2014-04-07 21:05:30Z weinert $
*/

/**
* The PluginFactory is a superclass for specialized plguin factories. It allows to define
* an array of name => guid pairs and access the plugin by the "local" name.
*
* This allows to avoid conflicts, while still using names for plugin access and not guids.
*
* @package Papaya-Library
* @subpackage Plugins
*/
abstract class PapayaPluginFactory extends PapayaObject {

  /**
  * The plugin name => guid list.
  *
  * @var array(string=>string,...)
  */
  protected $_plugins = array();

  /**
  * Plugin options objects
  *
  * @var array
  */
  private $_options = array();

  /**
  * plugin loader object
  *
  * @var PapayaPluginLoader
  */
  private $_pluginLoader = NULL;

  /**
  * An optional owner object, given to the plugin on create.
  *
  * @var NULL|object
  */
  protected $_owner = NULL;

  /**
  * Initialize plugin factory and store the owner object.
  *
  * @param object $owner
  */
  public function __construct($owner = NULL) {
    PapayaUtilConstraints::assertObjectOrNull($owner);
    $this->_owner = $owner;
  }

  /**
  * @param PapayaPluginLoader $pluginLoader
  * @return PapayaPluginLoader
  */
  public function loader(PapayaPluginLoader $pluginLoader = NULL) {
    if (isset($pluginLoader)) {
      $this->_pluginLoader = $pluginLoader;
    }
    if (is_null($this->_pluginLoader)) {
      $this->_pluginLoader = $this->papaya()->plugins;
    }
    return $this->_pluginLoader;
  }

  /**
  * Validate if a guid for the given plugin name was defined.
  *
  * @param string $pluginName
  * @return boolean
  */
  public function has($pluginName) {
    return array_key_exists($pluginName, $this->_plugins);
  }

  /**
  * Fetch a plugin from plugin loader using the guid definition in self::$_plugins.
  *
  * @throws InvalidArgumentException
  * @param string $pluginName
  * @param boolean $singleInstance
  * @return NULL|object
  */
  public function get($pluginName, $singleInstance = FALSE) {
    if ($this->has($pluginName)) {
      return $this->loader()->get(
        $this->_plugins[$pluginName], $this->_owner, NULL, $singleInstance
      );
    } else {
      throw new InvalidArgumentException(
        sprintf(
          'InvalidArgumentException: "%s" does not know plugin "%s".',
          get_class($this),
          $pluginName
        )
      );
    }
  }

  /**
  * Allow to fetch plugins by using dynamic properties. This will always create a new
  * plugin instance.
  *
  * @throws InvalidArgumentException
  * @param string $pluginName
  * @return NULL|object
  */
  public function __get($pluginName) {
    return $this->get($pluginName);
  }

  /**
  * Getter/setter the module options object of the given plugin.
  *
  * @param string $pluginName
  * @param PapayaConfiguration $options
  * @return NULL|PapayaConfiguration
  */
  public function options($pluginName, PapayaConfiguration $options = NULL) {
    if ($this->has($pluginName)) {
      if (isset($options)) {
        $this->_options[$pluginName] = $options;
      } elseif (!isset($this->_options[$pluginName])) {
        $this->_options[$pluginName] = $this
          ->loader()
          ->options[$this->_plugins[$pluginName]];
      }
      return $this->_options[$pluginName];
    }
    return NULL;
  }

  /**
  * Read an single option of the given plugin.
  *
  * @param string $pluginName
  * @param string $optionName
  * @param mixed $default
  * @param PapayaFilter $filter
  * @return mixed
  */
  public function getOption($pluginName, $optionName, $default = NULL, $filter = NULL) {
    if ($options = $this->options($pluginName)) {
      return $options->get($optionName, $default, $filter);
    }
    return $default;
  }
}