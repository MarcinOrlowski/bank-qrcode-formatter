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

use MarcinOrlowski\QrcodeFormatter\Builder;

class NameTest extends TestBase
{
	/**
	 * Checks if setting valid bank account works.
	 */
	public function testName()
	{
		$name = $this->getRandomString(null, Builder::NAME_MAX_LEN);
		$b = new Builder();
		$b->name($name);
		$this->assertEquals($name, $this->getProtectedMember($b, 'recipient_name'));
	}

	/**
	 * Checks if names longer than NAME_MAX_LEN chars are trimmed.
	 */
	public function testNameTrim()
	{
		$name = $this->getRandomString(null, 32);
		$b = new Builder();
		$b->name($name);
		$this->assertEquals(mb_substr($name, 0, Builder::NAME_MAX_LEN), $this->getProtectedMember($b, 'recipient_name'));
	}

	/**
	 * Tests if providing invalid data type as recipient name throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider nameInvalidDataTypeProvider
	 */
	public function testNameInvalidDataType($id)
	{
		$this->expectException(\InvalidArgumentException::class);
		$b = new Builder();
		$b->name($id);
	}

	public function nameInvalidDataTypeProvider()
	{
		return [
			[false],
			[null],
			[[]],
			[432],
		];
	}

	/**
	 * Checks if attempt to set empty string as recipient name would throw expected exception.
	 */
	public function testNameEmpty()
	{
		$this->expectException(\RuntimeException::class);
		$b = new Builder();
		$b->name('');
	}

	/**
	 * Checks if strict_mode is honoured by name() and throws exception for too long arguments.
	 */
	public function testNameStrictMode()
	{
		$b = new Builder();
		$b->strictMode(true);
		$this->expectException(\InvalidArgumentException::class);
		$b->name($this->getRandomAlphaString(Builder::NAME_MAX_LEN+1));
	}

}
