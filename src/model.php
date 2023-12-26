<?php
use Rmasla\Libs\Vtiger;

function get_contacts_list(): array {
    $client = new Vtiger($_ENV['API_URL'],$_ENV['API_USERNAME'], $_ENV['API_ACCESSKEY']);
    $client->login();
    $list = $client->getList('Contacts');
    $client->logout();
    return $list;
}

function save_contact() {
    
}

function delete_contact() {
    
}
