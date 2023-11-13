<?php

/**
 * @param  array  $data
 */
function success($message = 'Success', $data = [], $statusCode = 200, $success = true)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ], $statusCode);
}

/**
 * @param  array  $data
 */
function error($message = 'An error occurred', $statusCode = 400, $data = [])
{
    return success($message, $data, $statusCode, false);
}

function notFound($message = 'Resource not found')
{
    return error($message, 404);
}

function forbidden($message = 'Forbidden')
{
    return error($message, 403);
}
