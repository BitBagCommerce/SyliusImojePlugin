<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\ImojeOneClickSignature;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;

final class ImojeOneClickSignatureResolver implements ImojeOneClickSignatureResolverInterface
{
    public function resolve(array $orderData, ImojeClientConfigurationInterface $config): string
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
