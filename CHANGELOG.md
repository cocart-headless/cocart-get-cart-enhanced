# Changelog for CoCart Get Cart Enhanced

## v1.6.6 - 26th June, 2020

* Dev: Added `cocart_enhanced_totals` filter for totals.

## v1.6.5 - 13th June, 2020

* Fixed: Undefined variable `name` for formatted variable product data.

## v1.6.4 - 29th May, 2020

* Fixed: Array to string conversion error for notices.
* Tweaked: `cocart_notice_types` filter to return **info** notices.
* Tweaked: Cross sells to return product type.

## v1.6.3 - 29th May, 2020

* Fixed: Invalid argument supplied for `format_variation_data()`.

## v1.6.2 - 29th May, 2020

* Fixed: Undefined method for `get_session_cookie()`.
* Compatible: CoCart Lite v2.1.2

## v1.6.1 - 27th May, 2020

* Fixed: Undefined default values for cross sell `orderby`, `order` and `limit`. (Woops 🤦‍♂)

## v1.6.0 - 26th May, 2020

* NEW: Cross sell items return based on items in the cart.
* NEW: Added minimum quantity of item can be purchased.
* Tweaked: Variables that have no data now just return empty. Easier for developers to check if the variable exists.

> Cross sell has a number of filters. Check source code for them. 😄

## v1.5.1 - 23rd May, 2020

* Fixed: `wc_deprecated_argument` for **WC_Product** `get_dimensions()`.

## v1.5.0 - 22nd May, 2020

* NEW: Returns the product dimensions if any.
* Fixed: Cart fees echoing.

## v1.4.0 - 21st May, 2020

* NEW: Returns item price without currency symbol.
* NEW: Returns variation data formatted.
* NEW: Cart returns currency.
* NEW: Cart returns total weight.
* NEW: Cart now returns items removed under extras.
* NEW: Cart returns enhanced for empty carts.
* Fixed: Coupon partially echoing results.

## v1.3.0 - 9th May, 2020

* NEW: Returns cart key.
* NEW: Returns product price raw so no currency symbol.
* Tweaked: Product weight so now returns as an array with weight unit.
* Compatible: CoCart Lite v2.1

## v1.2.0 - 16th March, 2020

* NEW: Added plugin activation and deactivation check in preparation for coming feature in CoCart Pro.

## v1.1.0 - 28th February, 2020

* NEW: Returns any notices. For example a product is no longer purchasable so you are able to notify the customer.

## v1.0.0 - 20th February, 2020

* Initial version.
