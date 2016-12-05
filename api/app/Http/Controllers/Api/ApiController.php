<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected function input()
    {
        return json_decode(file_get_contents("php://input"));
    }

    protected function result($result)
    {
        $result->success = true;

        $data = [
            "result" => $result,
            "id"     => 1,
            "error"  => null,
        ];

        return json_encode($data);
    }

    protected function softError($reason)
    {
        $result = new \stdClass;
        $result->success = false;
        $result->error = $reason;

        $data = [
            "result" => $result,
            "id"     => 1,
            "error"  => null,
        ];

        return json_encode($data);
    }

    protected function criticalError($reason, $params = null, $code = 0)
    {
        $error = new \stdClass;
        $error->code = $code;
        $error->message = $reason;
        $error->data = $params;

        $data = [
            "result" => null,
            "id"     => 1,
            "error"  => $error,
        ];

        return json_encode($data);
    }
}
