<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function success(string $message = null,$data = null, )
    {
        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => $message ?? 'Operation successful',
            'data' => $data
        ]);
    }

    public function reply($data = null, )
    {
        return response()->json([
            'data' => $data
        ]);
    }

    public function error(string $message = null,$data = null,)
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message ?? 'Errored',
            'data' => $data
        ]);
    }
}
