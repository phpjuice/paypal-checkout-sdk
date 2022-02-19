<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\InvalidLandingPageException;
use PayPal\Checkout\Exceptions\InvalidShippingPreferenceException;
use PayPal\Checkout\Exceptions\InvalidUserActionException;

// landing_page
const LOGIN = 'LOGIN';
const BILLING = 'BILLING';
const NO_PREFERENCE = 'NO_PREFERENCE';

// shipping_preference
const GET_FROM_FILE = 'GET_FROM_FILE';
const NO_SHIPPING = 'NO_SHIPPING';
const SET_PROVIDED_ADDRESS = 'SET_PROVIDED_ADDRESS';

// user_action
const ACTION_CONTINUE = 'CONTINUE';
const ACTION_PAY_NOW = 'PAY_NOW';

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-order_application_context.
 */
class ApplicationContext implements Arrayable, Jsonable
{
    use CastsToJson;

    /**
     * The label that overrides the business name in the PayPal account on the PayPal site.
     *
     * @var string|null
     */
    protected ?string $brand_name = null;

    /**
     * The BCP 47-formatted locale of pages that the PayPal payment experience shows.
     * PayPal supports a five-character code.
     * Examples : da-DK, he-IL, id-ID, ja-JP, no-NO, pt-BR.
     *
     * @var string
     */
    protected string $locale = 'en-US';

    /**
     * The type of landing page to show on the PayPal site for customer checkout. The possible values are:
     *
     * - LOGIN: When the customer clicks PayPal Checkout,
     *   the customer is redirected to a page to log in to PayPal and approve the payment.
     *
     * - BILLING: When the customer clicks PayPal Checkout,
     *   the customer is redirected to a page to enter credit or debit card
     *   and other relevant billing information required to complete the purchase.
     *
     * - NO_PREFERENCE: A decimal fraction for currencies like TND
     *   that are subdivided into thousandths.
     *
     * @var string
     */
    protected string $landing_page = NO_PREFERENCE;

    /**
     * The shipping preferences. The possible values are:
     *  - GET_FROM_FILE: Use the customer-provided shipping address on the PayPal site.
     *  - NO_SHIPPING: Redact the shipping address from the PayPal site. Recommended for digital goods.
     *  - SET_PROVIDED_ADDRESS: Use the merchant-provided address.
     *    The customer cannot change this address on the PayPal site.
     *
     * @var string
     */
    protected string $shipping_preference = NO_SHIPPING;

    /**
     * The URL where the customer is redirected after the customer approves the payment.
     *
     * @var string|null
     */
    protected ?string $return_url = null;

    /**
     * The URL where the customer is redirected after the customer cancels the payment.
     *
     * @var string|null
     */
    protected ?string $cancel_url = null;

    /**
     * Configures a Continue or Pay Now checkout flow. The possible values are:
     *
     * - CONTINUE: After you redirect the customer to the PayPal payment page,
     *   a Continue button appears. Use this option when the final amount
     *   is not known when the checkout flow is initiated, and you want
     *   to redirect the customer to the merchant page without processing the payment.
     *
     * - PAY_NOW: After you redirect the customer to the PayPal payment page,
     *   a Pay Now button appears,Use this option when the final amount is known
     *   when the checkout is initiated, and you want to process the payment
     *   immediately when the customer clicks Pay Now.
     *
     * @var string
     */
    protected string $user_action = ACTION_CONTINUE;

    /**
     * @param  string|null  $brand_name
     * @param  string  $locale
     * @param  string  $landing_page
     * @param  string  $shipping_preference
     * @param  string|null  $return_url
     * @param  string|null  $cancel_url
     */
    public function __construct(
        ?string $brand_name = null,
        string $locale = 'en-US',
        string $landing_page = NO_PREFERENCE,
        string $shipping_preference = NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null
    ) {
        $this->brand_name = $brand_name;
        $this->locale = $locale;
        $this->landing_page = $landing_page;
        $this->shipping_preference = $shipping_preference;
        $this->return_url = $return_url;
        $this->cancel_url = $cancel_url;
    }

    public static function create(
        ?string $brand_name = null,
        string $locale = 'en-US',
        string $landing_page = NO_PREFERENCE,
        string $shipping_preference = NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null
    ): ApplicationContext {
        return new self(
            $brand_name,
            $locale,
            $landing_page,
            $shipping_preference,
            $return_url,
            $cancel_url
        );
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $arrayable = [
            'brand_name' => $this->getBrandName() ?? null,
            'locale' => $this->getLocale() ?? null,
            'shipping_preference' => $this->getShippingPreference() ?? null,
            'landing_page' => $this->getLandingPage() ?? null,
            'user_action' => $this->getUserAction() ?? null,
            'return_url' => $this->getReturnUrl() ?? null,
            'cancel_url' => $this->getCancelUrl() ?? null,
        ];

        return array_filter(
            $arrayable,
            function ($item) {
                return null !== $item;
            }
        );
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getShippingPreference(): string
    {
        return $this->shipping_preference;
    }

    public function setShippingPreference(string $shipping_preference): self
    {
        $validOptions = [GET_FROM_FILE, NO_SHIPPING, SET_PROVIDED_ADDRESS];
        if (!in_array($shipping_preference, $validOptions)) {
            throw new InvalidShippingPreferenceException();
        }

        $this->shipping_preference = $shipping_preference;

        return $this;
    }

    public function getLandingPage(): string
    {
        return $this->landing_page;
    }

    public function setLandingPage(string $landing_page): self
    {
        $validOptions = [LOGIN, BILLING, NO_PREFERENCE];
        if (!in_array($landing_page, $validOptions)) {
            throw new InvalidLandingPageException();
        }

        $this->landing_page = $landing_page;

        return $this;
    }

    public function getUserAction(): string
    {
        return $this->user_action;
    }

    public function setUserAction(string $user_action): self
    {
        $validOptions = [ACTION_CONTINUE, ACTION_PAY_NOW];
        if (!in_array($user_action, $validOptions)) {
            throw new InvalidUserActionException();
        }
        $this->user_action = $user_action;

        return $this;
    }

    public function getReturnUrl(): ?string
    {
        return $this->return_url;
    }

    public function setReturnUrl(string $return_url): self
    {
        $this->return_url = $return_url;

        return $this;
    }

    public function getCancelUrl(): ?string
    {
        return $this->cancel_url;
    }

    public function setCancelUrl(string $cancel_url): self
    {
        $this->cancel_url = $cancel_url;

        return $this;
    }
}
