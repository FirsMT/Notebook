<?php

include('../includes/header.php');

$note = new Note($db);

if (isset($_GET['id'])) {
    $note->id = $_GET['id'];

    if ($note->delete($note->id)) {
        http_response_code(200);
        echo json_encode(['message' => 'Post Deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Post not found']);
    }
}

