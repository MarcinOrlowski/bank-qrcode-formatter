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

class CountryTest extends TestBase
{
	/**
	 * Checks if setting valid bank account works.
	 */
	public function testCountry()
	{
		for ($i = 0; $i < 10; $i++) {
			$code = $this->getRandomAlphaString(2);
			$b = new Builder();
			$b->country($code);
			$this->assertEquals(mb_strtoupper($code), $this->getProtectedMember($b, 'country_code'));
		}
	}

	/**
	 * Tests if passing invalid, too short or too long code throws expected exception.
	 *
	 * @param $code
	 *
	 * @dataProvider countryInvalidProvider
	 */
	public function testCountryInvalid($code)
	{
		$this->expectException('\InvalidArgumentException');
		$b = new Builder();
		$b->country($code);
	}

	public function countryInvalidProvider()
	{
		return [
			[$this->getRandomDigitsString(2)],
			[$this->getRandomAlphaString(10)],
			[$this->getRandomAlphaString(1)],
		];
	}

	/**
	 * Checks if passing empty string or null to clear country code works.
	 */
	public function testCountryEmpty()
	{
		$codes = ['',
		          null];

		foreach ($codes as $code) {
			$b = new Builder();
			$b->country($code);
			$this->assertEquals('', $this->getProtectedMember($b, 'country_code'));
		}
	}

	/**
	 * Tests if providing invalid data type as country code throws expected exception
	 *
	 * @param $id
	 *
	 * @dataProvider countryInvalidDataTypeProvider
	 */
	public function testCountryInvalidDataType($id)
	{
		$this->expectException('\InvalidArgumentException');
		$b = new Builder();
		$b->country($id);
	}

	public function countryInvalidDataTypeProvider()
	{
		return [
			[false],
			[[]],
			[432],
		];
	}

}
