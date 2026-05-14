<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * JSON Response Helper
 */

function json_response($success = true, $message = '', $data = null, $http_code = 200) {
    http_response_code($http_code);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message,
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Success response
 */
function success($message = '', $data = null, $http_code = 200) {
    json_response(true, $message, $data, $http_code);
}

/**
 * Error response
 */
function error($message = '', $data = null, $http_code = 400) {
    json_response(false, $message, $data, $http_code);
}
?>
