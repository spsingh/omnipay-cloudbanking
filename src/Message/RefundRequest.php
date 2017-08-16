<?php

/**
 * CloudBanking Refund Request.
 */

namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Refund Request.

 * Example -- note this example assumes that the purchase has been successful
 * and that the transaction ID returned from the purchase is held in $banktransactionid.
 * See PurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // Do a refund transaction on the gateway
 *   $transaction = $gateway->refund(array(
 *       'banktransactionid'     => $banktransactionid,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Refund transaction was successful!\n";
 *       $refund_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $refund_id . "\n";
 *   }
 * </code>
 *
 * @see PurchaseRequest
 * @see \Omnipay\CloudBanking\Gateway
 * @link https://api.cloudbanking.com.au/version2/transaction/refund
 */
class RefundRequest extends AbstractRequest
{

    public function getData()
    {
        $data = array();

        if ($this->getToken()) {
            $data['cardtoken'] = $this->getToken();
        } else {
            $this->validate('token');
        }

        if ($this->getTransactionReference()) {
            $data['banktransactionid'] = $this->getTransactionReference();
        } else {
            $this->validate('transactionReference');
        }
        return $data;
    }

    /*
    Get End Point 
    */
    public function getEndpoint()
    {
        // Create a new transaction.
        return $this->getEndpointWithVersion().'/transaction/refund';
    }
}
