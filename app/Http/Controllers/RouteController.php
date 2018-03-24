<?php

namespace App\Http\Controllers;

use App\Http\Services\Web\RouteService;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $page     = $request->input('page');
        $pageSize = $request->input('pageSize');
        $route    = $request->input('route');
        $project  = $request->input('projectSelected');
        $project  = empty($project) ? [] : explode(',', $project);

        $createTime = $request->input('createTime');
        $startTime  = isset($createTime[0]) ? $createTime[0] : null;
        $endTime    = isset($createTime[1]) ? $createTime[1] : null;

        $service = new RouteService();
        $data    = $service->page($page, $pageSize, $route, $project, $startTime, $endTime);

        return $this->jsonReturn($data, $request);

    }

    public function create(Request $request)
    {
        $project     = $request->input('projectId');
        $route       = $request->input('route');
        $description = $request->input('description');

        $service = new RouteService();
        try {
            $status = $service->create($route, $description, $project);
        } catch (\Exception $e) {
            $status = 0;
            $msg    = $e->getMessage();
        }
        $result = [
            'status' => $status ? 1 : 0,
            'msg'    => isset($msg) ? $msg : ''
        ];

        return $this->jsonReturn($result, $request);
    }

    protected function jsonReturn($result, $request)
    {
        return response()->json($result)->withCallback($request->input('callback'));
    }
}