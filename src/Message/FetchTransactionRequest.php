<?php

/**
 * CloudBanking Fetch Transaction Request.
 */
namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Fetch Transaction Request.
 *
 * Example -- note this example assumes that the purchase has been successful
 * and that the transaction ID returned from the purchase is held in $banktransactionid.
 * See PurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // Fetch the transaction so that details can be found for refund, etc.
 *   $transaction = $gateway->fetchTransaction();
 *   $transaction->setTransactionReference($banktransactionid);
 *   $response = $transaction->send();
 *   $data = $response->getData();
 *   echo "Gateway fetchTransaction response data == " . print_r($data, true) . "\n";
 * </code>
 *
 * @see PurchaseRequest
 * @see Omnipay\CloudBanking\Gateway
 * @link https://api.cloudbanking.com.au/version2/transaction/find
 */
class FetchTransactionRequest extends AbstractRequest
{
   public function getData()
    {
        $data = array();

        if ($this->getCard()) {
            $card = $this->getCard();
            $card->validate();
            $data['cardtoken'] = $this->getCustomerReference();
            $data['refnumber'] = $this->getRefnumber();;
            $data['banktransactionid'] = $this->getBanktransactionid();
        }
        else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    /**
     * Get the Invoice number to the Transaction.
     *
     * @return string
     */
    public function getRefnumber()
    {
        return $this->getParameter('refnumber');
    }

    /**
     * Set the Invoice number to the Transaction.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setRefnumber($value)
    {
        return $this->setParameter('refnumber', $value);
    }

    /**
     * Get the Transaction reference number to the Transaction.
     *
     * @return string
     */
    public function getBanktransactionid()
    {
        return $this->getParameter('banktransactionid');
    }

    /**
     * Set the Transaction reference number to the Transaction.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setBanktransactionid($value)
    {
        return $this->setParameter('banktransactionid', $value);
    }
    /*
    Get End Point 
    */  
    public function getEndpoint()
    {
        // Create a new transaction. 
        return $this->getEndpointWithVersion().'/transaction/find';
    }   
    
    public function getHttpMethod()
    {
        return 'GET';
    }
}
