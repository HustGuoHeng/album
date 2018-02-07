<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        return response()->json($request->input('name'));
    }
}