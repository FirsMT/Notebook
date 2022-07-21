<?php

include('../includes/header.php');

$note = new Note($db);

if (count($_POST)) {
    $params = [
        'id' => $_POST['id'],
        'fullName' => $_POST['fullName'],
        'company' => $_POST['company'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'birthday' => $_POST['birthday'],
        'photo' => $_POST['photo'],
    ];

    if ($note->update($params)) {
        http_response_code(200);
        echo json_encode(array('message' => 'Post updated'));
    } else {
        http_response_code(412);
        echo json_encode(array('message' => 'Post not updated'));
    }
}
