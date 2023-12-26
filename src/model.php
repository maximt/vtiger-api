<?php
use Rmasla\Libs\Vtiger;

function get_contacts_list(): array {
    $client = new Vtiger($_ENV['API_URL'],$_ENV['API_USERNAME'], $_ENV['API_ACCESSKEY']);

    $client->login();
    $contacts = $client->query('Contacts', 'id');
    $users = $client->query('Users', 'id');

    $contacts = array_map(function($contact) use ($users) { 
        $contact['assigned_user_id'] = $users[$contact['assigned_user_id']];
        $contact['modifiedby'] = $users[$contact['modifiedby']];
        $contact['created_user_id'] = $users[$contact['created_user_id']];
        return $contact;
    }, $contacts); // map contacts and users

    $client->logout();

    return $contacts;
}

function save_contact() {
    
}

function delete_contact() {
    
}
