<?php

namespace Fqqdk\Packagist;

use Exception;
use Guzzle\Http\Client;
use Guzzle\Http\Url;

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
        $apiUrl = (string)$this->getApiUrl();
        $response = json_decode($this->client
            ->post($apiUrl, array('Content-Type' => 'application/json'), json_encode($this->getPayLoad($packageName)))
            ->send()->getBody(true), true);

        if ($response['status'] !== 'success') {
            throw new Exception("Could not publish package!");
        }

        return $response['jobs'];
    }

    private function getApiUrl()
    {
        return Url::factory("{$this->packagistUrl}api/update-package")->setQuery(array(
            'username' => $this->userName,
            'apiToken' => $this->apiToken,
        ));
    }

    private function getPayLoad($packageName)
    {
        return array('repository' => array(
            'url' => "{$this->packagistUrl}packages/{$packageName}"
        ));
    }
}
