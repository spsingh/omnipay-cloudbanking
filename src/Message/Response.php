<?php

/**
 * CloudBanking Response.
 */
namespace Omnipay\CloudBanking\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * CloudBanking Response.
 *
 * This is the response class for all CloudBanking requests.
 *
 * @see \Omnipay\CloudBanking\Gateway
 */
class Response extends AbstractResponse
{
    /**
     * Request id
     *
     * @var string URL
     */
    protected $requestId = null;
    
    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['success']) && 1 == $this->data['success'];
    }
    
    /**
     * Get a customer reference, for createCustomer requests.
     *
     * @return string|null
     */
    public function getCustomerReference()
    {
        if ($this->isSuccessful()) {
            return $this->data['customerid'];
        }
        return null;
    }

    /**
     * Get a card reference, for createCard or createCustomer requests.
     *
     * @return string|null
     */
    public function getCardReference()
    {
        if ($this->isSuccessful()) {
            return $this->data['cardtoken'];
        }
        return null;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (!$this->isSuccessful()){
            return $this->data['message'];
        }

        return null;
    }
}
