<?php

namespace Omnipay\CloudBanking\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testCreateCardSuccess()
    {   
        $httpResponse = $this->getMockHttpResponse('CreateCardSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->json());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('athenasofttestdev', $response->getCustomerReference());
        $this->assertNull($response->getMessage());
    }

    public function testCreateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CreateCardFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getCustomerReference());
        $this->assertSame('Card Number missing.<br />Card Expiry missing.<br />The card number seems to be empty.<br />Card type not found.', $response->getMessage());
    }
}
