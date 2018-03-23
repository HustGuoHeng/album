<?php
namespace App\Http\Services\Web;

use App\Http\Libraries\Page\Page;
use App\Http\Models\Web\RouteModel;

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

    private function getPageOrm($project, $startTime, $endTime)
    {
        $orm = RouteModel::where('status', 1);
        if ($project) {
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