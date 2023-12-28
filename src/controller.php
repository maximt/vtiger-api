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

function action_post_contact() {
    $id = preg_replace('/\W/', '', $_POST['id']);
    if (empty($id)) {
        action_error(403);
    }

    $filter_flags = FILTER_SANITIZE_STRING | FILTER_SANITIZE_FULL_SPECIAL_CHARS;
    $fields = [
        'id' => $filter_flags,
        'lastname' => $filter_flags,
        'firstname' => $filter_flags,
        'mobile' => $filter_flags,
        'assigned_user_id' => $filter_flags,
    ];


    $input = filter_input_array(INPUT_POST, $fields);

    save_contact($id, $input);

    redirect('contacts');
}

function action_delete_contact() {
    $id = preg_replace('/\W/', '', $_POST['id']);
    if (empty($id)) {
        action_error(403);
    }

    echo "action_post_delete_contact: {$id}";
}
function action_get_about() {
    echo 'action_get_about: This is the about page.';
}
