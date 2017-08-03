<?php

/**
 * CloudBanking Create Credit Card Request.
 */
namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Create Credit Card Request.
 *
 * In the CloudBanking system, creating a credit card requires passing
 * a customer ID.  The card is then added to the customer's account.
 * If the customer has no default card then the newly added
 * card becomes the customer's default card.
 *
 * This call can be used to create a new customer or add a card
 * to an existing customer.
 *
 * ### Example
 *
 * This example assumes that you have already created a
 * customer, and that the customer reference is stored in $customer_id.
 * See CreateCustomerRequest for the first part of this transaction.
 *
 * <code>
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   // The CreditCard object is also used for creating customers.
 *   $new_card = new CreditCard(array(
 *               'firstName'    => 'Example',
 *               'lastName'     => 'Customer',
 *               'number'       => '5555555555554444',
 *               'expiryMonth'  => '01',
 *               'expiryYear'   => '2020',
 *               'cvv'          => '456',
 *               'email'                 => 'customer@example.com',
 *               'billingAddress1'       => '1 Lower Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Upper Swan',
 *               'billingPostcode'       => '6999',
 *               'billingState'          => 'WA',
 *   ));
 *
 *   // Do a create card transaction on the gateway
 *   $response = $gateway->createCard(array(
 *       'card'              => $new_card,
 *       'customerReference' => $customer_id,
 *       'cardNickname'      => $card_nickname,
 *   ))->send();
 *   if ($response->isSuccessful()) {
 *       echo "Gateway createCard was successful.\n";
 *       // Find the card ID
 *       $card_id = $response->getCardReference();
 *       echo "Card ID = " . $card_id . "\n";
 *   }
 * </code>
 *
 * @see CreateCustomerRequest
 * @link https://api.cloudbanking.com.au/methods#method-card-add
 */
class CreateCardRequest extends AbstractRequest
{   
    public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
            $data['cardtoken'] = $this->getCustomerReference();
            $data['cardname'] = $card->getName();
            $data['cardnumber'] = $card->getNumber();
            $data['cardexpiry'] = $card->getExpiryDate('mm/yy');
            $data['cardcvv'] = $card->getCvv();
            $data['cardlabel'] = $this->getCardNickname();
            $data = array_merge($data, $this->getCardData());
        }
        else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }
    /*
    Get End Point 
    */
    public function getEndpoint()
    {
        // Create a new card 
        return $this->getEndpointWithVersion().'/card/add';
    }
}
