<h1 align="center">CoCart - Cart Enhanced <a href="https://github.com/co-cart/cocart-get-cart-enhanced/releases/latest/"><img src="https://img.shields.io/static/v1?goVersion=&message=v3.0.2&label=&color=9a6fc4&style=flat-square"></a></h1>

<p align="center"><img src="https://raw.githubusercontent.com/co-cart/co-cart/master/.github/Logo-1024x534.png.webp" alt="CoCart" /></p>

<p align="center">This free add-on enhances the data returned for the cart and the items added to it.</p>

## Features

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
 * Cart returns the shipping status of the cart.
 * Cart returns the payment status of the cart.
 * Cart returns coupons applied to the cart if enabled.
 * Cart returns shipping methods available if any and which one is chosen.
 * Cart returns additional fees applied to the cart.
 * Cart returns the cart totals.

Each item added to the cart will also return the following:

 * **NEW**: Returns the discount status of items and the discounted price if any. - **Enhances API v2 ONLY**
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

> To preview the new cart response for CoCart v3.0 API v2. Apply filter `cocart_preview_api_v2` set to `true` to enable.

## Requirement

You will need to be using [CoCart **v2.1**](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/) and up before installing this add-on.

## Download

[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/cocart-get-cart-enhanced.svg)](https://wordpress.org/plugins/cocart-get-cart-enhanced/)

[Click here to download](https://downloads.wordpress.org/plugin/cocart-get-cart-enhanced.zip) the latest release package of CoCart.

## Bug Reporting

If you think you have found a bug in the plugin, please [open a new issue](https://github.com/co-cart/cocart-get-cart-enhanced/issues/new/choose) and I will do my best to help you out.

## Support CoCart

If you or your company use CoCart or appreciate the work I’m doing in open source, please consider donating via one of the links available on right hand side under "**Sponsor this project**" or [purchasing CoCart Pro](https://cocart.xyz/pro/?utm_medium=gh&utm_source=github&utm_campaign=readme&utm_content=cocart) where you not just get the full cart experience but also support me directly so I can continue maintaining CoCart and keep evolving the project.

Please also consider starring ✨ and sharing 👍 the project repo! This helps the project getting known and grow with the community. 🙏

Thank you for your support! 🙌

---

## CoCart Channels

We have different channels at your disposal where you can find information about the CoCart project, discuss it and get involved:

[![Twitter: cart_co](https://img.shields.io/twitter/follow/cart_co?style=social)](https://twitter.com/cart_co) [![CoCart Github Stars](https://img.shields.io/github/stars/co-cart/cocart-get-cart-enhanced?style=social)](https://github.com/co-cart/cocart-get-cart-enhanced)

<ul>
  <li>📖 <strong>Docs</strong>: this is the place to learn how to build amazing sites with CoCart. <a href="https://docs.cocart.xyz/#getting-started">Get started!</a></li>
  <li>👪 <strong>Community</strong>: use our Slack chat room to share any doubts, feedback and meet great people. This is your place too to share <a href="https://cocart.xyz/community/">how are you planning to use CoCart!</a></li>
  <li>🐞 <strong>GitHub</strong>: we use GitHub for bugs and pull requests, doubts are solved with the community.</li>
  <li>🐦 <strong>Social media</strong>: a more informal place to interact with CoCart users, reach out to us on <a href="https://twitter.com/cart_co">Twitter.</a></li>
  <li>💌 <strong>Newsletter</strong>: do you want to receive the latest plugin updates and news? Subscribe <a href="https://twitter.com/cart_co">here.</a></li>
</ul>

## Get involved

Do you like the idea of creating a headless ecommerce with WooCommerce? Got questions or feedback? We'd love to hear from you. Come join our [community](https://cocart.xyz/community/)! ❤️

CoCart also welcomes contributions. There are many ways to support the project (and get free swag)! If you don't know where to start, this guide might help >> [How to contribute?](https://github.com/co-cart/co-cart/blob/master/.github/CONTRIBUTING.md).

---

## License

[![License](https://img.shields.io/badge/license-GPL--3.0%2B-red.svg)](https://github.com/co-cart/cocart-get-cart-enhanced/blob/master/LICENSE.md)

CoCart is released under [GNU General Public License v3.0](http://www.gnu.org/licenses/gpl-3.0.html).

## Credits

CoCart is developed and maintained by [Sébastien Dumont](https://github.com/seb86).

---

[sebastiendumont.com](https://sebastiendumont.com) &nbsp;&middot;&nbsp;
GitHub [@seb86](https://github.com/seb86) &nbsp;&middot;&nbsp;
Twitter [@sebd86](https://twitter.com/sebd86)

<p align="center">
    <img src="https://raw.githubusercontent.com/seb86/my-open-source-readme-template/master/a-sebastien-dumont-production.png" width="353">
</p>