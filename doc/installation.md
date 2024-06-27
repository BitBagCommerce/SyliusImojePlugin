## Installation

### Sylius configuration

1. The [Sylius Refund Plugin](https://github.com/Sylius/RefundPlugin) is our plugin dependency. Please complete [its installation steps](https://github.com/Sylius/RefundPlugin?tab=readme-ov-file#installation) before continuing.

    **Note.** After installation of the Refund plugin, please remove Refund configuration from your project. Imoje plugin already has this configuration and doubling it will cause and error. 

3. Require our plugin with composer:

    ```bash
    composer require bitbag/sylius-imoje-plugin --no-scripts
    ```
    
    **Note.** If you receive an error related to `Twig\Extra\Intl\IntlExtension` class, please go to `config/packages/twig.yaml` file and remove the mentioned service definition. In some cases, multiple dependencies redefine the service, so it results the error message. After removing it, please run the composer command second time, to finish the step.

4. Add plugin dependencies to your `config/bundles.php` file:

    ```php
    return [
        ...
        BitBag\SyliusImojePlugin\BitBagSyliusImojePlugin::class => ['all' => true],
    ];
    ```

5. Import required config in your `config/packages/_sylius.yaml` file:

    ```yaml
    # config/packages/_sylius.yaml

    imports:
        ...
        - { resource: "@BitBagSyliusImojePlugin/Resources/config.yaml" }
    ```

6. Import the routing in your `config/routes.yaml` file:

    ```yaml
    # config/routes.yaml

    bitbag_sylius_imoje_plugin:
        resource: "@BitBagSyliusImojePlugin/Resources/config/routing.yaml"
    ```

7. Add Imoje as a supported refund gateway in `config/packages/_sylius.yaml`:

    ```yaml
    # config/packages/_sylius.yaml

       parameters:
          sylius_refund.supported_gateways:
              - offline
              - bitbag_imoje
    ``` 

8. Copy Sylius templates overridden by the plug-in to your templates directory (`templates/bundles/`):

    ```
    mkdir -p templates/bundles/SyliusAdminBundle/
    mkdir -p templates/bundles/SyliusShopBundle/

    cp -R vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusAdminBundle/* templates/bundles/SyliusAdminBundle/
    cp -R vendor/bitbag/sylius-imoje-plugin/tests/Application/templates/bundles/SyliusShopBundle/* templates/bundles/SyliusShopBundle/
    ```

    **Note.** If you have overridden at least one template from the directories above, please adjust your code to include our changes.

9. Add logging to your environment by editing your {dev, prod, staging}/monolog.yaml:

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

10. Clear the cache:

    ```bash
    $ bin/console cache:clear
    ```

11. Install assets:

    ```
    $ bin/console assets:install
    ```

    **Note:** If you are running it on production, add the `-e prod` flag to this command.

12. Synchronize the database:

    ```
    $ bin/console doctrine:migrations:migrate
    ```

### Webpack configuration

#### Installing Webpack package

1. Before Webpack installation, please create the `config/packages/webpack_encore.yaml` file with a content of:

    ```yaml
    webpack_encore:
        output_path: '%kernel.project_dir%/public/build/default'
        builds:
            shop: '%kernel.project_dir%/public/build/shop'
            admin: '%kernel.project_dir%/public/build/admin'
            imoje_shop: '%kernel.project_dir%/public/build/bitbag/imoje/shop'
            imoje_admin: '%kernel.project_dir%/public/build/bitbag/imoje/admin'
    ```

2. To install Webpack in your application, please run the command below:

    ```bash
    $ composer require "symfony/webpack-encore-bundle"
    ```

3. After installation, please add the line below into `config/bundles.php` file:

    ```php
    return [
        ...
        Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
    ];
    ```

#### Configuring Webpack

By a standard, the `webpack.config.js` file should be available in your repository. If not, please use [the Sylius-Standard one](https://github.com/Sylius/Sylius-Standard/blob/1.11/webpack.config.js).

1. Please setup your `webpack.config.js` file to require the plugin's webpack configuration. To do so, please put the line below somewhere on top of your `webpack.config.js` file:

    ```javascript
    const [bitbagImojeShop, bitbagImojeAdmin] = require('./vendor/bitbag/sylius-imoje-plugin/webpack.config.js');
    ```

2. As next step, please add the imported consts into final module exports:

    ```javascripts
    module.exports = [shopConfig, adminConfig, bitbagImojeShop, bitbagImojeAdmin];
    ```

3. Next thing is to add the asset configuration into `config/packages/framework.yaml`:

    ```yaml
    framework:
        assets:
            packages:
                shop:
                    json_manifest_path: '%kernel.project_dir%/public/build/shop/manifest.json'
                admin:
                    json_manifest_path: '%kernel.project_dir%/public/build/admin/manifest.json'
                imoje_shop:
                    json_manifest_path: '%kernel.project_dir%/public/build/bitbag/imoje/shop/manifest.json'
                imoje_admin:
                    json_manifest_path: '%kernel.project_dir%/public/build/bitbag/imoje/admin/manifest.json'
    ```

4. Additionally, please add the `"@symfony/webpack-encore": "^1.5.0",` dependency into your `package.json` file.

5. Now you can run the commands:

    ```bash
    $ yarn install
    $ yarn encore dev # or prod, depends on your environment
    ```
