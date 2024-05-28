<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusImojePlugin\Unit\Resolver\Customer;

use BitBag\SyliusImojePlugin\Resolver\Customer\CustomerResolver;
use BitBag\SyliusImojePlugin\Resolver\Customer\CustomerResolverInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class CustomerResolverTest extends TestCase
{
    private const PHONE_NUMBER = '111222333';

    private OrderInterface $order;

    private CustomerResolverInterface $customerResolver;

    protected function setUp(): void
    {
        $this->order = $this->createMock(OrderInterface::class);
        $this->customerResolver = new CustomerResolver();
    }

    public function testResolveFirstname(): void
    {
        $customer = new Customer();
        $customer->setFirstName('John');

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        self::assertEquals('John', $this->customerResolver->resolveFirstname($this->order));
    }

    public function testResolveFirstnameFromBilling(): void
    {
        $customer = new Customer();
        $address = $this->createMock(AddressInterface::class);

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        $this->order
            ->method('getBillingAddress')
            ->willReturn($address);

        $address
            ->method('getFirstName')
            ->willReturn('Alex');

        self::assertEquals('Alex', $this->customerResolver->resolveFirstname($this->order));
    }

    public function testResolveLastname(): void
    {
        $customer = new Customer();
        $customer->setLastName('Smith');

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        self::assertEquals('Smith', $this->customerResolver->resolveLastname($this->order));
    }

    public function testResolveLastnameFromBilling(): void
    {
        $customer = new Customer();
        $address = $this->createMock(AddressInterface::class);

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        $this->order
            ->method('getBillingAddress')
            ->willReturn($address);

        $address
            ->method('getLastName')
            ->willReturn('Jones');

        self::assertEquals('Jones', $this->customerResolver->resolveLastname($this->order));
    }

    public function testResolvePhoneNumber(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber(self::PHONE_NUMBER);

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        self::assertEquals(self::PHONE_NUMBER, $this->customerResolver->resolvePhoneNumber($this->order));
    }

    public function testResolvePhoneNumberFromBilling(): void
    {
        $customer = new Customer();
        $address = $this->createMock(AddressInterface::class);

        $this->order
            ->method('getCustomer')
            ->willReturn($customer);

        $this->order
            ->method('getBillingAddress')
            ->willReturn($address);

        $address
            ->method('getPhoneNumber')
            ->willReturn(self::PHONE_NUMBER);

        self::assertEquals(self::PHONE_NUMBER, $this->customerResolver->resolvePhoneNumber($this->order));
    }
}
