<?php

function action_get_404() {
    http_response_code(404);
    echo '404 - Not Found.';
}

function action_get_index() {
    $list = get_contacts_list();
    view('index', ['list' => $list]);
}

function action_get_about() {
    echo 'action_get_about: This is the about page.';
}

function action_get_contact() {
    echo 'action_get_contact: Contact us at contact@example.com.';
}
