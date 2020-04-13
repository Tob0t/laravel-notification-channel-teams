<?php

namespace NotificationChannels\MicrosoftTeams;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Arr;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;

class MicrosoftTeams
{
    /**
     * API HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected HttpClient $httpClient;

    /**
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(HttpClient $http)
    {
        $this->httpClient = $http;
    }

    /**
     * Send a message to a MicrosoftTeams channel.
     *
     * @param string $url
     * @param array $data
     *
     * @return array
     */
    public function send(string $url, array $data)
    {
        if(!$url){
            throw CouldNotSendNotification::microsoftTeamsWebhookUrlMissing();
        }
        try {
            $response = $this->httpClient->post($url, [
                'json' => $data,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::microsoftTeamsRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithMicrosoftTeams($exception);
        }

        return $response;
    }
}