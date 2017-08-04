<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());  
        $this->request->setApiVersion('2');
        $this->request->setCard($this->getValidCard());   
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $this->request->setToken('86b7-9818-7c67-f4ef');
        $this->request->setTransactionReference('346662');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('346662', $response->getTransactionReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getCardReference());
        $this->assertSame('softdev', $response->getCustomerReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getToken());
        $this->assertNull($response->getMessage());
    }
    
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The token parameter is required
     */
    public function testToken()
    {
        $data = $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The transactionReference parameter is required
     */
    public function testTransactionReference()
    {
        $this->request->setToken('d142-f7e2-e8c7-b426');
        $data = $this->request->getData();
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $this->request->setToken('d142-f7e2-e8c7-b426');
        $this->request->setTransactionReference('346662');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getToken());
        $this->assertSame('No valid transaction found to refund.', $response->getMessage());
    }
}
