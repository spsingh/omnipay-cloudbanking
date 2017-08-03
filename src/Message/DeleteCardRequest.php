<?php

/**
 * CloudBanking Delete Credit Card Request.
 */
namespace Omnipay\CloudBanking\Message;


/**
 * CloudBanking Delete Credit Card Request.
 *
 * This is normally used to delete a credit card from an existing
 * customer.
 *
 * You can delete cards from a customer or recipient. If you delete a
 * card that is currently the default card on a customer or recipient,
 * the most recently added card will be used as the new default. If you
 * delete the last remaining card on a customer or recipient, the
 * default_card attribute on the card's owner will become null.
 *
 * Note that for cards belonging to customers, you may want to prevent
 * customers on paid subscriptions from deleting all cards on file so
 * that there is at least one default card for the next invoice payment
 * attempt.
 *
 * In deference to the previous incarnation of this gateway, where
 * all CreateCard requests added a new customer and the customer ID
 * was used as the card ID, if a cardReference is passed in but no
 * customerReference then we assume that the cardReference is in fact
 * a customerReference and delete the customer.  This might be
 * dangerous but it's the best way to ensure backwards compatibility.
 *
 * @link https://api.cloudbanking.com.au/methods#method-card-remove
 */
class DeleteCardRequest extends AbstractRequest
{
     public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
            $data['cardtoken'] = $this->getCustomerReference();
            $data['customerid'] = $this->getCustomerReference();
        }
        else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        // To Delete a Card  
        return $this->getEndpointWithVersion().'/card/remove';
    }
}
