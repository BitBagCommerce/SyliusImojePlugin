<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\IngPayOneClickSignature;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;

final class IngPayOneClickSignatureResolver implements IngPayOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, IngPayClientConfigurationInterface $config): string
    {
        $data = $this->prepareData($orderData);

        $signature = \hash('sha256', $data . $config->getShopKey());

        return $signature . ';sha256';
    }

    private function prepareData(
        array $data,
        string $prefix = '',
    ): string {
        \ksort($data);
        $hashData = [];
        foreach ($data as $key => $value) {
            if ('' !== $prefix) {
                $key = $prefix . '[' . $key . ']';
            }
            if (is_array($value)) {
                $hashData[] = $this->prepareData($value, $key);
            } else {
                $hashData[] = $key . '=' . $value;
            }
        }

        return implode('&', $hashData);
    }
}
