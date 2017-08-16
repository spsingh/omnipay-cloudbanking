<?php
/**
 * CloudBanking Customer Balance Request
 */

namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Customer Balance Request
 *
 * Example -- note this example assumes that the purchase has been successful
 * and that the transaction balance ID returned from the purchase is held in $balanceTransactionId.
 * See PurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // Fetch the balance to get information about the payment.
 *   $balance = $gateway->fetchBalanceTransaction();
 *   $balance->setBalanceTransactionReference($balance_transaction_id);
 *   $response = $balance->send();
 *   $data = $response->getData();
 *   echo "Gateway fetchBalance response data == " . print_r($data, true) . "\n";
 * </code>
 *
 * @see BalanceRequest
 * @see Omnipay\CloudBanking\Gateway
 * @link https://api.cloudbanking.com.au/methods#method-customer-balance
 */
class CustomerBalanceRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
        } else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        // To Get a card
        return $this->getEndpointWithVersion().'/customer/balance';
    }
}
