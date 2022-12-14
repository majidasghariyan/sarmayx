<?php

function response_success($result=null, $code=200)
{
    return response()->json([
        'result' => $result,
        'status' => $code
    ], $code);
}

function response_error($error=null, $code=409)
{
    return response()->json([
        'error' => $error,
        'status' => $code
    ], $code);
}

