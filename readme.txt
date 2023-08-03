=== CoCart - Cart Enhanced === 
Author URI: https://sebastiendumont.com
Plugin URI: https://cocart.xyz
Contributors: cocartforwc, sebd86, jppdesigns
Tags: woocommerce, rest-api, api, decoupled, headless, cart
Requires at least: 5.6
Requires PHP: 7.4
Tested up to: 6.2.2
Stable tag: 4.0.1
WC requires at least: 6.4
WC tested up to: 7.9
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enhances the data returned for the cart and the items added to it.

== Description ==

This free add-on for [CoCart](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/) enhances the data returned for the cart and the items added to it.

## ✨ Enhancements

Each item added to the cart will return the following:

 * **NEW** Returns visible product attributes for all products that is not a variation product. - **Enhances API v2 ONLY**
 * Returns the regular price and sale price if any. - **Enhances API v2 ONLY**
 * Returns the discount status of items and the discounted price if any. - **Enhances API v2 ONLY**
 * Returns virtual and downloadable status. - **Enhances API v2**
 * Returns the product slug.
 * Returns the product type.
 * Returns variation data formatted.
 * Returns the product dimensions.
 * Returns product price raw.
 * Returns the product categories assigned. - **Enhances API v2**
 * Returns the product tags assigned. - **Enhances API v2**
 * Returns the product SKU.
 * Returns the product weight and unit.
 * Returns the product stock status. - **Enhances API v2**
 * Returns the product gallery if any. - **Enhances API v2**
 * Returns the product permalink. - **Enhances API v2**

Want more? [Upgrade to CoCart Pro](https://cocart.xyz/pro/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart).

★★★★★
> An excellent plugin, which makes building a headless WooCommerce experience a breeze. Easy to use, nearly zero setup time. [Harald Schneider](https://wordpress.org/support/topic/excellent-plugin-8062/)

## 🧰 Tools and Libraries

* **[CoCart Beta Tester](https://github.com/co-cart/cocart-beta-tester)** allows you to easily update to prerelease versions of CoCart Lite for testing and development purposes.
* **[CoCart VSCode](https://github.com/co-cart/cocart-vscode)** extension for Visual Studio Code adds snippets and autocompletion of functions, classes and hooks.
* **[CoCart Product Support Boilerplate](https://github.com/co-cart/cocart-product-support-boilerplate)** provides a basic boilerplate for supporting a different product types to add to the cart with validation including adding your own parameters.
* **[CoCart Cart Callback Example](https://github.com/co-cart/cocart-cart-callback-example)** provides you an example of registering a callback that can be triggered when updating the cart.
* **[CoCart Tweaks](https://github.com/co-cart/co-cart-tweaks)** provides a starting point for developers to tweak CoCart to their needs.

#### 👍 Add-ons to further enhance CoCart

We also have other add-ons that extend CoCart to enhance your development and your customers shopping experience.

* **[CoCart - CORS](https://wordpress.org/plugins/cocart-cors/)** enables support for CORS to allow CoCart to work across multiple domains. - **FREE**
* **[CoCart - Rate Limiting](https://wordpress.org/plugins/cocart-rate-limiting)** enables the rate limiting feature. - **FREE**
* **[CoCart - JWT Authentication](https://wordpress.org/plugins/cocart-jwt-authentication)** allows you to authenticate via a simple JWT Token. - **FREE**
* and more add-ons in development.

They work with the core of CoCart already, and these add-ons of course come with support too.

★★★★★
> Amazing Plugin. I’m using it to create a react-native app with WooCommerce as back-end. This plugin is a life-saver! [Daniel Loureiro](https://wordpress.org/support/topic/amazing-plugin-1562/)

#### More testimonials

[See the wall of love](https://cocart.xyz/wall-of-love/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart).

### ⌨️ Join our growing community

A Discord community for developers, WordPress agencies and shop owners building the fastest and best headless WooCommerce stores with CoCart.

[Join our community](https://cocart.xyz/community/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)

### 🐞 Bug reports

Bug reports for CoCart - Cart Enhanced are welcomed in the [CoCart - Cart Enhanced repository on GitHub](https://github.com/co-cart/cocart-get-cart-enhanced). Please note that GitHub is not a support forum, and that issues that aren’t properly qualified as bugs will be closed.

### More information

* The [CoCart plugin](https://cocart.xyz/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart) official website.
* The CoCart [Documentation](https://docs.cocart.xyz/)
* [Subscribe to updates](http://eepurl.com/dKIYXE)
* Like, Follow and Star on [Facebook](https://www.facebook.com/cocartforwc/), [Twitter](https://twitter.com/cocartapi), [Instagram](https://www.instagram.com/co_cart/) and [GitHub](https://github.com/co-cart/co-cart)

#### 💯 Credits

This plugin is created by [Sébastien Dumont](https://sebastiendumont.com).

== Installation ==

= Minimum Requirements =

You will need CoCart v2.1 or above.

* WordPress v5.6
* WooCommerce v4.3
* PHP v7.3

= Recommended Requirements =

* WordPress v5.8 or higher.
* WooCommerce v5.2 or higher.
* PHP v7.4

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of CoCart - Cart Enhanced, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "CoCart Cart Enhanced" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Upgrading =

It is recommended that anytime you want to update "CoCart Cart Enhanced" that you get familiar with what's changed in the release.

CoCart Cart Enhanced uses Semver practices. The summary of Semver versioning is as follows:

- *MAJOR* version when you make incompatible API changes.
- *MINOR* version when you add functionality in a backwards compatible manner.
- *PATCH* version when you make backwards compatible bug fixes.

You can read more about the details of Semver at [semver.org](https://semver.org/)

== Screenshots ==

1. Empty Cart "Enhanced Off (API v1)
2. Empty Cart "Enhanced On" (API v1)
3. Cart with Item "Enhanced Off" (API v1)
4. Cart with Item "Enhanced On" (API v1)

== Changelog ==

[View the full changelog here](https://github.com/co-cart/cocart-get-cart-enhanced/blob/master/CHANGELOG.md).
