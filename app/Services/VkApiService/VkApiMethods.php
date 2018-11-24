<?php
namespace App\Services\VkApiService;
class VkApiMethods
{
    private $vkApiClient;
    public function __construct()
    {
        $this->vkApiClient = new VkApiClient();
    }

    public function wallPost(string $message)
    {
        $response =  $this->vkApiClient->apiRequest('wall.post',['message'=> $message]);
        return $response;
    }
}