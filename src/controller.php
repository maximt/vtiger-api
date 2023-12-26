<?php

function action_error($code) {
    http_response_code($code);
    die("Error: {$code}");
}

function action_get_index() {
    action_get_contacts();
}

function action_get_contacts() {
    $data = get_contacts_list();
    view('contacts', $data);
}

function action_get_contact() {
    $id = preg_replace('/\W/', '', $_GET['id']);
    if (empty($id)) {
        action_error(403);
    }

    $data = get_contact($id);
    view('contact', $data);
}

function action_get_about() {
    echo 'action_get_about: This is the about page.';
}
