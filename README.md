<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">SyliusIngPlugin</h1>

<p align="center">Payment gateway for ING</p>

## Documentation

For a comprehensive guide on Sylius Plugins development please go to Sylius documentation,
there you will find the <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development Guide</a>, which is full of examples.

## Quickstart Installation

1. Run `composer create-project sylius/plugin-skeleton ProjectName`.

2. From the plugin skeleton root directory, run the following commands:

    ```bash
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn build)
    $ (cd tests/Application && APP_ENV=test bin/console assets:install public)
    
    $ (cd tests/Application && APP_ENV=test bin/console doctrine:database:create)
    $ (cd tests/Application && APP_ENV=test bin/console doctrine:schema:create)
    ```

To be able to set up a plugin's database, remember to configure your database credentials in `tests/Application/.env` and `tests/Application/.env.test`.

## ING Payments Plugin for Sylius
At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us working together, feel free to reach us out. You will find out more about our professional services, technologies and contact details at [https://bitbag.io/](https://bitbag.io/).

## Overview

  ![Screenshot showing payment methods show in the shop](doc/select_payment_ing.png)

  ![Screenshot showing payment method config in admin](doc/admin_page_ing.png)

  ![Screenshot showing payment method config in admin](doc/widget_ing.png)

The integration currently supports the following payment methods:

1. Cards
2. Blik
3. ING payment method
4. Pay by link

# Installation
----

### Requirements

We work on stable, supported, and up-to-date versions of packages. We recommend you do the same.

| Package | Version |
| --- | --- |
| PHP |  ^7.4 |
| sylius/refund-plugin |  ^1.0.0 |
| sylius/sylius |  ^1.9ㅤ or ㅤ^1.10|

## Usage

This plugin allows you to use the payment solution delivered by ING.

### Instalation
```
$ composer install
$ cd tests/Application
$ yarn install
$ yarn encore dev
$ bin/console assets:install -e test
$ bin/console doctrine:database:create -e test
$ bin/console doctrine:schema:create -e test
$ symfony server:start
$ open http://localhost:8080 // or the port showed in your terminal while runing command with symfony server:start
```

For the full installation guide please go to [installation](doc/installation.md).
### Configuration:
You need to put the path to wkhtmltopdf in your .env file.
```
WKHTMLTOPDF_PATH=/usr/local/bin/wkhtmltopdf
```

### Configuration in the admin panel:
To create an ING-based payment method, go to Payment methods in the Sylius admin panel.


  ![Screenshot showing payment method config in admin](doc/payment_methods_config.png)

After that, you need to add an ING payment:


  ![Screenshot showing payment method config in admin](doc/create_ing_method.png)

And now, you can configure your payment method in the admin panel:
* first you need to add a gateway code, for example "ing_code" and set its position.


  ![Screenshot showing payment method config in admin](doc/details.png)
* To configure the iMoje gateway, log in to ING the admin panel.


  ![Screenshot showing payment method config in admin](doc/main_imoje.png)
* From "Settings" -> "Data for integration" you can acquire all the needed keys:

* merchantId,
* serviceId,
* shopKey

  ![Screenshot showing payment method config in admin](doc/data_integration.png)
* You also need an authorization token, so you need to go to:
  "Settings" -> "API Keys". And click on your "API key". This will be your authorization token.

  ![Screenshot showing payment method config in admin](doc/api_keys.png)

  ![Screenshot showing payment method config in admin](doc/token.png)
* Also, you need to configure the path to your webhooks, just type in your shop URL followed by /payment/ing/webhook.

  ![Screenshot showing payment method config in admin](doc/webhook.png)
* Now you need to add a URL in the admin panel for the production API URL:

```
https://sandbox.api.imoje.pl/v1/merchant
```
* And the sandbox API URL:
```
https://api.imoje.pl/v1/merchant
```

* And now, you can choose which payment-by-link you want to use. After that, you need to add a name for your ![img.png](img.png) gateway and click "Create".

  ![Screenshot showing payment method config in admin](doc/finish.png)

# About us
---

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo and Pimcore for PIM, eZ Platform for CMS and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.  
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.  
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**.Based on a detailed analysis of the goals and needs of a given organization we create dedicated systems and applications that let businesses grow.  
Our team is fluent in **Polish, English, German and French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 50+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 120+ projects **delivered** on top of Sylius,
- 25+ **countries** of BitBag’s customers,
- 4+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mollie) or send us an e-mail to hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2020/10/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mollie)

## Community
For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `sylius: sylius` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mollie)

## Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact
---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mollie) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2020/10/footer.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mollie)
