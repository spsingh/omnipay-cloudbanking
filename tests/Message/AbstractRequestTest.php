<?php

namespace Omnipay\CloudBanking\Message;

use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\CloudBanking\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }
    
    public function testCardReference()
    {
        $this->assertSame($this->request, $this->request->setCardReference('abc123'));
        $this->assertSame('abc123', $this->request->getCardReference());
    }

    public function testCardNickname()
    {
        $this->assertSame($this->request, $this->request->setCardNickname('testCreditCard'));
        $this->assertSame('testCreditCard', $this->request->getCardNickname());
    }

    public function testCardData()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getCardData();

        $this->assertSame($card['number'], $data['number']);
        $this->assertSame($card['cvv'], $data['cvc']);
    }

    /*To Make call to Api Must be with POST method
    */
    public function testHttpMethod()
    {
        $this->assertSame('POST', $this->request->getHttpMethod());
    }
    
}
