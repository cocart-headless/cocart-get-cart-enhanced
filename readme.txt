=== CoCart Get Cart Enhanced === 
Author URI: https://sebastiendumont.com
Plugin URI: https://cocart.xyz
Contributors: sebd86, cocartforwc
Tags: woocommerce, cart, rest, rest-api, JSON
Donate link: https://opencollective.com/cocart
Requires at least: 5.0
Requires PHP: 7.0
Tested up to: 5.4.1
Stable tag: 1.6.0
WC requires at least: 3.6.0
WC tested up to: 4.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enhances the data returned for the cart and the items added to it.

== Description ==

This free add-on for [CoCart](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/) enhances the data returned for the cart and the items added to it.

= Features =

 * **NEW**: Cart returns currency.
 * **NEW**: Cart returns total weight.
 * **NEW**: Cart now returns items removed under extras.
 * **NEW**: Cart returns cross sell items based on items in the cart.
 * **NEW**: Cart returns enhanced for empty carts.
 * Returns the cart key.
 * Returns the cart hash key.
 * Places the cart contents under a new array called `items`.
 * Returns the item count of all items in the cart.
 * Returns the shipping status of the cart.
 * Returns the payment status of the cart.
 * Returns coupons applied to the cart if enabled.
 * Returns additional fees applied to the cart.
 * Returns the cart totals.

Each item added to the cart will also return the following: 

 * **NEW**: Returns variation data formatted.
 * **NEW**: Returns the product dimensions.
 * **NEW**: Returns minimum and maximum quantity of item can be purchased.
 * Return product price raw.
 * Returns the product categories assigned.
 * Returns the product tags assigned.
 * Returns the product SKU.
 * Returns the product weight and unit.
 * Returns the product stock status.
 * Returns the product gallery if any.

Want more? [Upgrade to CoCart Pro](https://cocart.xyz/pro/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart).

#### Other Add-ons to further enhance your cart.

We also have other **[add-ons](https://cocart.xyz/add-ons/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** that extend CoCart to enhance your development and your customers shopping experience.

* **[CoCart Products](https://cocart.xyz/add-ons/products/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** provides a public version of WooCommerce REST API for accessing products, categories, tags, attributes and 
even reviews without the need to authenticate.
* **[CoCart Yoast SEO](https://cocart.xyz/add-ons/yoast-seo/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)** extends CoCart Products add-on by returning Yoast SEO data for products, product categories and product tags. - **REQUIRES COCART PRODUCTS**
* and more add-ons in development.

### Join our growing community

A Slack community for developers, WordPress agencies and shop owners building the fastest and best headless WooCommerce stores with CoCart.

[Join our community](https://cocart.xyz/community/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)

### Built with developers in mind

Extensible, adaptable, and open source — CoCart is created with developers in mind. If you’re interested to jump in the project, there are opportunities for developers at all levels to get involved. [Contribute to CoCart - Get Cart Enhanced on the GitHub repository](https://github.com/co-cart/cocart-get-cart-enhanced) and join the party.

### Bug reports

Bug reports for CoCart - Get Cart Enhanced are welcomed in the [CoCart - Get Cart Enhanced repository on GitHub](https://github.com/co-cart/cocart-get-cart-enhanced). Please note that GitHub is not a support forum, and that issues that aren’t properly qualified as bugs will be closed.

### More information

* [Visit the CoCart website](https://cocart.xyz/?utm_source=wordpressorg&utm_medium=wp.org&utm_campaign=readme).
* [Documentation](https://docs.cocart.xyz/)
* [Subscribe to updates](http://eepurl.com/dKIYXE)
* [Like and Follow on Facebook](https://www.facebook.com/cocartforwc/)
* [Follow on Twitter](https://twitter.com/cart_co)
* [Follow on Instagram](https://www.instagram.com/co_cart/)
* [GitHub](https://github.com/co-cart)

= Credits =

This plugin is created by [Sébastien Dumont](https://sebastiendumont.com).

== Installation ==

= Minimum Requirements =

Visit the [WooCommerce server requirements documentation](https://docs.woocommerce.com/document/server-requirements/) for a detailed list of server requirements.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of CoCart - Get Cart Enhanced, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "CoCart" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

[View the full changelog here](https://github.com/co-cart/cocart-get-cart-enhanced/blob/master/CHANGELOG.md).
