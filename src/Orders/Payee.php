<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-payee.
 */
class Payee implements Arrayable, Jsonable
{
    use HasJson;

    /**
     * The email address of merchant.
     *
     * @var string
     */
    protected $email_address;

    /**
     * The encrypted PayPal account ID of the merchant.
     *
     * @var string
     */
    protected $merchant_id;

    /**
     * Create a new instance.
     *
     * @param string $email_address
     * @param string $merchant_id
     *
     * @return self
     */
    public function __construct($email_address, $merchant_id)
    {
        $this->email_address = $email_address;
        $this->merchant_id = $merchant_id;
    }

    /**
     * Gets payee email address.
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Sets payee email address.
     *
     * @param float $email_address
     */
    public function setEmailAddress($email_address)
    {
        $this->email_address = $email_address;

        return $this;
    }

    /**
     * Gets merchant id.
     */
    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    /**
     * Sets payee merchant id.
     *
     * @param string $merchant_id
     */
    public function setMerchantId($merchant_id)
    {
        $this->merchant_id = $merchant_id;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'email_address' => $this->email_address,
            'merchant_id' => $this->merchant_id,
        ];
    }
}
