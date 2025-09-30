<?php

namespace Antistatique\Realforce;

use Antistatique\Realforce\Resource\ResourceInterface;

/**
 * Super-simple, minimum abstraction Realforce API v1.x wrapper, in PHP.
 *
 * Realforce Github API: https://github.com/realforce/documentation.
 * Realforce Developers: https://www.realforce.com/developers.
 *
 * Every request should contain a valid API token.
 *
 * @method \Antistatique\Realforce\Resource\PublicProperties publicProperties()
 * @method \Antistatique\Realforce\Resource\PublicLabels     publicLabels()
 */
class RealforceClient
{
    /**
     * Default timeout limit for request in seconds.
     *
     * @var int
     */
    public const TIMEOUT = 10;

    /**
     * Realforce FQD class to be automatically discovered.
     *
     * @var string
     */
    private const FQN_CLASS = '\\Antistatique\\Realforce\\Resource\\';

    /**
     * SSL Verification.
     *
     * Read before disabling:
     * http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
     *
     * @var bool
     */
    public bool $verifySsl = true;

    /**
     * The API auth token.
     *
     * @var string
     */
    private string $apiToken = '';

    /**
     * The last request error description.
     *
     * @var string
     */
    protected string $lastError = '';

    /**
     * The last request anatomy.
     *
     * @var array
     */
    protected array $lastRequest = [];

    /**
     * The last response.
     *
     * @var array
     */
    protected array $lastResponse = [];

    /**
     * The last response code.
     */
    protected ?int $lastResponseHttpStatus = null;

    /**
     * Does the last request succeed or failed.
     *
     * @var bool
     */
    protected bool $requestSuccessful = false;

    /**
     * Create a new instance.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (!$this->isCurlAvailable()) {
            throw new \RuntimeException("cURL support is required, but can't be found.");
        }

        $this->lastResponse = ['headers' => null, 'body' => null];
    }

    /**
     * Check if cURL is available.
     */
    public function isCurlAvailable(): bool
    {
        return function_exists('curl_init') || function_exists('curl_setopt');
    }

    /**
     * Proxies all Realforce API Class and Methods.
     */
    public function __call(string $name, array $arguments): ResourceInterface
    {
        try {
            $apiClass = ucfirst($name);
            $apiFQNClass = self::FQN_CLASS.$apiClass;

            if (false === class_exists($apiFQNClass)) {
                throw new \InvalidArgumentException(sprintf('Undefined API class %s', $apiClass));
            }

            return new $apiFQNClass($this);
        } catch (\InvalidArgumentException $e) {
            throw new \BadMethodCallException(sprintf('Undefined method %s', $name));
        }
    }

    /**
     * Get the last error returned by either the network transport, or by the API.
     *
     * If something didn't work, this contain the string describing the problem.
     *
     * @return bool|string describing the error
     */
    public function getLastError()
    {
        return $this->lastError ?: false;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API request.
     *
     * @return array assoc array
     */
    public function getLastRequest(): array
    {
        return $this->lastRequest;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API response.
     *
     * @return array assoc array with keys 'headers' and 'body'
     */
    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }

    /**
     * Get the last HTTP Status returned by the API.
     */
    public function getLastResponseHttpStatus(): ?int
    {
        return $this->lastResponseHttpStatus;
    }

    /**
     * Set the API token for restricted API calls.
     *
     * @param string $token the Realforce token authorized for restricted API calls
     */
    public function setApiToken(string $token): void
    {
        $this->apiToken = $token;
    }

    /**
     * Get the API token for restricted API calls.
     *
     * @return string|null $token the Realforce token authorized for restricted API calls
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    /**
     * Was the last request successful?
     *
     * @return bool true for success, FALSE for failure
     */
    public function success(): bool
    {
        return $this->requestSuccessful;
    }

    /**
     * Encode the data and attach it to the request.
     *
     * @param resource $curl cURL session handle, used by reference
     * @param array    $data assoc array of data to attach
     *
     * @throws \JsonException
     */
    protected function attachRequestPayload(&$curl, array $data): void
    {
        $encoded = json_encode($data, \JSON_THROW_ON_ERROR);
        $this->lastRequest['body'] = $encoded;
        curl_setopt($curl, \CURLOPT_POSTFIELDS, $encoded);
    }

    /**
     * Check if the response was successful or a failure.
     *
     * @param array       $response          the response from the curl request
     * @param array|false $formattedResponse the response body payload from the curl request
     * @param int         $timeout           the timeout supplied to the curl request
     *
     * @return bool if the request was successful
     *
     * @throws \Exception
     */
    protected function determineSuccess(array $response, $formattedResponse, int $timeout): bool
    {
        $status = $this->findHttpStatus($response, $formattedResponse);
        $this->lastResponseHttpStatus = $status;

        if ($timeout > 0 && $response['headers'] && isset($response['headers']['total_time']) && $response['headers']['total_time'] >= $timeout) {
            $this->lastError = \sprintf('Request timed out after %f seconds.', $response['headers']['total_time']);

            throw new \Exception($this->lastError);
        }

        if ($status >= 200 && $status <= 299) {
            $this->requestSuccessful = true;

            return true;
        }

        if (isset($formattedResponse['message']) && is_string($formattedResponse['message'])) {
            $this->lastError = sprintf('%d %s', $status, $formattedResponse['message']);

            throw new \Exception($this->lastError);
        }

        $this->lastError = 'Unknown error, call getLastResponse() to find out what happened.';

        throw new \Exception($this->lastError);
    }

    /**
     * Find the HTTP status code from the headers or API response body.
     *
     * @param array       $response          the response from the curl request
     * @param array|false $formattedResponse the decoded response body payload from the curl request
     *
     * @return int HTTP status code
     */
    protected function findHttpStatus(array $response, $formattedResponse): int
    {
        if (!empty($response['headers']) && isset($response['headers']['http_code'])) {
            return (int) $response['headers']['http_code'];
        }

        return 418;
    }

    /**
     * Decode the response and format any error messages for debugging.
     *
     * @param array $response the response from the curl request
     *
     * @return array|false a decoded array from JSON response
     *
     * @throws \JsonException
     */
    protected function formatResponse(array $response)
    {
        $this->lastResponse = $response;

        if (empty($response['body'])) {
            return [];
        }

        // Return the decoded response from JSON when reponse is a valid json.
        // Will return FALSE otherwise.
        return ($result = json_decode($response['body'], true, 512, \JSON_THROW_ON_ERROR)) ? $result : false;
    }

    /**
     * Get the HTTP headers as an array of header-name => header-value pairs.
     *
     * @param string $headersAsString a string of headers to parse
     *
     * @return array the parsed headers
     */
    protected function getHeadersAsArray(string $headersAsString): array
    {
        $headers = [];

        foreach (explode(\PHP_EOL, $headersAsString) as $line) {
            // Http code.
            if (1 === preg_match('/HTTP\/[1-2]/', substr($line, 0, 7))) {
                continue;
            }

            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $parts = explode(': ', $line, 2);

            if (2 !== \count($parts)) {
                continue;
            }

            $headers[$parts[0]] = $parts[1];
        }

        return $headers;
    }

    /**
     * Performs the underlying HTTP request. Not very exciting.
     *
     * @param string $http_verb the HTTP verb to use: get, post, put, patch, delete
     * @param string $url       the API method to be called
     * @param array  $args      assoc array of parameters to be passed
     * @param int    $timeout   timeout limit for request in seconds
     *
     * @return array|bool a decoded array of result or a boolean on unattended response
     *
     * @throws \Exception
     */
    public function makeRequest(string $http_verb, string $url, array $args = [], int $timeout = self::TIMEOUT)
    {
        $response = $this->prepareStateForRequest($http_verb, $url, $timeout);

        $httpHeader = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        // add Authorization token for any verb.
        if ($this->getApiToken()) {
            $httpHeader[] = "X-API-KEY: {$this->getApiToken()}";
        }

        if ('put' === $http_verb) {
            $httpHeader[] = 'Allow: PUT, PATCH, POST';
        }

        $curl = curl_init();
        curl_setopt($curl, \CURLOPT_URL, $url);
        curl_setopt($curl, \CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($curl, \CURLOPT_USERAGENT, 'Antistatique/Realforce');
        curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, \CURLOPT_VERBOSE, true);
        curl_setopt($curl, \CURLOPT_HEADER, true);
        curl_setopt($curl, \CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, \CURLOPT_SSL_VERIFYPEER, $this->verifySsl);
        curl_setopt($curl, \CURLOPT_ENCODING, '');
        curl_setopt($curl, \CURLINFO_HEADER_OUT, true);

        switch ($http_verb) {
            case 'post':
                curl_setopt($curl, \CURLOPT_POST, true);
                $this->attachRequestPayload($curl, $args);

                break;

            case 'get':
                $query = http_build_query($args, '', '&');
                curl_setopt($curl, \CURLOPT_URL, $url.'?'.$query);

                break;

            case 'delete':
                curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'DELETE');

                break;

            case 'patch':
                curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PATCH');
                $this->attachRequestPayload($curl, $args);

                break;

            case 'put':
                curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PUT');
                $this->attachRequestPayload($curl, $args);

                break;
        }

        /** @var string $response_content */
        $response_content = curl_exec($curl);
        $response['headers'] = curl_getinfo($curl);
        $response = $this->setResponseState($response, $response_content, $curl);
        $formattedResponse = $this->formatResponse($response);

        curl_close($curl);

        if (!$formattedResponse) {
            return false;
        }

        $isSuccess = $this->determineSuccess($response, $formattedResponse, $timeout);

        return \is_array($formattedResponse) ? $formattedResponse : $isSuccess;
    }

    /**
     * Save the last request and last response meta and raw data.
     *
     * @param string $http_verb the HTTP verb to use: get, post, put, patch, delete
     * @param string $url       the API URL to be called
     * @param int    $timeout   limit for request in seconds
     *
     * @return array the last request anatomy
     */
    protected function prepareStateForRequest(string $http_verb, string $url, int $timeout): array
    {
        $parts = parse_url($url);
        $this->lastError = '';

        $this->requestSuccessful = false;

        $this->lastResponse = [
            // Array of details from curl_getinfo().
            'headers' => null,
            // Array of HTTP headers.
            'httpHeaders' => null,
            // Content of the response.
            'body' => null,
        ];

        $this->lastRequest = $parts + [
            'method' => $http_verb,
            'body' => '',
            'timeout' => $timeout,
        ];

        return $this->lastResponse;
    }

    /**
     * Do post-request formatting and setting state from the response.
     *
     * @param array       $response         the response from the curl request
     * @param string|bool $response_content The body of the response from the curl request. Otherwise FALSE.
     * @param resource    $curl             the curl resource
     *
     * @return array the modified response
     *
     * @throws \Exception
     */
    protected function setResponseState(array $response, bool|string $response_content, \CurlHandle $curl): array
    {
        if (!\is_string($response_content)) {
            $this->lastError = curl_error($curl);

            throw new \Exception($this->lastError);
        }
        $headerSize = $response['headers']['header_size'];

        $response['httpHeaders'] = $this->getHeadersAsArray(substr($response_content, 0, $headerSize));
        $response['body'] = substr($response_content, $headerSize);

        if (isset($response['headers']['request_header'])) {
            $this->lastRequest['headers'] = $response['headers']['request_header'];
        }

        return $response;
    }
}
