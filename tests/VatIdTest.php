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

class VatIdTest extends TestBase
{
	/**
	 * Checks if assigning valid vatId from string works
	 */
	public function testVatIdValidDataString()
	{
		for ($i = 0; $i < 10; $i++) {
			$builder = new Builder(Builder::TYPE_COMPANY);
			$vat_id = $this->getRandomDigitsString(10);
			$builder->vatId($vat_id);
			$this->assertEquals($vat_id, $this->getProtectedMember($builder, 'vat_id'));
		}
	}

	/**
	 * Checks if assigning valid vatId from int works
	 */
	public function testVatIdValidDataInt()
	{
		for ($i = 0; $i < 10; $i++) {
			$builder = new Builder(Builder::TYPE_COMPANY);

			$vat_id = mt_rand(0, 9999999999);
			$builder->vatId($vat_id);

			$vat_id_string = sprintf('%010d', $vat_id);
			$this->assertEquals($vat_id_string, $this->getProtectedMember($builder, 'vat_id'));
		}
	}

	/**
	 * Checks if (optional) dash characters are filtered out properly.
	 */
	public function testVatIdFilterDash()
	{
		$ids = [
			'012-345-67-89',
			'012-34-567-89',
			'0123456789-----',
		];

		foreach ($ids as $id) {
			$builder = new Builder(Builder::TYPE_COMPANY);
			$builder->vatId($id);
			$this->assertEquals(str_replace('-', '', $id), $this->getProtectedMember($builder, 'vat_id'));
		}
	}

	/**
	 * Setting empty VatID for TYPE_COMPANY is not allowed.
	 *
	 * @param $vat_id
	 *
	 * @dataProvider vatIdInvalidDataEmptyVatForCompanyProvider
	 */
	public function testVatIdInvalidDataEmptyVatForCompany($vat_id)
	{
		$builder = new Builder(Builder::TYPE_COMPANY);
		$this->expectException(\RuntimeException::class);
		$builder->vatId($vat_id);
	}

	public function vatIdInvalidDataEmptyVatForCompanyProvider()
	{
		return [
			[''],
			[null],
		];
	}

	/**
	 * Setting empty VatID for TYPE_PERSON is allowed.
	 */
	public function testVatIdInvalidDataEmptyVatForPerson()
	{
		$vat_ids = [
			'',
			null,
		];

		foreach ($vat_ids as $vat_id) {
			$builder = new Builder(Builder::TYPE_PERSON);
			$builder->vatId($vat_id);
			$this->assertEquals('', $this->getProtectedMember($builder, 'vat_id'));
		}
	}

	/**
	 * @param $vatId
	 *
	 * @dataProvider vatIdInvalidDataInvalidLengthProvider
	 */
	public function testVatIdInvalidDataInvalidLength($vatId)
	{
		$builder = new Builder(Builder::TYPE_COMPANY);
		$this->expectException(\InvalidArgumentException::class);
		$builder->vatId($vatId);
	}

	public function vatIdInvalidDataInvalidLengthProvider()
	{
		$vatIds = [];

		// too short
		for ($i = 1; $i < 10; $i++) {
			$vatIds[] = [$this->getRandomDigitsString(mt_rand(1, $i))];
		}

		// too long
		for ($i = 1; $i < 10; $i++) {
			$vatIds[] = [$this->getRandomDigitsString(mt_rand(11, 100))];
		}

		return $vatIds;
	}

	public function testVatIdInvalidDataInvalidCharacters()
	{
		$builder = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$builder->vatId($this->getRandomAlphaString(10));
	}

	public function testVatIdDataTypeInvalid()
	{
		$builder = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		/** @noinspection PhpParamsInspection */
		$builder->vatId([]);
	}
}
