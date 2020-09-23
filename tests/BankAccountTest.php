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

class BankAccountTest extends TestBase
{
	/**
	 * Checks if setting valid bank account works.
	 */
	public function testBankAccountTest()
	{
		$id = $this->getRandomDigitsString(26);
		$b = new Builder();
		$b->bankAccount($id);
		$this->assertEquals($id, $this->getProtectedMember($b, 'bank_account'));
	}

	/**
	 * Checks if separating spaces are properly handled and stripped.
	 */
	public function testBankAccountTestSpaceStrip()
	{
		// How many spaces we want to inject
		$spaces = mt_rand(1, 10);
		$id = $this->getRandomDigitsString(26 + $spaces);

		// Choose random positions for our spaces
		$pos = [];
		for ($i = 0; $i < $spaces; $i++) {
			do {
				$tmp = mt_rand(0, 25);
			} while (in_array($tmp, $pos));
			$pos[] = $tmp;
		}

		// Put our spaces
		foreach ($pos as $p) {
			$id{$p} = ' ';
		}

		$b = new Builder();
		$b->bankAccount($id);
		$this->assertEquals(str_replace(' ', '', $id), $this->getProtectedMember($b, 'bank_account'));
	}

	/**
	 * Tests if providing invalid data type as bank account throws expected exception
	 *
	 * @dataProvider bankAccountInvalidDataProvider
	 */
	public function testBankAccountInvalidData($id)
	{
		$this->expectException('\InvalidArgumentException');
		$b = new Builder();
		$b->bankAccount($id);
	}

	public function bankAccountInvalidDataProvider()
	{
		return [
			[false],
			[null],
			[[]],
			[432],
		];
	}

	/**
	 * @dataProvider bankAccountInvalidLengthProvider
	 */
	public function testBankAccountInvalidLength($id)
	{
		$this->expectException('\InvalidArgumentException');
		$b = new Builder();
		$b->bankAccount($id);
	}

	public function bankAccountInvalidLengthProvider()
	{
		$ids = [];

		// too short
		for ($i = 0; $i < 10; $i++) {
			$ids[] = [$this->getRandomDigitsString(mt_rand(1, 25))];
		}

		// too long
		for ($i = 0; $i < 10; $i++) {
			$ids[] = [$this->getRandomDigitsString(mt_rand(27, 100))];
		}

		return $ids;
	}
}
