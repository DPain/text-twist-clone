<?php

session_start();

include '../util/combination_util.php';

if (isset($_SESSION['rack']) && isset($_SESSION['all'])) {
    //Make sure that it is a POST request.
    if (0 != strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
        throw new Exception('Request method must be POST!');
    }

    //Make sure that the content type of the POST request has been set to application/json
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
    if (0 != strcasecmp($contentType, 'application/json')) {
        throw new Exception('Content type must be: application/json');
    }

    //Receive the RAW post data.
    $content = trim(file_get_contents('php://input'));

    //Attempt to decode the incoming RAW post data from JSON.
    $input = json_decode($content, true);

    //If json_decode failed, the JSON is invalid.
    if (!is_array($input)) {
        throw new Exception('Received content contained invalid JSON!');
    }

    //Process the JSON.
    $action = $_SERVER['REQUEST_METHOD'];
    switch ($action) {
    case 'GET':
        echo 'Not supported yet.';
        break;
    case 'POST':
        $status = false;
        $input['word'] = strtoupper($input['word']);
        foreach ($_SESSION['all'] as &$arr) {
            if (in_array($input['word'], $arr)) {
                $status = true;
                break;
            }
        }

        $payload['word'] = $input['word'];
        if ($status) {
            $payload['status'] = 'RIGHT';
            echo json_encode($payload);
        } else {
            $payload['status'] = 'WRONG';
            echo json_encode($payload);
        }
        break;
    }

    return;
} else {
    echo 'Please access the api with a valid session id.';
}

return;
