<?php

/**
 * Stripe Purchase Request.
 */
namespace Omnipay\CloudBanking\Message;

/**
 * CloudBanking Purchase Request.
 *
 * To charge a credit card, you create a new charge object. If your API key
 * is in test mode, the supplied card won't actually be charged, though
 * everything else will occur as if in live mode. (CloudBanking assumes that the
 * charge would have completed successfully).
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the CloudBanking Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('CloudBanking');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'authkey' => 'MyAuthKey',
 *   ));
 *
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $card = new CreditCard(array(
 *               'firstName'    => 'Example',
 *               'lastName'     => 'Customer',
 *               'number'       => '4242424242424242',
 *               'expiryMonth'  => '01',
 *               'expiryYear'   => '2020',
 *               'cvv'          => '123',
 *               'email'                 => 'customer@example.com',
 *               'billingAddress1'       => '1 Scrubby Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Scrubby Creek',
 *               'billingPostcode'       => '4999',
 *               'billingState'          => 'QLD',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'transactionamount'        => '10.00',
 *       'addfees'                  => '2.00',
 *       'refnumber'                => 'This is a transaction invoice number.',
 *       'smssend'                  => 'True/False',
 *       'smsnumberto'              => 'This is a recipent number.',
 *       'smsmessage'               => 'This is a message.',
 *       'emailsend'                => 'True/False',
 *       'emailaddressto'           => 'This is a recipent email address.',
 *       'emailaddressfrom'         => 'This is a sender email address.',
 *       'emailsubject'             => 'This is email subject.',
 *       'emailmessage'             => 'This is email message.',
 *       'customfieldname'          => 'This is custome field name.',
 *       'card'                     => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $banktransactionid = $response->getTransactionReference();
 *       echo "Transaction reference = " . $banktransactionid . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\CloudBanking\Gateway
 * @link https://api.cloudbanking.com.au/version2/transaction/process
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array();

        if ($this->getCardReference()) {
            $data['cardtoken'] = $this->getCardReference();
        } else {
            $this->validate('token');
        }

        if ($this->getAmount()) {
            $data['transactionamount'] = $this->getAmount();
        } else {
            $this->validate('amount');
        }

        $data['addfees'] = $this->getAddfees();
        $data['refnumber'] = $this->getTransactionId();
        $data['smssend'] = $this->getSmssend();
        $data['smsnumberto'] = $this->getSmsnumberto();
        $data['smsmessage'] = $this->getSmsmessage();
        $data['emailsend'] = $this->getEmailsend();
        $data['emailaddressto'] = $this->getEmailaddressto();
        $data['emailaddressfrom'] = $this->getEmailaddressfrom();
        $data['emailsubject'] = $this->getEmailsubject();
        $data['emailmessage'] = $this->getEmailmessage();
        if ($this->getCustomfields()) {
            $data = array_merge($data, $this->getCustomfields());
        }
        return $data;
    }
     /**
     * @return int
     */
    public function getAddfees()
    {
        return $this->getParameter('addfees');
    }

    /**
     * Set the AddFees.
     *
     * @return int
     */
    public function setAddfees($value)
    {
        return $this->getParameter('addfees', $value);
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
     * Get whether the SMS Plugin is enabled for your account.
     *
     * @return string
     */
    public function getSmssend()
    {
        return $this->getParameter('smssend');
    }

    /**
     * Set SMS Plugin is enabled for your account.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setSmssend($value)
    {
        return $this->setParameter('smssend', $value);
    }

    /**
     * Get the phone number you want to send the text message to.
     *
     * @return string
     */
    public function getSmsnumberto()
    {
        return $this->getParameter('smsnumberto');
    }

    /**
     * Set the phone number you want to send the text message to.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setSmsnumberto($value)
    {
        return $this->setParameter('smsnumberto', $value);
    }

    /**
     * Get the body of the text message.
     *
     * @return string
     */
    public function getSmsmessage()
    {
        return $this->getParameter('smsmessage');
    }

    /**
     * Set the body of the text message.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setSmsmessage($value)
    {
        return $this->setParameter('smsmessage', $value);
    }
     
    /**
     * Get whether the Email Plugin is enabled for your account.
     *
     * @return string
     */
    public function getEmailsend()
    {
        return $this->getParameter('emailsend');
    }

    /**
     * Set Email Plugin is enabled for your account.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setEmailsend($value)
    {
        return $this->setParameter('emailsend', $value);
    }

     /**
     * Get email address to send the message to.
     *
     * @return string
     */
    public function getEmailaddressto()
    {
        return $this->getParameter('emailaddressto');
    }

    /**
     * Set email address to send the message to.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setEmailaddressto($value)
    {
        return $this->setParameter('emailaddressto', $value);
    }
     
    /**
     * Get email address to send the message from.
     *
     * @return string
     */
    public function getEmailaddressfrom()
    {
        return $this->getParameter('emailaddressfrom');
    }

    /**
     * Set email address to send the message from.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setEmailaddressfrom($value)
    {
        return $this->setParameter('emailaddressfrom', $value);
    }

    /**
     * Get subject for the email.
     *
     * @return string
     */
    public function getEmailsubject()
    {
        return $this->getParameter('emailsubject');
    }

    /**
     * Set subject for the email.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setEmailsubject($value)
    {
        return $this->setParameter('emailsubject', $value);
    }
     
    /**
     * Get  body of the email message
     *
     * @return string
     */
    public function getEmailmessage()
    {
        return $this->getParameter('emailmessage');
    }

    /**
     * Set  body of the email message
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setEmailmessage($value)
    {
        return $this->setParameter('emailmessage', $value);
    }
    /**
     * Get custom fields
     *
     * @return array
     */
    public function getCustomfields()
    {
        return $this->getParameter('customfields');
    }

    /**
     * Set custom fields
     *
     * @param array $value
     * @return AbstractRequest provides a fluent interface.
     */
    public function setCustomfields($value)
    {
        return $this->setParameter('customfields', $value);
    }

    /** Get End Point 
    */
    public function getEndpoint()
    {
        // Create a new transaction.
        return $this->getEndpointWithVersion().'/transaction/process';
    }
}
