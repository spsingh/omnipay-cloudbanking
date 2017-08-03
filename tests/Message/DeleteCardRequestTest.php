<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class DeleteCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DeleteCardRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testDataWithCustomerToken()
    {
        $this->request->setCustomerReference('xyz');
        $data = $this->request->getData()['cardtoken'];

        $this->assertSame('xyz', $data);
    }

    public function testDataWithCustomerId()
    {
        $this->request->setCustomerReference('abcde');
        $data = $this->request->getData()['customerid'];

        $this->assertSame('abcde', $data);
    }

    public function testEndpointWithVersion()
    {
         $this->assertSame('https://api.cloudbanking.com.au/version2/card/remove', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('DeleteCardSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('DeleteCardFailure.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('No card ID found.', $response->getMessage());
    }
}
