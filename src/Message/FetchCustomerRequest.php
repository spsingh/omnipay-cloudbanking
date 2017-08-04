<?php

/**
 * CloudBanking Fetch Customer Request.
 */
namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Fetch Customer Request.
 *
 *
 * @link https://api.cloudbanking.com.au/methods#method-customer-get
 */
class FetchCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
            $data['cardname'] = $card->getName();
        }
        else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        // To Get a Customer 
        return $this->getEndpointWithVersion().'/customer/get';
    }
}
