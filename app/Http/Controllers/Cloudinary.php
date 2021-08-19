<?php

namespace EmailService\Http\Controllers;

use Cloudinary\Cloudinary as Clouda;
use Illuminate\Http\Request;

class Cloudinary extends Controller
{
    public function uploads(Request $request)
    {
        $uploadedFileUrl = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();

        $response = [
            'status' => 'success',
            'message' => 'Image upload successful',
            'path' => $uploadedFileUrl
        ];

        return response()->json($response);
    }
}
