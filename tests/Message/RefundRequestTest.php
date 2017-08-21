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
        $this->request->setCardReference('86b7-9818-7c67-f4ef');
        $this->request->setTransactionReference('346662');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('346662', $response->getTransactionReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getCardReference());
        $this->assertSame('softdev', $response->getCustomerReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getCardReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('105.00', $response->getAmountRefunded());
        $this->assertSame('refundreceipt456', $response->getRefundId());
        $this->assertSame('2017-08-01', $response->getRefundDate());
    }
    
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The token parameter is required
     */
    public function testCardReference()
    {
        $data = $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The transactionReference parameter is required
     */
    public function testTransactionReference()
    {
        $this->request->setCardReference('d142-f7e2-e8c7-b426');
        $data = $this->request->getData();
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $this->request->setCardReference('d142-f7e2-e8c7-b426');
        $this->request->setTransactionReference('346662');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('No valid transaction found to refund.', $response->getMessage());
        $this->assertNull($response->getAmountRefunded());
        $this->assertNull($response->getRefundId());
        $this->assertNull($response->getRefundDate());
    }
}
