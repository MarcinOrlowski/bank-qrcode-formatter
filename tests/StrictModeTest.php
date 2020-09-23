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

class StrictModeTest extends TestBase
{

	public function testStrictMode()
	{
		foreach ([true,
		          false] as $mode) {
			$b = new Builder();
			$b->strictMode($mode);
			$this->assertEquals($mode, $this->getProtectedMember($b, 'strict_mode'));
		}
	}

	/**
	 * @param $mode
	 *
	 * @dataProvider strictModeInvalidDataProvider
	 */
	public function testStrictModeInvalidData($mode)
	{
		$b = new Builder();
		$this->expectException(\InvalidArgumentException::class);
		$b->strictMode($mode);

	}

	public function strictModeInvalidDataProvider()
	{
		return [
			[null],
			[''],
			[[]],
		];
	}
}
