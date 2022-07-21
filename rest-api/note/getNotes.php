<?php

include('../includes/header.php');

$note = new Note($db);
$data = $note->getNotes()->get_result();

if (mysqli_num_rows($data)!=0) {
    $notes = [];
    
    while ($row = $data->fetch_array(MYSQLI_ASSOC)) {
        $notes[] = $row;
    }
    http_response_code(200);
    echo json_encode($notes);

} else {
    http_response_code(404);
    echo json_encode(['message' => 'No Data']);
}