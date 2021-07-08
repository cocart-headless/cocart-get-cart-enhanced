=== CoCart - Cart Enhanced === 
Author URI: https://sebastiendumont.com
Plugin URI: https://cocart.xyz
Contributors: sebd86, cocartforwc, jppdesigns
Tags: woocommerce, cart, rest-api, decoupled, headless
Donate link: https://www.buymeacoffee.com/sebastien
Requires at least: 5.4
Requires PHP: 7.3
Tested up to: 5.7
Stable tag: 3.0.2
WC requires at least: 4.3
WC tested up to: 5.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enhances the data returned for the cart and the items added to it.

== Description ==

This free add-on for [CoCart](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/) enhances the data returned for the cart and the items added to it.

= Features =

#### 11th May 2021

The majority of these features will already be available in CoCart API v2. Only a few of these features will be used to enhance the cart response.

If you are still using API v1 then all features listed below will still be available.

----

 * Checks for items in cart for validity and stock before returning the updated cart.
 * Checks for coupons applied to the cart for validity before returning the updated cart.
 * Cart returns currency.
 * Cart returns total weight.
 * Cart now returns items removed under extras.
 * Cart returns cross sell items based on items in the cart.
 * Cart returns enhanced for empty carts.
 * Cart returns the cart key.
 * Cart returns the cart hash key.
 * Places the cart contents under a new array called `items`.
 * Cart returns the item count of all items in the cart.
 * Cart returns shipping methods available if any and which one is chosen.
 * Cart returns the shipping status of the cart.
 * Cart returns the payment status of the cart.
 * Cart returns coupons applied to the cart if enabled.
 * Cart returns additional fees applied to the cart.
 * Cart returns the cart totals.

Each item added to the cart will also return the following:

 * Returns virtual and downloadable status. - **Enhances API v2**
 * Returns the product slug.
 * Returns the product type.
 * Returns variation data formatted.
 * Returns the product dimensions.
 * Returns minimum and maximum quantity of item can be purchased.
 * Returns product price raw.
 * Returns the product categories assigned. - **Enhances API v2**
 * Returns the product tags assigned. - **Enhances API v2**
 * Returns the product SKU.
 * Returns the product weight and unit.
 * Returns the product stock status. - **Enhances API v2**
 * Returns the product gallery if any. - **Enhances API v2**
 * Returns the product permalink. - **Enhances API v2**

Want more? [Upgrade to CoCart Pro](https://cocart.xyz/pro/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart).

#### Other Add-ons to further enhance your cart.

We also have other **[add-ons](https://cocart.xyz/add-ons/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** that extend CoCart to enhance your development and your customers shopping experience.

* **[CoCart - Carts in Session](https://github.com/co-cart/cocart-carts-in-session)** allows you to view all the carts in session via the WordPress admin. - **FREE**
* **[CoCart - CORS](https://wordpress.org/plugins/cocart-cors/)** simply filters the session cookie to allow CoCart to work across multiple domains. - **FREE**
* **[CoCart Products](https://cocart.xyz/add-ons/products/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** provides access to non-sensitive product information, categories, tags, attributes and even reviews from your store without the need to authenticate.
* **[Advanced Custom Fields](https://cocart.xyz/add-ons/advanced-custom-fields/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** extends CoCart Products add-on by returning all your advanced custom fields for products. - **REQUIRES COCART PRODUCTS**
* **[Yoast SEO](https://cocart.xyz/add-ons/yoast-seo/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** extends CoCart Products add-on by returning Yoast SEO data for products, product categories and product tags. - **REQUIRES COCART PRODUCTS**
* and more add-ons in development.

### Join our growing community

A Slack community for developers, WordPress agencies and shop owners building the fastest and best headless WooCommerce stores with CoCart.

[Join our community](https://cocart.xyz/community/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)

### Built with developers in mind

Extensible, adaptable, and open source — CoCart - Get Cart Enhanced is created with developers in mind. If you’re interested to jump in the project, there are opportunities for developers at all levels to get involved. [Contribute to CoCart - Get Cart Enhanced on the GitHub repository](https://github.com/co-cart/cocart-get-cart-enhanced) and join the party.

### Bug reports

Bug reports for CoCart - Get Cart Enhanced are welcomed in the [CoCart - Get Cart Enhanced repository on GitHub](https://github.com/co-cart/cocart-get-cart-enhanced). Please note that GitHub is not a support forum, and that issues that aren’t properly qualified as bugs will be closed.

### More information

* The [CoCart plugin](https://cocart.xyz/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart) official website.
* The CoCart [Documentation](https://docs.cocart.xyz/)
* [Subscribe to updates](http://eepurl.com/dKIYXE)
* Like, Follow and Star on [Facebook](https://www.facebook.com/cocartforwc/), [Twitter](https://twitter.com/cart_co), [Instagram](https://www.instagram.com/co_cart/) and [GitHub](https://github.com/co-cart/co-cart)

= Credits =

This plugin is created by [Sébastien Dumont](https://sebastiendumont.com).

== Installation ==

= Minimum Requirements =

You will need CoCart v2.1 or above.

* WordPress v5.4
* WooCommerce v4.3
* PHP v7.3

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of CoCart - Get Cart Enhanced, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "CoCart" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Empty Cart "Enhanced Off (API v1)
2. Empty Cart "Enhanced On" (API v1)
3. Cart with Item "Enhanced Off" (API v1)
4. Cart with Item "Enhanced On" (API v1)

== Changelog ==

[View the full changelog here](https://github.com/co-cart/cocart-get-cart-enhanced/blob/master/CHANGELOG.md).
