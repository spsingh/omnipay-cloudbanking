<?php

/**
 * CloudBanking Abstract Request.
 */

namespace Omnipay\CloudBanking\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * CloudBanking Abstract Request.
 *
 * This is the parent class for all CloudBanking requests.
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
 * @see \Omnipay\CloudBanking\Gateway
 * @link https://api.cloudbanking.com.au/
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    private $endpoint = 'https://api.cloudbanking.com.au/version';

    /**
     * Get the gateway endpoint with version.
     *
     * @return string
     */
    protected function getEndpointWithVersion()
    {
        return $this->endpoint . $this->getParameter('apiVersion');
    }

    /**
     * Get the gateway API Key.
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
     * @return AbstractRequest provides a fluent interface.
     */
    public function setAuthkey($value)
    {
        return $this->setParameter('authkey', $value);
    }

    /**
     * Get the gateway API version.
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
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }

    /**
     * Get the customer reference.
     *
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * Set the customer reference.
     *
     * Used when calling CreateCard on an existing customer.  If this
     * parameter is not set then a new customer is created.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    /**
     * Get the card nickname.
     *
     * @return string
     */
    public function getCardNickname()
    {
        return $this->getParameter('cardNickname');
    }

    /**
     * Set the card nick name.
     *
     * Used when calling CreateCard on an existing customer.  If this
     * parameter is not set then a new customer is created.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setCardNickname($value)
    {
        return $this->setParameter('cardNickname', $value);
    }

    abstract public function getEndpoint();

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }
    /**
     * @param       $data
     * @param array $headers
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
     protected function createClientRequest($data, array $headers = null)
    {
        // Stripe only accepts TLS >= v1.2, so make sure Curl is told
        $config                          = $this->httpClient->getConfig();
        $curlOptions                     = $config->get('curl.options');
        $curlOptions[CURLOPT_SSLVERSION] = 6;
        $config->set('curl.options', $curlOptions);
        $this->httpClient->setConfig($config);

        // TODO: fix code coverage, don't throw exceptions for 4xx errors
        // $this->httpClient->getEventDispatcher()->addListener(
        //     'request.error',
        //     function ($event) {
        //         if ($event['response']->isClientError()) {
        //             $event->stopPropagation();
        //         }
        //     }
        // );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            $headers,
            $data
        );

        return $httpRequest;
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
     * {@inheritdoc}
     */
    public function sendData($data)
    {   
        $card = $this->getCard();
        $card->validate();
        $data['authkey'] = $this->getAuthkey();
        if ($this->getCustomerReference()) {
            $data['customerid'] = $this->getCustomerReference();
        }
        else {
            $this->validate('customerReference');
        }

        $httpRequest  = $this->createClientRequest($data, $this->getHeaders());
        $httpResponse = $httpRequest->send();

        $this->response = new Response($this, $httpResponse->json());

        // if ($httpResponse->hasHeader('Request-Id')) {
        //     $this->response->setRequestId((string) $httpResponse->getHeader('Request-Id'));
        // }

        return $this->response;
    }

    /**
     * Get the card data.
     *
     * Because the CloudBanking gateway uses a common format for passing
     * card data to the API, this function can be called to get the
     * data from the associated card object in the format that the
     * API requires.
     *
     * @return array
     */
    protected function getCardData()
    {
        $card = $this->getCard();
        $card->validate();

        $data = array();
        $data['object'] = 'card';
        $data['number'] = $card->getNumber();
        $data['exp_month'] = $card->getExpiryMonth();
        $data['exp_year'] = $card->getExpiryYear();
        if ($card->getCvv()) {
            $data['cvc'] = $card->getCvv();
        }
        $data['name'] = $card->getName();
        $data['address_line1'] = $card->getAddress1();
        $data['address_line2'] = $card->getAddress2();
        $data['address_city'] = $card->getCity();
        $data['address_zip'] = $card->getPostcode();
        $data['address_state'] = $card->getState();
        $data['address_country'] = $card->getCountry();
        $data['email']           = $card->getEmail();

        return $data;
    }
}
