<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setApiVersion('2');
        $this->request->setCard($this->getValidCard());
    }

    public function testAddfees()
    {
       $this->request->setAddfees(500);
    }

    public function testRefnumber()
    {
        $this->assertSame($this->request, $this->request->setRefnumber('45546'));
        $this->assertSame('45546', $this->request->getRefnumber());     
    }

    public function testSmssend()
    {
        $this->assertSame($this->request, $this->request->setSmssend(1));
        $this->assertSame(1, $this->request->getSmssend());     
    }

    public function testSmsnumberto()
    {
        $this->assertSame($this->request, $this->request->setSmsnumberto('99999'));
        $this->assertSame('99999', $this->request->getSmsnumberto());     
    }

    public function testSmsmessage()
    {
        $this->assertSame($this->request, $this->request->setSmsmessage('hi'));
        $this->assertSame('hi', $this->request->getSmsmessage());     
    }

    public function testEmailsend()
    {
        $this->assertSame($this->request, $this->request->setEmailsend(1));
        $this->assertSame(1, $this->request->getEmailsend());     
    }

    public function testEmailaddressto()
    {
        $this->assertSame($this->request, $this->request->setEmailaddressto('abc@xyz.com'));
        $this->assertSame('abc@xyz.com', $this->request->getEmailaddressto());     
    }

    public function testEmailaddressfrom()
    {
        $this->assertSame($this->request, $this->request->setEmailaddressfrom('abc@xyz.com'));
        $this->assertSame('abc@xyz.com', $this->request->getEmailaddressfrom());     
    }

    public function testEmailsubject()
    {
        $this->assertSame($this->request, $this->request->setEmailsubject('greetings'));
        $this->assertSame('greetings', $this->request->getEmailsubject());     
    }

    public function testEmailmessage()
    {
        $this->assertSame($this->request, $this->request->setEmailmessage('hi there'));
        $this->assertSame('hi there', $this->request->getEmailmessage());     
    }

    public function testCustomfields()
    {
        $this->request->setToken('86b7-9818-7c67-f4ef');
        $this->request->setAmount('500.00');
        $this->request->setCustomerReference('softdev');
        $this->assertSame($this->request, $this->request->setCustomfields(['fieldName' => 'fieldValue']));
        $this->request->getData();
        $this->assertArrayHasKey('fieldName', $this->request->getCustomfields());
    }

    public function testEndpointWithVersion()
    {
        $this->assertSame('https://api.cloudbanking.com.au/version2/transaction/process', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $this->request->setToken('86b7-9818-7c67-f4ef');
        $this->request->setAmount('500.00');
        $this->request->setCustomerReference('softdev');
        $this->request->setCardReference('d142-f7e2-e8c7-b426');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('346387', $response->getTransactionReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getCardReference());
        $this->assertSame('softdev', $response->getCustomerReference());
        $this->assertSame('d142-f7e2-e8c7-b426', $response->getToken());
        $this->assertSame('500.00', $response->getAmount());
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
     * @expectedExceptionMessage The amount parameter is required
     */
    public function testAmount()
    {
        $this->request->setToken('d142-f7e2-e8c7-b426');
        $data = $this->request->getData();
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $this->request->setToken('d142-f7e2-e8c7-b426');
        $this->request->setAmount('500.00');
        $this->request->setCustomerReference('softdev');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getAmount());
        $this->assertNull($response->getToken());
        $this->assertSame('Card token not found in vault.', $response->getMessage());
    }
}
