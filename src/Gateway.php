<?php

/**
 * CloudBanking Gateway.
 */
namespace Omnipay\CloudBanking;

use Omnipay\Common\AbstractGateway;
use Omnipay\CloudBanking\Message\CreateTokenRequest;

/**
 * CloudBanking Gateway.
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
 *       'apiKey' => 'MyApiKey',
 *       'apiVersion' => '2',
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
 *       'amount'                   => '10.00',
 *       'currency'                 => 'USD',
 *       'card'                     => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *
 *       $balance_transaction_id = $response->getBalanceTransactionReference();
 *       echo "Balance Transaction reference = " . $balance_transaction_id . "\n";
 *   }
 * </code>
 *
 * Test modes:
 *
 * CloudBanking accounts have test-mode API keys as well as live-mode
 * API keys. These keys can be active at the same time. Data
 * created with test-mode credentials will never hit the credit
 * card networks and will never cost anyone money.
 *
 * Unlike some gateways, there is no test mode endpoint separate
 * to the live mode endpoint, the CloudBanking API endpoint is the same
 * for test and for live.
 *
 * Setting the testMode flag on this gateway has no effect.  To
 * use test mode just use your test mode API key.
 *
 * You can use any of the cards listed at https://CloudBanking.com/docs/testing
 * for testing.
 *
 * Authentication:
 *
 * Authentication is by means of a single secret API key set as
 * the apiKey parameter when creating the gateway object.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\CloudBanking\Message\AbstractRequest
 *
 * @link https://CloudBanking.com/docs/api
 *
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 */
class Gateway extends AbstractGateway
{
    const BILLING_PLAN_FREQUENCY_DAY    = 'day';
    const BILLING_PLAN_FREQUENCY_WEEK   = 'week';
    const BILLING_PLAN_FREQUENCY_MONTH  = 'month';
    const BILLING_PLAN_FREQUENCY_YEAR   = 'year';

    public function getName()
    {
        return 'CloudBanking';
    }

    /**
     * Get the gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'authkey' => '',
            'apiVersion' => 2
        );
    }

    /**
     * Get the gateway API Key.
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     *
     * @return string
     */
    public function getAuthkey()
    {
        return $this->getParameter('authkey');
    }

    /**
     * Set the gateway API Key.
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     *
     * CloudBanking accounts have test-mode API keys as well as live-mode
     * API keys. These keys can be active at the same time. Data
     * created with test-mode credentials will never hit the credit
     * card networks and will never cost anyone money.
     *
     * Unlike some gateways, there is no test mode endpoint separate
     * to the live mode endpoint, the CloudBanking API endpoint is the same
     * for test and for live.
     *
     * Setting the testMode flag on this gateway has no effect.  To
     * use test mode just use your test mode API key.
     *
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setAuthkey($value)
    {
        return $this->setParameter('authkey', $value);
    }

    /**
     * Get the gateway API version.
     *
     * The version of the CloudBanking api to use.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    /**
     * Set the gateway API Version.
     *
     *
     * @param integer $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->getParameter('headers');
    }

    /**
     * Set http headers.
     *
     * Used to send the headers array in the http request.
     *
     * @param array $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setHeaders($value)
    {
        return $this->setParameter('headers', $value);
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Set request id
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    //
    // Cards
    // @link https://api.cloudbanking.com.au/methods#methods-card
    //

    /**
     * Create Card.
     *
     * This call can be used to create a new customer or add a card
     * to an existing customer.  If a customerReference is passed in then
     * a card is added to an existing customer.  If there is no
     * customerReference passed in then a new customer is created.  The
     * response in that case will then contain both a customer token
     * and a card token, and is essentially the same as CreateCustomerRequest
     *
     * @param array $parameters
     *
     * @return \Omnipay\CloudBanking\Message\CreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CloudBanking\Message\CreateCardRequest', $parameters);
    }
    
}
