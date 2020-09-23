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

class BuildTest extends TestBase
{
	protected $fields = [
		'type',
		'vatId',
		'bankAccount',
		'name',
		'country',
		'title',
		'amount',
		'reserved1',
		'reserved2',
		'reserved3',
		'result',
	];

	/**
	 * Validates if build() produces expected output.
	 *
	 * @param $data
	 *
	 * @dataProvider dataProvider
	 */
	public function testBuild($data)
	{
		$type = array_key_exists('type', $data) ? $data['type'] : null;
		if ($type === null) {
			$b = new Builder();
		} else {
			$b = new Builder($type);
			unset($data['type']);
		}

		$expected = $data['result'];
		unset($data['result']);

		foreach ($this->fields as $field) {
			if (array_key_exists($field, $data)) {
				$val = $data[ $field ];
				if ($val !== null) {
					$b->$field($val);
				}
			}
		}

		$result = $b->build();
		$this->assertEquals($expected, $result);
	}

	public function dataProvider()
	{
		return [
			[[
				 'type'        => Builder::TYPE_PERSON,
				 'bankAccount' => '01234567890123456789012345',
				 'name'        => 'Acme Inc.',
				 'country'     => 'PL',
				 'title'       => 'Payment title',
				 'amount'      => 12399,
				 'result'      => '|PL|01234567890123456789012345|012399|Acme Inc.|Payment title|||',
			 ]],

			[[
				 'type'        => Builder::TYPE_COMPANY,
				 'bankAccount' => '01234567890123456789012345',
				 'vatId'       => '0123456789',
				 'name'        => 'Acme Inc.',
				 'country'     => 'DE',
				 'title'       => 'Other payment title',
				 'amount'      => 312.99,
				 'result'      => '0123456789|DE|01234567890123456789012345|031299|Acme Inc.|Other payment title|||',
			 ]],

			//			[[
			//				 'type'        => null,
			//				 'vatId'       => null,
			//				 'bankAccount' => null,
			//				 'name'        => null,
			//				 'country'     => null,
			//				 'title'       => null,
			//				 'amount'      => null,
			//				 'reserved1'   => null,
			//				 'reserved2'   => null,
			//				 'reserved3'   => null,
			//				 'result'      => '',
			//			 ]],

		];
	}


	/**
	 * Checks if attempt to build() for TYPE_COMPANY without Vat Id specified
	 * throws expected exception.
	 */
	public function testBuildCompanyNoVat()
	{
		$this->expectException('\RuntimeException');
		$b = new Builder(Builder::TYPE_COMPANY);
		$b->bankAccount('01234567890123456789012345')
			->name('ACME Inc')
			->title('Payment')
			->amount(100)
			->build();
	}

	/**
	 * Ensure we catch the (very unlike) case when formatted string
	 * is too long. It should never happen in real life, but to trigger
	 * this we put more than one character as field separator - this
	 * not user manipulable via current api, so this is synthetic test.
	 */
	public function testBuildResultTooLong()
	{
		$b = new Builder(Builder::TYPE_PERSON);

		$this->setProtectedMember($b, 'separator', 'XXX');

		$this->expectException('\RuntimeException');
		$b->bankAccount('01234567890123456789012345')
			->vatId($this->getRandomDigitsString(10))
			->name($this->getRandomAlphaString(20))
			->country('PL')
			->title($this->getRandomAlphaString(32))
			->amount(100)
			->reserved1($this->getRandomDigitsString(20))
			->reserved2($this->getRandomDigitsString(12))
			->reserved3($this->getRandomDigitsString(24))
			->build();
	}

}
