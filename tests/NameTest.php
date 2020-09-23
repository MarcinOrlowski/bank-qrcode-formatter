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
		$name = $this->getRandomString(null, 20);
		$b = new Builder();
		$b->name($name);
		$this->assertEquals($name, $this->getProtectedMember($b, 'recipient_name'));
	}

	/**
	 * Checks if names longer than 20 chars are trimmed.
	 */
	public function testNameTrim()
	{
		$name = $this->getRandomString(null, 32);
		$b = new Builder();
		$b->name($name);
		$this->assertEquals(mb_substr($name, 0, 20), $this->getProtectedMember($b, 'recipient_name'));
	}

	/**
	 * Tests if providing invalid data type as recipient name throws expected exception
	 *
	 * @dataProvider nameInvalidDataTypeProvider
	 */
	public function testNameInvalidDataType($id)
	{
		$this->expectException('\InvalidArgumentException');
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
		$this->expectException('\RuntimeException');
		$b = new Builder();
		$b->name('');
	}

}
