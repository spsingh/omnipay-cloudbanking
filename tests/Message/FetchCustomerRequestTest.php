<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class FetchCustomerRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new FetchCustomerRequest($this->getHttpClient(), $this->getHttpRequest());
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
         $this->assertSame('https://api.cloudbanking.com.au/version2/customer/get', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchCustomerSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
    }

    /*
    Success for Customer Search by Name Response Sample
    */
    public function testSendNameSuccess()
    {
        $this->setMockHttpResponse('FetchCustomerNameSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchCustomerFailure.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('No Customer was found matching that ID.', $response->getMessage());
    }
}
