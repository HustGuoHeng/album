<?php
namespace App\Http\Services\Web;

use App\Http\Libraries\Page\Page;
use App\Http\Models\Web\ProjectModel;
use App\Http\Models\Web\RouteModel;
use League\Flysystem\Exception;


class RouteService
{
    public function page($page, $pageSize, $project, $startTime, $endTime)
    {
        $orm   = $this->getPageOrm($project, $startTime, $endTime);
        $count = $orm->count('id');

        $page = new Page($page, $pageSize, $count);
        $data = $orm->limit($page->getNumber(), $page->getOffset())->orderBy('id', 'desc')->get()->toArray();
        return [
            'data'     => $data,
            'total'    => $count,
            'page'     => $page->getPage(),
            'maxPage'  => $page->getMaxPage(),
            'pageSize' => $page->getNumber()
        ];
    }

    public function create($route, $description, $projectId)
    {
        if (!ProjectModel::find($projectId)) {
            throw new Exception('项目ID不存在');
        }
        if ($this->checkRoute($route)) {
            throw new Exception('该路由已经配置');
        }

        return $this->add($route, $description, $projectId);
    }

    public function checkRoute($route)
    {
        $hash = $this->genRouteHash($route);
        return RouteModel::where('status', 1)->where('route_hash', $hash)->get()->toArray();
    }

    protected function add($route, $description, $projectId)
    {
        $routeHash = $this->genRouteHash($route);

        $model              = new RouteModel();
        $model->route       = $route;
        $model->status      = 1;
        $model->route_hash  = $routeHash;
        $model->description = $description;
        $model->project_id  = $projectId;
        $status             = $model->save();
        return $status ? true : false;
    }

    protected function genRouteHash($route)
    {
        return md5($route);
    }

    private function getPageOrm($project, $startTime, $endTime)
    {
        $orm = RouteModel::where('status', 1);
        if (!empty($project)) {
            if (is_array($project)) {
                $orm = $orm->whereIn('project_id', $project);
            } else {
                $orm = $orm->where('project', '=', $project);
            }
        }
        if ($startTime) {
            $startTime = date('Y-m-d H:i:s', strtotime($startTime));
            $orm       = $orm->where('created_at', '>=', $startTime);
        }
        if ($endTime) {
            $endTime = date('Y-m-d H:i:s', strtotime($endTime));
            $orm     = $orm->where('created_at', '<=', $endTime);
        }
        return $orm;
    }
}