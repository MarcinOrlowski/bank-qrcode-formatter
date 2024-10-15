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

class AmountTest extends TestBase
{
	/**
	 * Checks if setting valid amount works.
	 *
	 * @dataProvider amountProvider
	 *
	 * @param string $amount
	 *
	 * @throws \ReflectionException
	 */
	public function testAmount($amount)
	{
		if (is_float($amount)) {
			$amount_grosz = (int)($amount * 100);
		} else {
			$amount_grosz = $amount;
		}

		$b = new Builder();
		$b->amount($amount);
		$this->assertEquals($amount_grosz, $this->getProtectedMember($b, 'amount'));
	}

	public function amountProvider()
	{
		$amounts = [];

		// ints
		for ($i = 0; $i < 10; $i++) {
			$amounts[] = [mt_rand(0, 999999)];
		}

		// random doubles, lame way
		for ($i = 0; $i < 10; $i++) {
			$a = mt_rand(0, 9999);
			$b = mt_rand(0, 99);
			$amounts[] = [(float)sprintf('%d.%02d', $a, $b)];
		}

		return $amounts;
	}

	/**
	 * Checks if passing empty string or null to clear country code works.
	 *
	 * @param $amount
	 *
	 * @dataProvider amountOutOfBoundProvider
	 */
	public function testAmountOutOfBounds($amount)
	{
		$b = new Builder();
		$this->expectException(\OutOfRangeException::class);
		$b->amount($amount);
	}

	public function amountOutOfBoundProvider()
	{
        $amount_str = '';
        for($i = 0; $i < Builder::MAX_AMOUNT_LEN + 1; $i++) {
            $amount_str .= mt_rand(0, 9);
        }
		return [
            [\floatval($amount_str)],
		];
	}

	/**
	 * Tests if providing invalid data type as amount throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider amountInvalidDataTypeProvider
	 */
	public function testAmountInvalidDataType($id)
	{
		$b = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$b->amount($id);
	}

	public function amountInvalidDataTypeProvider()
	{
		return [
			[false],
			[[]],
		];
	}

	/**
	 * Checks if null as amount throws expected exception.
	 */
	public function testAmountNull()
	{
		$b = new Builder();
		$this->expectException(\RuntimeException::class);
		$b->amount(null);
	}

}
