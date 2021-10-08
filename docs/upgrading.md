# Upgrading

## UPGRADING FROM V2 TO V3

There are few changes needed for upgrading from v2 to v3. but the bulk of changes can be done by leveraging the power of
find and replace on your editor.

- Change `^2.xx` (xx can vary) to `^3.0` in your `composer.json`
- Run`composer update`. Of course, your app must meet the minimum requirements as well.
- Change `PayPalClient` namespace from `PayPal\Checkout\Http\PayPalClient` to `PayPal\Http\PayPalClient`.
- Change environments namespace:
    - `PayPal\Checkout\Environment\Environment` to `PayPal\Http\Environment\Environment`.
    - `PayPal\Checkout\Environment\ProductionEnvironment` to `PayPal\Http\Environment\ProductionEnvironment`.
    - `PayPal\Checkout\Environment\SandboxEnvironment` to `PayPal\Http\Environment\SandboxEnvironment`.
- Change requests namespace:
    - `PayPal\Checkout\Http\OrderCreateRequest` to `PayPal\Checkout\Requests\OrderCreateRequest`.
    - `PayPal\Checkout\Http\OrderCaptureRequest` to `PayPal\Checkout\Requests\OrderCaptureRequest`.
    - `PayPal\Checkout\Http\OrderAuthorizeRequest` to `PayPal\Checkout\Requests\OrderAuthorizeRequest`.
    - `PayPal\Checkout\Http\OrderShowRequest` to `PayPal\Checkout\Requests\OrderShowRequest`.
    - `PayPal\Checkout\Http\PaypalRequest` to `PayPal\Http\PaypalRequest`

## UPGRADING FROM V1 TO V2

There are no special requirements for upgrading from v1 to v2, other than changing ^1.xx \(xx can vary\) to ^2.0 in your composer. json and running `composer update`. Of course, your app must meet the minimum requirements as well.

