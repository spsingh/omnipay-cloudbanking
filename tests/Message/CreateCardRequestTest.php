<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class CreateCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setApiVersion('2');
        $this->request->setHeaders(null);
        $this->request->setCard($this->getValidCard());
        $data = $this->request->getData();
    }

    public function testDataWithCardReference()
    {
        $this->request->setCustomerReference('xyz');
        $data = $this->request->getData()['cardtoken'];

        $this->assertSame('xyz', $data);
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['cardnumber']);
    }

    public function testEndpointWithVersion()
    {
         $this->assertSame('https://api.cloudbanking.com.au/version2/card/add', $this->request->getEndpoint());
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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreateCardSuccess.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        // $this->assertNull($response->getTransactionReference());
        $this->assertSame('athenasofttestdev', $response->getCustomerReference());
        $this->assertSame('d729-9818-e8c7-dd79', $response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The customerReference parameter is required
     */
    public function testSendWithoutCustomerReference()
    {
        $this->setMockHttpResponse('CreateCardSuccess.txt');
        $response = $this->request->send();
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreateCardFailure.txt');
        $this->request->setCustomerReference('athenasofttestdev');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        // $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Card Number missing.<br />Card Expiry missing.<br />The card number seems to be empty.<br />Card type not found.', $response->getMessage());
    }
}
