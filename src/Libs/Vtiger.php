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

    private function getResponse($response) {
        if ($response->getStatusCode() != 200) {
            throw new \Exception("Wrong result. Response error:\n {$response->getStatusCode()}");
        }

        $response = json_decode($response->getBody(), true);
        if (!$response['success']) {
            throw new \Exception("Wrong result. Response error:\n {$response['error']['message']}");
        }

        return $response;
    }

    public function login() {
        // todo check expireTime

        $response = $this->getResponse(
            $this->client->get("{$this->url}?operation=getchallenge&username={$this->username}")
        );
        $this->token = $response['result']['token'];
        $this->serverTime = $response['result']['serverTime'];
        $this->expireTime = $response['result']['expireTime'];

        $response = $this->getResponse(
            $this->client->post($this->url, [
                'body' => [
                    'operation' => 'login',
                    'username' => $this->username,
                    'accessKey' => md5($this->token . $this->accessKey)
                ]
            ])
        );
        $this->sessionName = $response['result']['sessionName'];
    }

    public function logout() {
        $this->client->post($this->url, [
            'body' => [
                'operation' => 'logout',
                'sessionName' => $this->sessionName
            ]
        ]);
    }

    public function query(string $module, string $group_by = '') {
        if (!isset($this->sessionName)) {
            throw new \Exception('Cannot get list: not authorized');
        }

        $query = "SELECT * FROM {$module};";
        $query = urlencode($query);
        $response = $this->getResponse(
            $this->client->get("{$this->url}?operation=query&sessionName={$this->sessionName}&query={$query}")
        );

        $result = $response['result'];

        if ($group_by) {
            $result = array_combine(array_column($result, $group_by), $result);
        }

        return $result;
    }

    public function getOne(string $id) {
        if (!isset($this->sessionName)) {
            throw new \Exception('Cannot get list: not authorized');
        }

        $response = $this->getResponse(
            $this->client->get("{$this->url}?operation=retrieve&sessionName={$this->sessionName}&id={$id}")
        );

        return $response['result'];
    }

    public function update(string $id, array $data) {
        // todo copy array
        $data['id'] = $id;
        // $_data = http_build_query($data);
        $_data = json_encode($data);
 
        $this->getResponse(
            $this->client->post($this->url, [
                'body' => [
                    'operation' => 'update',
                    'sessionName' => $this->sessionName,
                    'element' => $_data
                ]
            ])
        );
    }

    public function delete(string $id) {
        $this->getResponse(
            $this->client->post($this->url, [
                'body' => [
                    'operation' => 'delete',
                    'sessionName' => $this->sessionName,
                    'id' => $id
                ]
            ])
        );
    }
}