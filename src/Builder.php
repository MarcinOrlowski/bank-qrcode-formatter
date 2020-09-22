<?php

namespace MarcinOrlowski\QrcodeFormatter;

class Builder
{
	/** @var int */
	const TYPE_COMPANY = 1;

	/** @var int */
	const TYPE_PERSON  = 2;

	/** @var string */
	protected $separator = '|';

	/** @var int */
	protected $max_length = 160;

	/** @var int */
	protected $recipient_type = self::TYPE_PERSON;

	/**
	 * @param int $type
	 *
	 * @return $this
	 */
	public function type($type)
	{
		$this->recipient_type = $type;

		return $this;
	}

	// VAT ID (10 chars). required for TYPE_COMPANY (VAT ID), optional otherwise, digits

	/** @var string */
	protected $recipient_id = '';

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function recipientId($id)
	{
		$this->recipient_id = str_replace('-', '', $id);

		return $this;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function vatId($id)
	{
		return $this->recipientId($id);
	}

	// recipient bank account number (26 chars), digits
	protected $recipient_account = '';

	/**
	 * @param string $account
	 *
	 * @return $this
	 */
	public function account($account)
	{
		$this->recipient_account = str_replace(' ', '', $account);

		return $this;
	}

	/** @var string */
	protected $recipient_name = '';

	/**
	 * @param string $name 20 chars, recipient name, mandatory, letters+digits
	 *
	 * @return $this
	 */
	public function name($name)
	{
		$this->recipient_name = $name;

		return $this;
	}

	/** @var string */
	protected $recipient_country_code = 'PL';

	/**
	 * @param string $country_code 2 chars, country code (i.e. 'PL'), optional, letters
	 *
	 * @return $this
	 */
	public function country($country_code)
	{
		$this->recipient_country_code = $country_code;

		return $this;
	}

	/** @var string */
	protected $payment_subject = '';

	/**
	 * @param string $subject 32 chars, payment subject, mandatory, letters+digits
	 *
	 * @return $this
	 */
	public function subject($subject)
	{
		$this->payment_subject = $subject;

		return $this;
	}

	/** @var int */
	protected $amount = 0;

	/**
	 * @param float|int $amount 6 chars, amount in Polish grosz, digits
	 *
	 * @return $this
	 */
	public function amount($amount)
	{
		if (is_float($amount)) {
			$amount *= 100;
		} elseif (!is_int($amount)) {
			throw new \RuntimeException('Amount must be either float or int');
		}

		$this->amount = $amount;

		return $this;
	}

	/** @var string */
	protected $reserved1 = '';

	/**
	 * @param string $id 20 chars, reserved i.e. for payment reference id, optional, digits (but we use letters+digits as some banks do too)
	 *
	 * @return $this
	 */
	public function reserved1($id)
	{
		$this->reserved1 = (string)$id;

		return $this;
	}

	/**
	 * Alias for reserved1()
	 *
	 * @param string $id
	 *
	 * @return $this
	 */
	public function paymentId($id)
	{
		return $this->reserved1($id);
	}

	/** @var string */
	protected $reserved2 = '';

	/**
	 * 12 chars, reserved i.e. for Invobill reference id, optional, digits (but we use letters+digits as some banks do too)
	 *
	 * @param string $id
	 *
	 * @return $this
	 */
	public function reserved2($id)
	{
		$this->reserved2 = (string)$id;

		return $this;
	}

	/**
	 * Alias for reserved2()
	 *
	 * @param string $id
	 *
	 * @return $this
	 */
	public function invobill($id)
	{
		return $this->reserved2($id);
	}

	/** @var string */
	protected $reserved3 = '';

	/**
	 * 24 chars, reserved, optional, letters+digits
	 *
	 * @param string $id
	 *
	 * @return $this
	 */
	public function reserved3($id)
	{
		$this->reserved3($id);

		return $this;
	}

	/**
	 * @return string
	 */
	public function build()
	{
		// validate

		// build
		$fields = array(
			$this->recipient_id,
			$this->recipient_country_code,
			$this->recipient_account,
			$this->amount,
			$this->recipient_name,
			$this->payment_subject,
			$this->reserved1,
			$this->reserved2,
			$this->reserved3,
		);

		return implode($this->separator, $fields);
	}
}
