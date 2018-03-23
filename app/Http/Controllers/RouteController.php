<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $page     = $request->input('page');
        $pageSize = $request->input('pageSize');
        $project  = $request->input('project');
        $project  = empty($project) ? [] : explode(',', $project);

        $createTime = $request->input('createTime');
        $startTime  = isset($createTime[0]) ? $createTime[0] : null;
        $endTime    = isset($createTime[1]) ? $createTime[1] : null;


    }
}