<?php
namespace App\Http\Services\Web;

use App\Http\Libraries\Page\Page;
use App\Http\Models\Web\ProjectModel;
use App\Http\Models\Web\RouteModel;
use League\Flysystem\Exception;


class RouteService
{
    public function page($page, $pageSize, $route, array $project, $startTime, $endTime)
    {
        $orm   = $this->getPageOrm($route, $project, $startTime, $endTime);
        $count = $orm->count('id');

        $page = new Page($page, $pageSize, $count);
        $data = $orm->limit($page->getNumber(), $page->getOffset())
            ->select('news_route.*', 'news_project.project')
            ->orderBy('news_route.id', 'desc')
            ->leftJoin('news_project', 'news_route.project_id', '=', 'news_project.id')
            ->get()
            ->toArray();
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
        if ($this->checkRoute($route, $projectId)) {
            throw new Exception('该路由已经配置');
        }

        return $this->add($route, $description, $projectId);
    }

    public function checkRoute($route, $projectId)
    {
        $hash = $this->genRouteHash($route);
        return RouteModel::where('status', 1)
            ->where('project_id', $projectId)
            ->where('route_hash', $hash)->get()->toArray();
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

    private function getPageOrm($route, array $project, $startTime, $endTime)
    {
        $orm = RouteModel::where('news_route.status', 1);
        if (!empty($project)) {
            $orm = $orm->whereIn('project_id', $project);
            if (!empty($route)) {
                $orm = $orm->where('route', 'like', '%' . $route . '%');
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