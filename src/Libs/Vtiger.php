<?php
namespace Rmasla\Libs;

use GuzzleHttp\Client;

class Vtiger {
    private Client $client;
    private string $url;
    private string $username;
    private string $accessKey;

    private string $token;
    private string $serverTime;
    private string $expireTime;
    private string $sessionName;

    public function __construct(string $url, string $username, string $accessKey) {
        $this->client = new Client();
        $this->url = $url;
        $this->username = $username;
        $this->accessKey = $accessKey;
    }

    private function getResult($response) {
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Wrong result. Response error:\n {$response->getStatusCode()}");
        }

        $result = json_decode($response->getBody(), true);
        if (!$result['success']) {
            throw new \Exception("Wrong result. Response error:\n {$result['error']['message']}");
        }

        return $result;
    }

    public function login() {
        // todo check expireTime

        $result = $this->getResult(
            $this->client->get("{$this->url}?operation=getchallenge&username={$this->username}")
        );
        $this->token = $result['result']['token'];
        $this->serverTime = $result['result']['serverTime'];
        $this->expireTime = $result['result']['expireTime'];

        $result = $this->getResult(
            $this->client->post($this->url, [
                'body' => [
                    'operation' => 'login',
                    'username' => $this->username,
                    'accessKey' => md5($this->token . $this->accessKey)
                ]
            ])
        );
        $this->sessionName = $result['result']['sessionName'];
    }

    public function logout() {
        $this->client->post($this->url, [
            'body' => [
                'operation' => 'logout',
                'sessionName' => $this->sessionName
            ]
        ]);
    }

    public function getList($module) {
        if (!isset($this->sessionName)) {
            throw new \Exception('Cannot get list: not authorized');
        }

        $query = "SELECT * FROM Contacts;";
        $query = urlencode($query);
        $result = $this->getResult(
            $this->client->get("{$this->url}?operation=query&sessionName={$this->sessionName}&query={$query}")
        );
        return $result['result'];
    }
}