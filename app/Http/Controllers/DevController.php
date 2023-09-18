<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DevController extends Controller
{
    //
    // public function test()
    // {
        // File::makeDirectory(storage_path('app/public/avatars'));

        // return "created";

    // }

    public function loginAPI(Request $request)
    {
        // File::makeDirectory(storage_path('app/public/files'));
        $credentials = array($request->username,$request->password);
        // return json_encode($credentials);
        $result = [
            // 'recordsTotal'    => $groupCount,
            'result' => $credentials,
            'success' => true,
        ];

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    }
}
