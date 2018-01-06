<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ProjectService;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $page     = $request->input('page');
        $pageSize = $request->input('pageSize');
        $project  = $request->input('project');

        $createTime = $request->input('createTime');
        $startTime  = isset($createTime[0]) ? $createTime[0] : null;
        $endTime    = isset($createTime[1]) ? $createTime[1] : null;

        $service  = new ProjectService();
        $pageData = $service->page($page, $pageSize, $project, $startTime, $endTime);

        $return = [
            'page'     => $pageData['page'],
            'pageSize' => $pageData['pageSize'],
            'data'     => $pageData['data'],
            'total'    => $pageData['total'],
            'maxPage'  => $pageData['maxPage']
        ];

        return $this->jsonReturn($return, $request);
    }

    public function create(Request $request)
    {
        $project = $request->input('project');

        $service = new ProjectService();
        $status  = $service->create($project);
        $result  = [
            'status' => $status ? 1 : 0
        ];

        return $this->jsonReturn($result, $request);
    }

    public function update(Request $request)
    {
        $id      = $request->input('id');
        $project = $request->input('project');
        $service = new ProjectService();
        $status  = $service->update($id, $project);

        return $this->jsonReturn([
            'status' => $status ? 1 : 0
        ], $request);
    }

    public function delete(Request $request)
    {
        $id      = $request->input('id');

        $service = new ProjectService();
        $status  = $service->delete($id);

        return $this->jsonReturn([
            'status' => $status ? 1 : 0
        ], $request);
    }

    protected function jsonReturn($result, $request)
    {
        return response()->json($result)->withCallback($request->input('callback'));
    }
}
