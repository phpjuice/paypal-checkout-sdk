<?php

namespace PayPal\Checkout\Orders;

use ArrayAccess;
use PayPal\Checkout\Concerns\HasJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\InvalidOrderIntentException;
use PayPal\Checkout\Exceptions\OrderPurchaseUnitException;

const CAPTURE = 'CAPTURE';
const AUTHORIZE = 'AUTHORIZE';

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-order.
 */
class Order implements Arrayable, Jsonable, ArrayAccess
{
    use HasJson;

    /**
     * The ID of the order.
     *
     * @var string read only
     */
    protected $id;

    /**
     * The intent to either capture payment immediately or authorize a payment for an order after order creation.
     *      CAPTURE : The merchant intends to capture payment immediately after the customer makes a payment.
     *      AUTHORIZE : The merchant intends to authorize a payment and place funds on hold after the customer makes a payment.
     *
     * @var string
     */
    protected $intent;

    /**
     * An array of purchase units. At present only 1 purchase_unit is supported.
     * Each purchase unit establishes a contract between a payer and the payee.
     * https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit_request.
     *
     * @var \PayPal\Checkout\Orders\PurchaseUnit[]
     */
    protected $purchase_units = [];

    /**
     * The intent to either capture payment immediately or authorize a payment for an order after order creation.
     *      CREATED : The order was created with the specified context.
     *      SAVED : The order was saved and persisted.
     *      The order status continues to be in progress until a capture is made with final_capture = true for all purchase units within the order.
     *      APPROVED :  The customer approved the payment through the PayPal wallet or another form of guest or unbranded payment.
     *      For example, a card, bank account, or so on.
     *      VOIDED : All purchase units in the order are voided.
     *      COMPLETED : The payment was authorized or the authorized payment was captured for the order.
     *
     * @var string read only
     */
    protected $status;

    /**
     * The order application context.
     * https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.
     *
     * @var \PayPal\Checkout\Orders\ApplicationContext
     */
    protected $application_context = null;

    /**
     * The order payee.
     * https://developer.paypal.com/docs/api/orders/v2/#definition-payee.
     *
     * @var \PayPal\Checkout\Orders\Payee
     */
    protected $payee = null;

    /**
     * creates a new order instance.
     */
    public function __construct(string $intent = CAPTURE)
    {
        $this->setIntent($intent);
        $this->application_context = new ApplicationContext();
    }

    /**
     *  push a new item into purchase_units array.
     */
    public function addPurchaseUnit(PurchaseUnit $purchase_unit): self
    {
        if (count($this->purchase_units) >= 1) {
            throw new OrderPurchaseUnitException('At present only 1 purchase_unit is supported.');
        }
        $this->purchase_units[] = $purchase_unit;

        return $this;
    }

    /**
     * return's order purchase units.
     */
    public function getPurchaseUnits(): array
    {
        return $this->purchase_units;
    }

    /**
     * set's order application context.
     */
    public function setApplicationContext(ApplicationContext $application_context): self
    {
        $this->application_context = $application_context;

        return $this;
    }

    /**
     * return's order application context.
     */
    public function getApplicationContext(): ?ApplicationContext
    {
        return $this->application_context;
    }

    /**
     * set's order intent.
     */
    public function setIntent(string $intent): self
    {
        if (!in_array($intent, [CAPTURE, AUTHORIZE])) {
            throw new InvalidOrderIntentException();
        }

        $this->intent = $intent;

        return $this;
    }

    /**
     * return's order intent.
     */
    public function getIntent(): string
    {
        return $this->intent;
    }

    /**
     * return's order id.
     */
    public function getId(): string
    {
        return $this->status;
    }

    /**
     * return's order status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if (empty($this->purchase_units)) {
            throw new OrderPurchaseUnitException('Paypal orders must have 1 purchase_unit at least.');
        }

        return [
            'intent' => $this->intent,
            'purchase_units' => array_map(
                function (PurchaseUnit $purchase_unit) {
                    return $purchase_unit->toArray();
                },
                $this->purchase_units
            ),
            'application_context' => $this->application_context->toArray(),
        ];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->purchase_units[] = $value;
        } else {
            $this->purchase_units[$offset] = $value;
        }
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->purchase_units[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->purchase_units[$offset]) ? $this->purchase_units[$offset] : null;
    }

    /**
     * Determine if a key exists on the purchase_units.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->purchase_units[$offset]);
    }
}
