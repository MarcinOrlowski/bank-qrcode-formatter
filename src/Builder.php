<?php

namespace MarcinOrlowski\QrcodeFormatter;

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

use InvalidArgumentException;
use RuntimeException;

class Builder
{
    /** @var int */
    const TYPE_COMPANY = 1;

    /** @var int */
    const TYPE_PERSON = 2;

    /** @var string */
    protected $separator = '|';

    /** @var int Maks. allowed length of result string */
    const MAX_LEN = 160;


    /**
     * Builder constructor.
     *
     * @param int  $recipient_type
     * @param bool $strict_mode
     */
    public function __construct($recipient_type = self::TYPE_PERSON, $strict_mode = false)
    {
        if ($recipient_type !== self::TYPE_COMPANY && $recipient_type !== self::TYPE_PERSON) {
            throw new \InvalidArgumentException('Invalid recipient type specified.');
        }

        $this->recipient_type = $recipient_type;
        $this->strictMode($strict_mode);
    }

    /** @var bool */
    protected $strict_mode = false;

    /**
     * Controls strict mode. When mode is disabled (default) some methods may trim down string
     * arguments exceeding max allowed length. With strict mode on, such case would throw
     * InvalidArgumentException.
     *
     * @param bool $mode Set to @true to enable strict mode, @false (default) otherwise.
     */
    public function strictMode($mode)
    {
        if (!\is_bool($mode)) {
            throw new InvalidArgumentException('Mode argument must be a boolean.');
        }

        $this->strict_mode = (bool)$mode;
    }

    /** @var int */
    protected $recipient_type = self::TYPE_PERSON;

    /** @var string VAT ID (10 chars). required for TYPE_COMPANY (VAT ID), optional otherwise, digits */
    protected $vat_id = '';

    /**
     * Sets recipient Vat ID
     *
     * @param string|int|null $vat_id
     *
     * @return $this
     */
    public function vatId($vat_id)
    {
        $this->vat_id = $this->validateVatId($vat_id);

        return $this;
    }

    /**
     * @param string|int|null $vat_id
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function validateVatId($vat_id)
    {
        if (is_string($vat_id)) {
            $vat_id = \trim(\str_replace('-', '', $vat_id));
        } elseif ($vat_id === null) {
            $vat_id = '';
        } elseif (\is_int($vat_id)) {
            $vat_id = \sprintf('%010d', $vat_id);
        } else {
            throw new \InvalidArgumentException('VatId can either be a string, int or null.');
        }

        if ($vat_id !== '') {
            if (\preg_match('/^\d{10}$/', $vat_id) !== 1) {
                throw new \InvalidArgumentException(
                    "Invalid VAT ID set. Must be contain 10 chars, digits only. '{$vat_id}' provided.");
            }
        }

        if ($this->recipient_type === self::TYPE_COMPANY && $vat_id === '') {
            throw new \InvalidArgumentException('Company recipient must have VAT ID set.');
        }

        return $vat_id;
    }

    /** @var string Recipient bank account number (26 digits), mandatory */
    protected $bank_account = '';

    /**
     * Sets mandatory recipient routing bank account number. Account number must contain 26 digits.
     * Digits can be grouped and separated by spaces however all spaces will be removed.
     *
     * @param string $account Recipient bank account number (26 digits)
     *
     * @return $this
     */
    public function bankAccount($account)
    {
        $this->bank_account = $this->validateBankAccount($account);

        return $this;
    }

    /**
     * @param string $account
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function validateBankAccount($account)
    {
        if (!\is_string($account)) {
            throw new \InvalidArgumentException('Bank account number must be a string.');
        }
        $account = \str_replace(' ', '', $account);

        if (\preg_match('/^\d{26}$/', $account) !== 1) {
            throw new \InvalidArgumentException(
                "Bank account number must be 26 chars long, digits only. '{$account}' provided.");
        }

        return $account;
    }

    /** @var string 20 chars max, recipient name, mandatory */
    protected $recipient_name = '';

    /** @var int */
    const NAME_MAX_LEN = 20;

    /**
     * Sets recipient name. Up to 20 chars (longer strings are allowed and will be trimmed).
     *
     * @param string $name recipient name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->recipient_name = $this->validateName($name);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function validateName($name)
    {
        if (!\is_string($name)) {
            throw new InvalidArgumentException('Recipient name must be a string.');
        }

        if ($this->strict_mode && \mb_strlen($name) > self::NAME_MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Recipient name must not exceed %d chars.', self::NAME_MAX_LEN));
        }

        $name = \mb_substr(\trim($name), 0, self::NAME_MAX_LEN);

        if ($name === '') {
            throw new \InvalidArgumentException('Recipient name cannot be empty.');
        }

        return $name;
    }

    /** @var string 2 chars, country code (i.e. 'PL'), optional, letters */
    protected $country_code = '';

    /**
     * @param string|null $country_code 2 chars, country code (i.e. 'PL'), optional, letters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function country($country_code)
    {
        if ($country_code === null) {
            $country_code = '';
        }

        if (!\is_string($country_code)) {
            throw new \InvalidArgumentException('Country code must be a string.');
        }

        $country_code = \mb_strtoupper($country_code);
        if ($country_code !== '') {
            if (\preg_match('/^[A-Z]{2}$/', $country_code) !== 1) {
                $exMsg = \sprintf("Country code must be a 2 character long, letters only. '%s' provided.", $country_code);
                throw new \InvalidArgumentException($exMsg);
            }
        }

        $this->country_code = \strtoupper($country_code);

        return $this;
    }

    /** @var string */
    protected $payment_title = '';

    /** @var int */
    const TITLE_MAX_LEN = 32;

    /**
     * @param string $title 32 chars, payment title, mandatory, letters+digits
     *
     * @return $this
     */
    public function title($title)
    {
        $this->payment_title = $this->validateTitle($title);

        return $this;
    }

    /**
     * @param string $title
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function validateTitle($title)
    {
        if (!\is_string($title)) {
            throw new \InvalidArgumentException('Payment title must be a string.');
        }

        if ($this->strict_mode && \mb_strlen($title) > self::TITLE_MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Payment title must not exceed %d chars.', self::TITLE_MAX_LEN));
        }

        if ($title === '') {
            throw new \InvalidArgumentException('Payment title cannot be empty.');
        }

        return \mb_substr(\trim($title), 0, self::TITLE_MAX_LEN);
    }

    /** @var int|null */
    protected $amount = null;

    /**
     * @param float|int $amount 6 chars, amount in Polish grosz, digits, mandatory
     *
     * @return $this
     */
    public function amount($amount)
    {
        $this->amount = $this->validateAmount($amount);

        return $this;
    }

    /**
     * @param float|int $amount
     *
     * @return int
     *
     * @throws \OutOfRangeException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function validateAmount($amount)
    {
        if ($amount === null) {
            throw new RuntimeException('Amount not specified.');
        }

        if (\is_float($amount)) {
            $amount = (int)($amount * 100);
        } elseif (!\is_int($amount)) {
            throw new \InvalidArgumentException('Amount must be either float or int');
        }

        if ($amount < 0) {
            throw new \OutOfRangeException('Amount cannot be negative.');
        }

        if ($amount > 999999) {
            throw new \OutOfRangeException('Amount representation cannot exceed 6 digits. Current value: {$amount}');
        }

        return $amount;
    }

    /** @var string */
    protected $reserved1 = '';

    /** @var int */
    const RESERVED1_MAX_LEN = 20;

    /**
     * @param string|null $id 20 chars, reserved i.e. for payment reference id, optional, digits
     *                        (but we use letters+digits as some banks do too)
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function reserved1($id)
    {
        if ($id === null) {
            $id = '';
        }

        if (!\is_string($id)) {
            throw new \InvalidArgumentException('Reserved1/RefId value must be a string.');
        }

        if (\mb_strlen($id) > self::RESERVED1_MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Maksymalna długość wartości Reserved1/RefId to %d znaków.', self::RESERVED1_MAX_LEN));
        }

        $this->reserved1 = $id;

        return $this;
    }

    /**
     * Alias for reserved1()
     *
     * @param string|null $id
     *
     * @return $this
     */
    public function refId($id)
    {
        return $this->reserved1($id);
    }

    /** @var string */
    protected $reserved2 = '';

    /** @var int */
    const RESERVED2_MAX_LEN = 12;

    /**
     * 12 chars, reserved i.e. for Invobill reference id, optional, digits (but we allow
     * letters+digits as some banks do too)
     *
     * @param string|null $id
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function reserved2($id)
    {
        if ($id === null) {
            $id = '';
        }

        if (!\is_string($id)) {
            throw new \InvalidArgumentException('Reserved2 value must be a string.');
        }

        if (\mb_strlen($id) > self::RESERVED2_MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Maksymalna długość wartości Reserved2 to %d znaków.', self::RESERVED2_MAX_LEN));
        }

        $this->reserved2 = $id;

        return $this;
    }

    /** @var string */
    protected $reserved3 = '';

    /** @var int */
    const RESERVED3_MAX_LEN = 24;

    /**
     * 24 chars, reserved, optional, letters+digits
     *
     * @param string|null $id
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function reserved3($id)
    {
        if ($id === null) {
            $id = '';
        }

        if (!\is_string($id)) {
            throw new \InvalidArgumentException('Reserved3 value must be a string.');
        }

        if (\mb_strlen($id) > self::RESERVED3_MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Maksymalna długość wartości Reserved3 to %d znaków.', self::RESERVED3_MAX_LEN));
        }

        $this->reserved3 = $id;

        return $this;
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function build()
    {
        // validate
        $this->validateBankAccount($this->bank_account);
        $this->validateName($this->recipient_name);
        $this->validateVatId($this->vat_id);
        $this->validateTitle($this->payment_title);
        $this->validateAmount($this->amount);

        // build
        $fields = [
            $this->vat_id,
            $this->country_code,
            $this->bank_account,
            \sprintf('%06d', $this->amount),
            $this->recipient_name,
            $this->payment_title,
            $this->reserved1,
            $this->reserved2,
            $this->reserved3,
        ];

        $result = \implode($this->separator, $fields);
        if (\mb_strlen($result) > self::MAX_LEN) {
            throw new \InvalidArgumentException(
                \sprintf('Oops! Result string is %d chars long (max allowed %d). Please report this!',
                    \mb_strlen($result), self::MAX_LEN));
        }

        return $result;
    }
}
