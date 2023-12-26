<?php
use Rmasla\Libs\Vtiger;

function get_contacts_list(): array {
    $client = new Vtiger($_ENV['API_URL'],$_ENV['API_USERNAME'], $_ENV['API_ACCESSKEY']);

    $client->login();

    $contacts = $client->query('Contacts', 'id');
    $users = $client->query('Users', 'id');

    $client->logout();

    // return $contacts;
    return ['contacts' => $contacts, 'users' => $users]; 
}

function get_contact($id) {
    $client = new Vtiger($_ENV['API_URL'],$_ENV['API_USERNAME'], $_ENV['API_ACCESSKEY']);

    $client->login();

    $contact = $client->getOne($id);
    $users = $client->query('Users', 'id');

    $client->logout();

    return ['contact' => $contact, 'users' => $users];    
}

function save_contact() {
    
}

function delete_contact() {
    
}
