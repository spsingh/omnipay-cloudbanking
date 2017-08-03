<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setBanktransactionid('346387');
        $this->request->setApiVersion('2');
        $this->request->setCard($this->getValidCard());
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

    public function testRefnumber()
    {
        $this->assertSame($this->request, $this->request->setRefnumber('45546'));
        $this->assertSame('45546', $this->request->getRefnumber());     
    }
     
    public function testBanktransactionid()
    {
        $this->assertSame($this->request, $this->request->setBanktransactionid('346387'));
        $this->assertSame('346387', $this->request->getBanktransactionid());     
    } 
    
     public function testEndpointWithVersion()
    {
        $this->assertSame('https://api.cloudbanking.com.au/version2/transaction/find', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('346387', $response->getTransactionReference());
        $this->assertSame('softdev', $response->getCustomerReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FetchTransactionFailure.txt');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('There was no transaction found matching your details.', $response->getMessage());
    }
}
