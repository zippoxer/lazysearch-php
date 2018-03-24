<?php namespace Lazysearch;

use \Lazysearch\Folder;
use GuzzleHttp\Client as GuzzleClient;

class LazysearchException extends \Exception {
    protected $data;
    public function __construct($message="", $code=0 , Exception $previous=NULL, $data = NULL)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
    public function getData()
    {
        return $this->data;
    }
}

class Client
{
    private $apiKey;

    /** @var GuzzleHttp\Client $client */
    private $guzzle;

    /** @var array $defaultOptions */
    public static $defaultOptions = [
        'page' => 1,
        'hitsPerPage' => 10,
    ];

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->guzzle = new GuzzleClient([
            'base_uri' => 'http://lazysearch.zippo.io:1323'
        ]);
    }

    /**
     * Get folder by name.
     *
     * @param string $name
     * @return Folder
     **/
    public function folder(string $name)
    {
        return new Folder($this, $name);
    }

    /**
     * Get folder by name.
     *
     * @param string $property
     * @return Folder
     **/
    public function __get($property) {
        return $this->folder($property);
    }

    public function _request(string $method, string $path,
        array $query = null, array $body = null)
    {
        $data = [
            'http_errors' => false,
            'headers' => [
                'X-Lazysearch-API-Key'
            ]
        ];
        if($query) {
            $data['query'] = $query;
        }
        if($body) {
            $data['json'] = $body;
        }
        $resp = $this->guzzle->request($method, $path, $data);
        // $content = json_decode($resp->getBody());
        $code = $resp->getStatusCode();
        if($code != 200) {
            // TODO: parse JSON error from response
            $errorMessage = $resp->getBody()->getContents();
            throw new LazysearchException("lazysearch failed request {$path} with status".
                "code {$code}: {$errorMessage}",
                $code);
        }
        return json_decode($resp->getBody());
    }
}