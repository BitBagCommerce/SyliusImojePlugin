## Installation

1. Require with composer

```bash
composer require bitbag/sylius-ing-plugin
```
2. Add plugin dependencies to your `config/bundles.php` file:

```php
return [
    ...
    BitBag\SyliusIngPlugin\BitBagSyliusIngPlugin::class => ['all' => true],
];
```

3. Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusIngPlugin/Resources/config/config.yaml" }
```

4. Import the routing in your `config/routes.yaml` file:

```yaml
# config/routes.yaml

bitbag_sylius_ing_plugin:
    resource: "@BitBagSyliusIngPlugin/Resources/config/shop_routing.yaml"
```

5. Add Ing payment method as supported refund gateway in `config/packages/_sylius.yaml`

```yaml
# config/packages/_sylius.yaml

   parameters:
      sylius_refund.supported_gateways:
         - offline
         - bitbag_ing
``` 

6. Copy Sylius templates overridden by plug-in to your templates directory (`templates/bundles/`):

```
mkdir -p templates/bundles/SyliusAdminBundle/
mkdir -p templates/bundles/SyliusShopBundle/

cp -R vendor/bitbag/sylius-ing-plugin/tests/Application/templates/bundles/SyliusAdminBundle/* templates/bundles/SyliusAdminBundle/
cp -R vendor/bitbag/sylius-ing-plugin/tests/Application/templates/bundles/SyliusShopBundle/* templates/bundles/SyliusShopBundle/
```

7. Complete [refund plug-in](https://github.com/Sylius/RefundPlugin) install steps (e.g. templates and so on)

8. Install assets

```
bin/console assets:install
```

**Note:** If you are running it on production, add the `-e prod` flag to this command.

9. Synchronize the database

```
bin/console doctrine:schema:update
```

10. Add logging to your environment in {dev, prod, staging}/monolog.yaml

```yaml
monolog:
  channels: ['ing']
    ing:
      type: stream
      path: "%kernel.logs_dir%/%kernel.environment%_ing.log"
      level: debug
      channels: [ 'ing' ]
```
