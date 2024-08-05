=== CoCart - Cart API Enhanced === 
Contributors: cocartforwc, sebd86, jppdesigns
Tags: woocommerce, rest-api, decoupled, headless, cart
Requires at least: 5.6
Requires PHP: 7.4
Tested up to: 6.6
Stable tag: 4.0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enhances CoCart's cart REST API response.

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

Want more? [See what we have in store](https://cocartapi.com/pricing/?utm_medium=website&utm_source=wpplugindirectory&utm_campaign=readme&utm_content=readmelink).

★★★★★
> An excellent plugin, which makes building a headless WooCommerce experience a breeze. Easy to use, nearly zero setup time. [Harald Schneider](https://wordpress.org/support/topic/excellent-plugin-8062/)

## 🧰 Developer Tools

* **[CoCart Beta Tester](https://github.com/cocart-headless/cocart-beta-tester)** allows you to easily update to pre-release versions of CoCart for testing and development purposes.
* **[CoCart VSCode](https://github.com/cocart-headless/cocart-vscode)** extension for Visual Studio Code adds snippets and autocompletion of functions, classes and hooks.
* **[CoCart Product Support Boilerplate](https://github.com/cocart-headless/cocart-product-support-boilerplate)** provides a basic boilerplate for supporting a different product types to add to the cart with validation including adding your own parameters.
* **[CoCart Cart Callback Example](https://github.com/cocart-headless/cocart-cart-callback-example)** provides you an example of registering a callback that can be triggered when updating the cart.

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

[See the wall of love](https://cocartapi.com/wall-of-love/?utm_medium=website&utm_source=wpplugindirectory&utm_campaign=readme&utm_content=readmelink).

### ⌨️ Join our growing community

A Discord community for developers, WordPress agencies and shop owners building the fastest and best headless WooCommerce stores with CoCart.

[Join our community](https://cocartapi.com/community/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=cocart)

### 🐞 Bug reports

Bug reports for CoCart - Cart API Enhanced are welcomed in the [CoCart - Cart API Enhanced repository on GitHub](https://github.com/cocart-headless/cocart-get-cart-enhanced). Please note that GitHub is not a support forum, and that issues that aren’t properly qualified as bugs will be closed.

### More information

* The official [CoCart API plugin](https://cocartapi.com/?utm_medium=website&utm_source=wpplugindirectory&utm_campaign=readme&utm_content=readmelink) website.
* [CoCart for Developers](https://cocart.dev/?utm_medium=website&utm_source=wpplugindirectory&utm_campaign=readme&utm_content=readmelink), an official hub for resources you need to be productive with CoCart and keep track of everything that is happening with the API.
* [CoCart API Reference](https://docs.cocart.xyz/)
* [Subscribe to updates](http://eepurl.com/dKIYXE)
* Like, Follow and Star on [Facebook](https://www.facebook.com/cocartforwc/), [Twitter](https://twitter.com/cocartapi), [Instagram](https://www.instagram.com/cocartheadless/) and [GitHub](https://github.com/co-cart/co-cart)

#### 💯 Credits

This plugin is developed and maintained by [Sébastien Dumont](https://twitter.com/sebd86).
Founder of [CoCart Headless, LLC](https://twitter.com/cocartheadless).

== Installation ==

= Minimum Requirements =

You will need CoCart v2.1 or above.

* WordPress v5.6
* WooCommerce v7.0
* PHP v7.4

= Recommended Requirements =

* WordPress v6.0 or higher.
* WooCommerce v9.0 or higher.
* PHP v8.0 or higher.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of CoCart - Cart API Enhanced, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "CoCart Cart API Enhanced" and click Search Plugins. Once you’ve found the plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Upgrading =

It is recommended that anytime you want to update "CoCart Cart API Enhanced" that you get familiar with what's changed in the release.

CoCart Cart API Enhanced uses Semver practices. The summary of Semver versioning is as follows:

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

[View the full changelog here](https://github.com/cocart-headless/cocart-get-cart-enhanced/blob/master/CHANGELOG.md).
