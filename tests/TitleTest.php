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

class TitleTest extends TestBase
{
	/**
	 * Checks if setting valid bank account works.
	 */
	public function testTitle()
	{
		$name = $this->getRandomString(null, 32);
		$b = new Builder();
		$b->title($name);
		$this->assertEquals($name, $this->getProtectedMember($b, 'payment_title'));
	}

	/**
	 * Checks if titles longer than 32 chars are trimmed.
	 */
	public function testTitleTrim()
	{
		$name = $this->getRandomString(null, 32);
		$name .= $name;
		$b = new Builder();
		$b->title($name);
		$this->assertEquals(mb_substr($name, 0, 32), $this->getProtectedMember($b, 'payment_title'));
	}

	/**
	 * Tests if providing invalid data type as recipient name throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider titleInvalidDataTypeProvider
	 */
	public function testTitleInvalidDataType($id)
	{
		$b = new Builder();
		$this->expectException('\InvalidArgumentException');
		$b->title($id);
	}

	public function titleInvalidDataTypeProvider()
	{
		return [
			[false],
			[null],
			[[]],
			[432],
		];
	}

	/**
	 * Checks if attempt to set empty string as payment title would throw expected exception.
	 */
	public function testNameEmpty()
	{
		$b = new Builder();
		$this->expectException('\RuntimeException');
		$b->title('');
	}

}
