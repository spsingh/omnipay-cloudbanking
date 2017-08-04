<?php

namespace Omnipay\CloudBanking;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testCreateCard()
    {
        $request = $this->gateway->createCard(array('description' => 'foo'));

        $this->assertInstanceOf('Omnipay\CloudBanking\Message\CreateCardRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

    public function testDeleteCard()
    {
        $request = $this->gateway->createCard(array('description' => 'foo'));

        $this->assertInstanceOf('Omnipay\CloudBanking\Message\CreateCardRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

    public function testFetchCustomer()
    {
        $request = $this->gateway->fetchCustomer(array('description' => 'foo'));
        $this->assertInstanceOf('Omnipay\CloudBanking\Message\FetchCustomerRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

     public function testFetchCard()
    {
        $request = $this->gateway->fetchCard(array('description' => 'foo'));
        $this->assertInstanceOf('Omnipay\CloudBanking\Message\FetchCardRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

     public function testCustomerBalance()
    {
        $request = $this->gateway->customerBalance(array('description' => 'foo'));
        $this->assertInstanceOf('Omnipay\CloudBanking\Message\CustomerBalanceRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

    public function testHttpHeaders()
    {
        $this->gateway->setHeaders(array('Content-Type' => 'application/json; charset=UTF-8'));
        $request = $this->gateway->createCard();

        $this->assertInstanceOf('Omnipay\CloudBanking\Message\CreateCardRequest', $request);
        $this->assertArrayHasKey('Content-Type', $this->gateway->getHeaders());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));
        $this->assertInstanceOf('Omnipay\CloudBanking\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array());

        $this->assertInstanceOf('Omnipay\CloudBanking\Message\FetchTransactionRequest', $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(array());

        $this->assertInstanceOf('Omnipay\CloudBanking\Message\RefundRequest', $request);    
    }
}
