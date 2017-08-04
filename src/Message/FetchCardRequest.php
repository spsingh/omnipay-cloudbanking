<?php

/**
 * CloudBanking Fetch Card Request.
 */
namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Fetch Card Request.
 *
 *
 * @link https://api.cloudbanking.com.au/methods#method-customer-get
 */
class FetchCardRequest extends  AbstractRequest
{
    public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
        }
        else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        // To Get a card
        return $this->getEndpointWithVersion().'/customer/cards';
    }
}
