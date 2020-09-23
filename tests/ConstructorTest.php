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

class ConstructorTest extends TestBase
{
	/**
	 * Checks if constructing builder with valid recipient type works.
	 */
	public function testConstructor()
	{
		$types = [
			Builder::TYPE_COMPANY,
			Builder::TYPE_COMPANY,
		];
		foreach ($types as $type) {
			$builder = new Builder($type);
			$this->assertEquals($type, $this->getProtectedMember($builder, 'recipient_type'));
		}
	}

	/**
	 * Checks if constructing builder with invalid recipient type throws expected exception.
	 */
	public function testConstructorInvalidType()
	{
		$this->expectException('\RuntimeException');
		new Builder('foo');
	}
}
