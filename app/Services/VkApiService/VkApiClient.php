<?php
namespace App\Services\VkApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkApiClient
{

    private $client;
    private $accessToken;
    private $apiVersion;

    public function __construct()
    {
        $this->client      = new Client();
        $this->accessToken = env('VK_ACCESS_TOKEN');
        $this->apiVersion  = env('VK_API_VERSION');
    }

    public function apiRequest(string $apiMethod, array $options)
    {
        $options['v']            = $this->apiVersion;
        $options['access_token'] = $this->accessToken;
        $uri                     = "https://api.vk.com/method/$apiMethod?" .
                                   http_build_query($options);
        try {
            $response = $this->client->request('POST', $uri);
        } catch (GuzzleException $e) {
            return null;
        }
        $responseData = json_decode((string)$response->getBody());

        return $responseData;
    }


}