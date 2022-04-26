<?php

namespace Fqqdk\Packagist;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;

class Publisher
{
    const PACKAGIST_BASE_URL = 'https://packagist.org/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $packagistUrl;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $apiToken;


    public static function create($vendorDir, $packagistUrl, $userName, $apiToken)
    {
        require_once $vendorDir . '/autoload.php';
        return new self(new Client(), $packagistUrl, $userName, $apiToken);
    }

    public function __construct(Client $client, $packagistUrl, $userName, $apiToken)
    {
        $this->client = $client;
        $this->packagistUrl = $packagistUrl;
        $this->userName = $userName;
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $packageName
     * @return array
     * @throws Exception
     */
    public function updatePackage($packageName)
    {
        $response = \GuzzleHttp\json_decode($this->client
            ->post($this->getApiUrl(), array('json' => $this->getPayLoad($packageName)))
            ->getBody()->getContents(), true);

        if (!isset($response['status']) || $response['status'] != 'success') {
            throw new Exception("Could not publish package!");
        }

        return $response['jobs'];
    }

    private function getApiUrl()
    {
        return Utils::uriFor("{$this->packagistUrl}api/update-package")
            ->withQuery(http_build_query(array(
                'username' => $this->userName,
                'apiToken' => $this->apiToken,
            )));
    }

    private function getPayLoad($packageName)
    {
        return array('repository' => array(
            'url' => "{$this->packagistUrl}packages/{$packageName}"
        ));
    }
}
