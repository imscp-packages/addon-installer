<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 * Copyright (C) 2013 - 2014 Laurent Declercq <l.declercq@nuxwin.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace iMSCP\Composer;

class iMSCPConfig implements \ArrayAccess
{
	/**
	 * Configuration file path
	 *
	 * @var string
	 */
	protected $_pathFile;

	/**
	 * Loads all configuration parameters from a flat file
	 *
	 * <b>Note:</b> Default file path is set to {/usr/local}/etc/imscp/imscp.conf depending of distribution.
	 *
	 * @param string $pathFile Configuration file path
	 */
	public function __construct($pathFile = null)
	{
		if (is_null($pathFile)) {
			if (getenv('IMSCP_CONF')) {
				$pathFile = getEnv('IMSCP_CONF');
			} else {
				switch (PHP_OS) {
					case 'FreeBSD':
					case 'OpenBSD':
					case 'NetBSD':
						$pathFile = '/usr/local/etc/imscp/imscp.conf';
						break;
					default:
						$pathFile = '/etc/imscp/imscp.conf';
				}
			}
		}

		$this->_pathFile = $pathFile;
		$this->_parseFile();
	}

	/**
	 * Sets a configuration parameter.
	 *
	 * @param string $key Configuration parameter key name
	 * @param mixed $value Configuration parameter value
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->$key = $value;
	}

	/**
	 * PHP overloading on inaccessible members.
	 *
	 * @param string $key Configuration parameter key name
	 * @return mixed Configuration parameter value
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Getter method to retrieve a configuration parameter value.
	 *
	 * @throws \UnexpectedValueException
	 * @param string $key Configuration parameter key name
	 * @return mixed Configuration parameter value
	 */
	public function get($key)
	{
		if (!$this->exists($key)) {
			throw new \UnexpectedValueException("Configuration variable `$key` is missing.");
		}

		return $this->$key;
	}

	/**
	 * Deletes a configuration parameters.
	 *
	 * @param string $key Configuration parameter key name
	 * @return void
	 */
	public function del($key)
	{
		unset($this->$key);
	}

	/**
	 * Checks whether configuration parameters exists.
	 *
	 * @param string $key Configuration parameter key name
	 * @return boolean TRUE if configuration parameter exists, FALSE otherwise
	 * @todo Remove this method
	 */
	public function exists($key)
	{
		return property_exists($this, $key);
	}

	/**
	 * Replaces all parameters of this object with parameters from another.
	 *
	 * This method replace the parameters values of this object with the same values from another
	 * {@link iMSCP_Config_Handler} object.
	 *
	 * If a key from this object exists in the second object, its value will be replaced by the value from the second
	 * object. If the key exists in the second object, and not in the first, it will be created in the first object.
	 * All keys in this object that don't exist in the second object will be left untouched.
	 *
	 * <b>Note:</b> This method is not recursive.
	 *
	 * @param iMSCPConfig $config iMSCP_Config_Handler object
	 * @return bool TRUE on success, FALSE otherwise
	 */
	public function replaceWith(iMSCPConfig $config)
	{
		foreach ($config as $key => $value) {
			$this->set($key, $value);
		}

		return true;
	}

	/**
	 * Return an associative array that contains all configuration parameters.
	 *
	 * @return array Array that contains configuration parameters
	 */
	public function toArray()
	{
		$ref = new \ReflectionObject($this);
		$properties = $ref->getProperties(\ReflectionProperty::IS_PUBLIC);
		$array = array();

		foreach ($properties as $property) {
			$name = $property->name;
			$array[$name] = $this->$name;
		}

		return $array;
	}

	/**
	 * Assigns a value to the specified offset.
	 *
	 * @param mixed $offset The offset to assign the value to
	 * @param mixed $value The value to set.
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}

	/**
	 * Returns the value at specified offset.
	 *
	 * @param mixed $offset The offset to retrieve
	 * @return mixed Offset value
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Whether or not an offset exists.
	 *
	 * @param mixed $offset An offset to check for existence
	 * @return boolean TRUE on success or FALSE on failure
	 */
	public function offsetExists($offset)
	{
		return property_exists($this, $offset);
	}

	/**
	 * Unset an offset.
	 *
	 * @param  mixed $offset The offset to unset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}

	/**
	 * Opens a configuration file and parses its Key = Value pairs
	 *
	 * @throws \RuntimeException
	 * @return void
	 */
	protected function _parseFile()
	{
		if (($config = @file_get_contents($this->_pathFile)) == false) {
			throw new \RuntimeException(sprintf('Unable to read file: %s', $this->_pathFile));
		}

		foreach (explode(PHP_EOL, $config) as $line) {
			if (!empty($line) && $line[0] != '#' && strpos($line, '=')) {
				list($key, $value) = explode('=', $line, 2);
				$this[trim($key)] = trim($value);
			}
		}
	}
}
