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
     * Get a transaction reference, for createCard or createCustomer requests.
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        if ($this->isSuccessful()) {
            return $this->data['banktransactionid'];
        }
        return null;
    }
    
     /**
     * Get Token.
     *
     * @return string|null
     */
    public function getToken()
    {
        if ($this->isSuccessful()) {
            return $this->data['cardtoken'];
        }
        return null;
    }

      /**
     * Get Amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        if ($this->isSuccessful()) {
            return $this->data['transactionamount'];
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
        if (!$this->isSuccessful()) {
            return $this->data['message'];
        }

        return null;
    }

    /**
     * Get the amount refunded from the response.
     *
     * Returns null if the request was unsuccessful.
     *
     * @return string|null
     */
    public function getAmountRefunded()
    {
        if ($this->isSuccessful()) {
            return $this->data['amountrefunded'];
        }
          return null;
    }

     /**
     * Get the refund id from the response.
     *
     * Returns null if the request was unsuccessful.
     *
     * @return string|null
     */
    public function getRefundId()
    {
        if ($this->isSuccessful()) {
            return $this->data['bankrefundid'];
        }
          return null;
    }

    /**
     * Get the refund date from the response.
     *
     * Returns null if the request was unsuccessful.
     *
     * @return string|null
     */
    public function getRefundDate()
    {
        if ($this->isSuccessful()) {
            return $this->data['refunddate'];
        }
        return null;
    }
}
