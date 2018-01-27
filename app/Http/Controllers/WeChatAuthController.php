<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeChatAuthController extends Controller
{
    public function index(Request $request)
    {
        echo "<pre>";
        print_r($_GET);
        print_r($_POST);
    }

}