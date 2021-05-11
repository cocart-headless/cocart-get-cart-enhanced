# Changelog for CoCart Get Cart Enhanced

## v3.0.0 - 11th April, 2021

* Compatible: CoCart v3.0

## v2.0.4 - 09th Febuary, 2021

* Fixed: Undefined `$code` by removing it as it returns nothing of important. One less error.
* Tweaked: Moved `$available_methods` container up a few lines so each package returns fully.
* Tested: Compatible with WooCommerce v4.9

## v2.0.3 - 07th January, 2021

* Corrected: Coupons now return as an object if more than one coupon is applied.

### CoCart v3.0 API v2 Preview

* Enhanced: Shipping details now return along with each package and the available rates.
* Dev: New `cocart_shipping_package_details_array` filter for filtering package details listed per package.
* Dev: New `cocart_shipping_package_name` filter for renaming the package name.

## v2.0.2 - 23rd December, 2020

* Corrected: Package version with the version of the plugin.
* Enhanced: Cross sells is cleaner if you are previewing the cart response for CoCart v3.0 API v2.

## v2.0.1 - 13th December, 2020

* Dev: Re-done the main file so the plugin can be packaged with composer.

## v2.0.0 - 9th December, 2020

* **NEW**: Preview cart response for CoCart v3.0 API v2 (Requires `cocart_preview_api_v2` set `true` to enable).
* **NEW**: Added a check for items in cart for validity and stock before returning the updated cart.
* **NEW**: Added a check for coupons applied to the cart for validity before returning the updated cart.
* Tested: Compatible with PHP 8
* Tested: Compatible with CoCart v2.8
* Tested: Compatible with WooCommerce v4.8

> Previewing the new cart response is still a working progress and is by no means complete. Use only for experimenting and to provide feedback.

## v1.10.0 - 23rd November, 2020

* **NEW**: Product returns virtual and downloadable status.
* **NEW**: Returns shipping methods available if any and which one is chosen.
* Enhanced: Cart totals are calculated again to be sure they are correct before returning totals.

> This is a community release by [@joelpierre](https://github.com/joelpierre)

## v1.9.3 - 30th October, 2020

* Fixed: Get product slug if variable product detected.
* Tested: Compatible with CoCart v2.7
* Tested: Compatible with WooCommerce v4.6.1

## v1.9.2 - 22nd September, 2020

* Fixed: Always empty product categories and tags.
* Tested: Compatible with WooCommerce v4.5.2

> This is a community release by [@tomsotte](https://github.com/tomsotte)

## v1.9.1 - 26th August, 2020

* Fixed: Coupons returned without losing special characters applied.

## v1.9.0 - 06th August, 2020

* **NEW**: Cross sell items now returns the product slug.
* Fixed: Product slug returns from the parent product even if the product type is a variation.

## v1.8.0 - 04th August, 2020

* **NEW**: Each item now returns the product slug.
* Added: Requirements to plugin header.
* Added: Before and After screenshots.
* Corrected: Requirements to match with CoCart Lite's current requirements.

## v1.7.0 - 6th July, 2020

* **NEW**: Each item now returns the product type.

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

* **NEW**: Cross sell items return based on items in the cart.
* **NEW**: Added minimum quantity of item can be purchased.
* Tweaked: Variables that have no data now just return empty. Easier for developers to check if the variable exists.

> Cross sell has a number of filters. Check source code for them. 😄

## v1.5.1 - 23rd May, 2020

* Fixed: `wc_deprecated_argument` for **WC_Product** `get_dimensions()`.

## v1.5.0 - 22nd May, 2020

* **NEW**: Returns the product dimensions if any.
* Fixed: Cart fees echoing.

## v1.4.0 - 21st May, 2020

* **NEW**: Returns item price without currency symbol.
* **NEW**: Returns variation data formatted.
* **NEW**: Cart returns currency.
* **NEW**: Cart returns total weight.
* **NEW**: Cart now returns items removed under extras.
* **NEW**: Cart returns enhanced for empty carts.
* Fixed: Coupon partially echoing results.

## v1.3.0 - 9th May, 2020

* **NEW**: Returns cart key.
* **NEW**: Returns product price raw so no currency symbol.
* Tweaked: Product weight so now returns as an array with weight unit.
* Compatible: CoCart Lite v2.1

## v1.2.0 - 16th March, 2020

* **NEW**: Added plugin activation and deactivation check in preparation for coming feature in CoCart Pro.

## v1.1.0 - 28th February, 2020

* **NEW**: Returns any notices. For example a product is no longer purchasable so you are able to notify the customer.

## v1.0.0 - 20th February, 2020

* Initial version.
