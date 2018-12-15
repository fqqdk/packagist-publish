<?php

namespace Fqqdk\Packagist;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Exception;
use GuzzleHttp\Client;

class SelfDeployCommand
{
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


    /**
     * @param Event $event
     * @throws Exception
     */
    public static function main(Event $event)
    {
        /** @var Composer $composer */
        $composer = $event->getComposer();
        echo self::create($event->getArguments(), $composer->getConfig()->get('vendor-dir'))
            ->updatePackage($composer->getPackage()->getName());
    }

    /**
     * @param array $arguments
     * @param string $vendorDir
     * @return SelfDeployCommand
     * @throws Exception
     */
    public static function create(array $arguments, $vendorDir)
    {
        $userName = isset($arguments[0]) ? $arguments[0] : getenv('PACKAGIST_USERNAME');
        $apiToken = isset($arguments[1]) ? $arguments[1] : getenv('PACKAGIST_API_TOKEN');
        $packagistUrl = (isset($arguments[2]) ? $arguments[2] : getenv('PACKAGIST_URL')) ?: 'https://packagist.org/';

        if (!$userName && !$apiToken) {
            throw new Exception("Username or api token missing. Provide it via cli arguments or env variables (PACKAGIST_USERNAME and PACKAGIST_API_TOKEN)");
        }

        return new self(self::getClient($vendorDir), $packagistUrl, $userName, $apiToken);
    }

    private static function getClient($vendorDir)
    {
        require_once $vendorDir . '/autoload.php';
        return new Client;
    }

    public function __construct(Client $client, $packagistUrl, $userName, $apiToken)
    {
        $this->client = $client;
        $this->packagistUrl = $packagistUrl;
        $this->userName = $userName;
        $this->apiToken = $apiToken;
    }

    private function updatePackage($packageName)
    {
        return $this->client
            ->post($this->getApiUrl(), array('json' => $this->getPayLoad($packageName)))
            ->getBody();
    }

    private function getApiUrl()
    {
        return \GuzzleHttp\Psr7\uri_for("{$this->packagistUrl}api/update-package")->withQuery(http_build_query(array(
            'username' => $this->userName,
            'apiToken' => $this->apiToken,
        )));
    }

    private function getPayLoad($packageName)
    {
        return array('repository' => array('url' => "{$this->packagistUrl}packages/{$packageName}"));
    }
}
