<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class CustomerBalanceRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CustomerBalanceRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setApiVersion('2');
        $this->request->setHeaders(null);
        $this->request->setCard($this->getValidCard());
        $data = $this->request->getData();
    }
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCard()
    {
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testEndpointWithVersion()
    {
         $this->assertSame('https://api.cloudbanking.com.au/version2/customer/balance', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CustomerBalanceSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendNoInvoiceSuccess()
    {
        $this->setMockHttpResponse('CustomerBalanceWithInvoiceSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CustomerBalanceFailure.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('No customer found with that ID.', $response->getMessage());
    }
}
