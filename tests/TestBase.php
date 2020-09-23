<?php

namespace MarcinOrlowski\QrcodeFormatter\Tests;

/**
 * Bank QrCode Formatter
 *
 * @package   MarcinOrlowski\QrcodeFormatter
 *
 * @author    Marcin Orlowski <mail (#) marcinOrlowski (.) com>
 * @copyright 2020 Marcin Orlowski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/MarcinOrlowski/bank-qrcode-formatter
 */

use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase
{
	/**
	 * Calls protected method of $object, passing optional array of arguments.
	 *
	 * @param object|string $obj_or_class Object to call $method_name on or name of the class.
	 * @param string        $method_name  Name of method to called.
	 * @param array         $args         Optional array of arguments (empty array if no args to pass).
	 *
	 * @return mixed
	 *
	 * @throws \ReflectionException
	 * @throws \RuntimeException
	 */
	protected function callProtectedMethod($obj_or_class, $method_name, array $args = null)
	{
		if ($args === null) {
			$args = [];
		}

		if (\is_object($obj_or_class)) {
			$obj = $obj_or_class;
		} elseif (\is_string($obj_or_class)) {
			$obj = $obj_or_class;
		} else {
			throw new \RuntimeException('getProtectedMethod() expects object or valid class name argument');
		}

		$reflection = new \ReflectionClass($obj);
		$method = $reflection->getMethod($method_name);
		$method->setAccessible(true);

		return $method->invokeArgs(\is_object($obj) ? $obj : null, $args);
	}

	/**
	 * Returns value of otherwise non-public member of the class
	 *
	 * @param string|object $cls  class name to get member from, or instance of that class
	 * @param string        $name member name to grab (i.e. `max_length`)
	 *
	 * @return mixed
	 *
	 * @throws \ReflectionException
	 */
	protected function getProtectedMember($cls, $name)
	{
		$reflection = new \ReflectionClass($cls);
		$property = $reflection->getProperty($name);
		$property->setAccessible(true);

		return $property->getValue($cls);
	}

	protected function setProtectedMember($cls, $name, $val)
	{
		$reflection = new \ReflectionClass($cls);
		$property = $reflection->getProperty($name);
		$property->setAccessible(true);

		$property->setValue($cls, $val);
	}

	/**
	 * Returns value of otherwise non-public member of the class
	 *
	 * @param string|object $cls  class name to get member from, or instance of that class
	 * @param string        $name name of constant to grab (i.e. `FOO`)
	 *
	 * @return mixed
	 * @throws \ReflectionException
	 */
	protected function getProtectedConstant($cls, $name)
	{
		$reflection = new \ReflectionClass($cls);

		return $reflection->getConstant($name);
	}

	/**
	 * Generates random string, with optional prefix
	 *
	 * @param string $prefix
	 * @param int    $max_len
	 *
	 * @return string
	 */
	protected function getRandomString($prefix = null, $max_len = 32)
	{
		if ($prefix === '') {
			$prefix = null;
		}

		if ($prefix !== null) {
			$prefix = "{$prefix}_";
		}

		return mb_substr($prefix . \md5(uniqid(\mt_rand(), true)), 0, $max_len);
	}

	protected function getRandomDigitsString($len = 10)
	{
		$result = '';
		for ($digit = 0; $digit < $len; $digit++) {
			$result .= chr(ord('0') + mt_rand(0, 9));
		}

		return $result;
	}

	protected function getRandomAlphaString($len = 10)
	{
		$result = '';
		for ($char = 0; $char < $len; $char++) {
			$result .= chr(ord('A') + mt_rand(0, 25));
		}

		return $result;
	}
}
