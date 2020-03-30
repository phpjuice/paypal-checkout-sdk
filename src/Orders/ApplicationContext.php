<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\HasJson;
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
    use HasJson;

    /**
     * The label that overrides the business name in the PayPal account on the PayPal site.
     *
     * @var string
     */
    protected $brand_name;

    /**
     * The BCP 47-formatted locale of pages that the PayPal payment experience shows.
     * PayPal supports a five-character code.
     * Examples : da-DK, he-IL, id-ID, ja-JP, no-NO, pt-BR.
     *
     * @var string
     */
    protected $locale = 'en-US';

    /**
     * The type of landing page to show on the PayPal site for customer checkout. The possible values are:
     *     - LOGIN: When the customer clicks PayPal Checkout, the customer is redirected to a page to log in to PayPal and approve the payment.
     *     - BILLING: When the customer clicks PayPal Checkout, the customer is redirected to a page to enter credit or debit card
     *       and other relevant billing information required to complete the purchase.
     *     - NO_PREFERENCE: A decimal fraction for currencies like TND that are subdivided into thousandths.
     *
     * @var string
     */
    protected $landing_page = NO_PREFERENCE;

    /**
     * The shipping preferences. The possible values are:
     *     - GET_FROM_FILE: Use the customer-provided shipping address on the PayPal site.
     *     - NO_SHIPPING: Redact the shipping address from the PayPal site. Recommended for digital goods.
     *     - SET_PROVIDED_ADDRESS: Use the merchant-provided address. The customer cannot change this address on the PayPal site.
     *
     * @var string
     */
    protected $shipping_preference = NO_SHIPPING;

    /**
     * The URL where the customer is redirected after the customer approves the payment.
     *
     * @var string
     */
    protected $return_url = null;

    /**
     * The URL where the customer is redirected after the customer cancels the payment.
     *
     * @var string
     */
    protected $cancel_url = null;

    /**
     * Configures a Continue or Pay Now checkout flow. The possible values are:
     *     - CONTINUE: After you redirect the customer to the PayPal payment page, a Continue button appears.
     *       Use this option when the final amount is not known when the checkout flow is initiated
     *       and you want to redirect the customer to the merchant page without processing the payment.
     *     - PAY_NOW: After you redirect the customer to the PayPal payment page, a Pay Now button appears.
     *       Use this option when the final amount is known when the checkout is initiated
     *       and you want to process the payment immediately when the customer clicks Pay Now.
     *
     * @var string
     */
    protected $user_action = ACTION_CONTINUE;

    /**
     * Create a new collection.
     *
     * @param string $brand_name
     * @param string $locale
     * @param string $landing_page
     * @param string $shipping_preference
     * @param string $return_url
     * @param string $cancel_url
     *
     * @return self
     */
    public function __construct(
        $brand_name = null,
        $locale = 'en-US',
        $landing_page = NO_PREFERENCE,
        $shipping_preference = NO_SHIPPING,
        $return_url = null,
        $cancel_url = null
    ) {
        $this->setBrandName($brand_name);
        $this->setLocale($locale);
        $this->setLandingPage($landing_page);
        $this->setShippingPreference($shipping_preference);
        $this->setReturnUrl($return_url);
        $this->setCancelUrl($cancel_url);
    }

    /**
     * gets brand_name.
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * sets the brand_name.
     */
    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    /**
     * gets brand_name.
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * sets the locale.
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * gets landing_page.
     */
    public function getLandingPage()
    {
        return $this->landing_page;
    }

    /**
     * sets the landing_page.
     */
    public function setLandingPage($landing_page)
    {
        $validOptions = [LOGIN, BILLING, NO_PREFERENCE];
        if (!in_array($landing_page, $validOptions)) {
            throw new InvalidLandingPageException();
        }

        $this->landing_page = $landing_page;

        return $this;
    }

    /**
     * gets shipping_preference.
     */
    public function getShippingPreference()
    {
        return $this->shipping_preference;
    }

    /**
     * sets the shipping_preference.
     */
    public function setShippingPreference($shipping_preference)
    {
        $validOptions = [GET_FROM_FILE, NO_SHIPPING, SET_PROVIDED_ADDRESS];
        if (!in_array($shipping_preference, $validOptions)) {
            throw new InvalidShippingPreferenceException();
        }

        $this->shipping_preference = $shipping_preference;

        return $this;
    }

    /**
     * gets return_url.
     */
    public function getReturnUrl()
    {
        return $this->return_url;
    }

    /**
     * sets the return_url.
     */
    public function setReturnUrl($return_url)
    {
        $this->return_url = $return_url;

        return $this;
    }

    /**
     * gets cancel_url.
     */
    public function getCancelUrl()
    {
        return $this->cancel_url;
    }

    /**
     * sets the cancel_url.
     */
    public function setCancelUrl($cancel_url)
    {
        $this->cancel_url = $cancel_url;

        return $this;
    }

    /**
     * gets user_action.
     */
    public function getUserAction()
    {
        return $this->user_action;
    }

    /**
     * sets the user_action.
     */
    public function setUserAction($user_action)
    {
        $validOptions = [ACTION_CONTINUE, ACTION_PAY_NOW];
        if (!in_array($user_action, $validOptions)) {
            throw new InvalidUserActionException();
        }
        $this->user_action = $user_action;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arrayable = [
            'brand_name' => $this->brand_name ?? null,
            'locale' => $this->locale ?? null,
            'shipping_preference' => $this->shipping_preference ?? null,
            'landing_page' => $this->landing_page ?? null,
            'user_action' => $this->user_action ?? null,
            'return_url' => $this->return_url ?? null,
            'cancel_url' => $this->cancel_url ?? null,
        ];

        return array_filter(
            $arrayable,
            function ($item) {
                return null !== $item;
            }
        );
    }
}
