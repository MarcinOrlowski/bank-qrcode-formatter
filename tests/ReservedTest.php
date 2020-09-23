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
	 * Checks if setting valid reserved1 field works.
	 */
	public function testReserved1()
	{
		$id = $this->getRandomString(null, Builder::RESERVED1_MAX_LEN);
		$b = new Builder();
		$b->reserved1($id);
		$this->assertEquals($id, $this->getProtectedMember($b, 'reserved1'));
	}

	/**
	 * Tests if providing invalid data type as reserved1 throws expected exception
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
	 * Checks if setting valid reserved1 field via refId() alias method works.
	 */
	public function testRefId()
	{
		$id = $this->getRandomString(null, Builder::RESERVED1_MAX_LEN);
		$b = new Builder();
		$b->refId($id);
		$this->assertEquals($id, $this->getProtectedMember($b, 'reserved1'));
	}

	/**
	 * Tests if providing invalid data type as reserved1 via refId() alias method throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider reservedInvalidDataTypeProvider
	 */
	public function testRefIdInvalidDataType($id)
	{
		$b = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$b->refId($id);
	}

	/**
	 * Tests if providing invalid data type as reserved2 throws expected exception
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
	 * Tests if providing invalid data type as reserved3 throws expected exception
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
	public function testReservedXEmpty()
	{
		for ($i = 1; $i <= 3; $i++) {
			$field = "reserved{$i}";

			$b = new Builder();
			$b->$field(null);
			$this->assertEquals('', $this->getProtectedMember($b, $field));
		}
	}

	/**
	 * Checks if passing too long value to reserved1() triggers exception abort.
	 */
	public function testReserved1TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED1_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved1($this->getRandomString(null, $max_len + 1));
	}

	/**
	 * Checks if passing too long value to reserved2() triggers exception abort.
	 */
	public function testReserved2TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED2_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved2($this->getRandomString(null, $max_len + 1));
	}

	/**
	 * Checks if passing too long value to reserved3() triggers exception abort.
	 */
	public function testReserved3TooLong()
	{
		$b = new Builder();
		$max_len = $this->getProtectedConstant($b, 'RESERVED3_MAX_LEN');

		$this->expectException(\InvalidArgumentException::class);
		$b->reserved3($this->getRandomString(null, $max_len + 1));
	}

}
