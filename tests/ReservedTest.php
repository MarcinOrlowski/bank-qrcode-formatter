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

class ReservedTest extends TestBase
{
	/**
	 * Checks if setting valid bank account works.
	 */
	public function testReserved()
	{
		$name = $this->getRandomString(null, 32);
		$b = new Builder();
		$b->title($name);
		$this->assertEquals($name, $this->getProtectedMember($b, 'payment_title'));
	}

	/**
	 * Tests if providing invalid data type as reserved1 name throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider reservedInvalidDataTypeProvider
	 */
	public function testReserved1InvalidDataType($id)
	{
		$b = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$b->reserved1($id);
	}

	/**
	 * Tests if providing invalid data type as reserved2 name throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider reservedInvalidDataTypeProvider
	 */
	public function testReserved2InvalidDataType($id)
	{
		$this->expectException(\InvalidArgumentException::class);
		$b = new Builder();
		$b->reserved2($id);
	}

	/**
	 * Tests if providing invalid data type as reserved3 name throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider reservedInvalidDataTypeProvider
	 */
	public function testReserved3InvalidDataType($id)
	{
		$b = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$b->reserved3($id);
	}

	public function reservedInvalidDataTypeProvider()
	{
		return [
			[false],
			[[]],
			[432],
		];
	}

	/**
	 * Checks if setting reservedX fields to `null` clears them correctly.
	 */
	public function testNameEmpty()
	{
		for ($i = 1; $i <= 3; $i++) {
			$field = "reserved{$i}";

			$b = new Builder();
			$b->$field(null);
			$this->assertEquals('', $this->getProtectedMember($b, $field));
		}
	}

	public function testReserved1TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED1_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved1($this->getRandomString(null, $max_len + 1));
	}

	public function testReserved2TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED2_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved2($this->getRandomString(null, $max_len + 1));
	}

	public function testReserved3TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED3_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved3($this->getRandomString(null, $max_len + 1));
	}

}
