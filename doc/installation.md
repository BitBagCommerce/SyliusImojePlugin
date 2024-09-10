# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Dependencies](#dependencies)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
FRONTEND
- [Templates](#templates)
- [Webpack](#webpack)
---
ADDITIONAL
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package              | Version            |
|----------------------|--------------------|
| PHP                  | ^8.0               |
| sylius/refund-plugin | ^1.0.0             |
| sylius/sylius        | ~1.12.x or ~1.13.x |

## Dependencies
### Refund Plugin
The [Sylius Refund Plugin](https://github.com/Sylius/RefundPlugin) is our plugin dependency.
Please complete [its installation steps](https://github.com/Sylius/RefundPlugin?tab=readme-ov-file#installation) before continuing.

### IMPORTANT
> **Note:** After installation of the `RefundPlugin`, please remove Refund configuration from your project.
Imoje plugin already has this configuration and doubling it will cause and error.

### To remove `RefundPlugin` configuration:
Remove `config/packages/sylius_refund.yaml` file if exists.

## Composer:
```bash
composer require bitbag/sylius-imoje-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusImojePlugin\BitBagSyliusImojePlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusImojePlugin/Resources/config.yaml" }
```

Add `imoje` as a supported refund gateway in `config/packages/_sylius.yaml`:
```yaml
# config/packages/_sylius.yaml

   parameters:
      sylius_refund.supported_gateways:
          - offline
          - bitbag_imoje
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_imoje_plugin:
    resource: "@BitBagSyliusImojePlugin/Resources/config/routing.yaml"
```

Add logging to your environment by editing your `{dev, prod, staging}/monolog.yaml`:
```yaml
monolog:
    channels: ['imoje']
    handlers:
        imoje:
            type: stream
            path: "%kernel.logs_dir%/%kernel.environment%_imoje.log"
            level: debug
            channels: [ 'imoje' ]
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```

### IMPORTANT
> **Note**: If you receive an error related to `Twig\Extra\Intl\IntlExtension` class,
please go to `config/packages/twig.yaml` file and remove the mentioned service definition.
In some cases, multiple dependencies redefine the service, so it results the error message.
After removing it, please run the composer command second time, to finish the step.

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Templates
Copy required templates into correct directories in your project.

**AdminBundle** (`templates/bundles/SyliusAdminBundle`):
```
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusAdminBundle/Order/Show/_payment.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusAdminBundle/Order/Show/_payments.html.twig
```

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/Complete/_form.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/Complete/_navigation.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/complete.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/SelectPayment/_choiceImoje.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Checkout/SelectPayment/_payment.html.twig
vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/Order/show.html.twig
```

### Install assets by running:
```bash
bin/console assets:install
```

## Webpack
### Webpack.config.js

Please setup your `webpack.config.js` file to require the plugin's webpack configuration. To do so, please put the line below somewhere on top of your webpack.config.js file:
```js
const [bitbagImojeShop, bitbagImojeAdmin] = require('./vendor/bitbag/sylius-imoje-plugin/webpack.config.js');
```
As next step, please add the imported consts into final module exports:
```js
module.exports = [..., bitbagImojeShop, bitbagImojeAdmin];
```

### Assets
Add the asset configuration into `config/packages/assets.yaml`:
```yaml
framework:
    assets:
        packages:
            # ...
            imoje_shop:
                json_manifest_path: '%kernel.project_dir%/public/build/bitbag/imoje/shop/manifest.json'
            imoje_admin:
                json_manifest_path: '%kernel.project_dir%/public/build/bitbag/imoje/admin/manifest.json'
```

### Webpack Encore
Add the webpack configuration into `config/packages/webpack_encore.yaml`:

```yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build/default'
    builds:
        # ...
        imoje_shop: '%kernel.project_dir%/public/build/bitbag/imoje/shop'
        imoje_admin: '%kernel.project_dir%/public/build/bitbag/imoje/admin'
```

### Encore functions
Add encore functions to your templates:

SyliusAdminBundle:
```php
{# @SyliusAdminBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-imoje-admin', null, 'imoje_admin') }}

{# @SyliusAdminBundle/_styles.html.twig #}
{{ encore_entry_link_tags('bitbag-imoje-admin', null, 'imoje_admin') }}
```
SyliusShopBundle:
```php
{# @SyliusShopBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-imoje-shop', null, 'imoje_shop') }}

{# @SyliusShopBundle/_styles.html.twig #}
{{ encore_entry_link_tags('bitbag-imoje-shop', null, 'imoje_shop') }}
```

### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
