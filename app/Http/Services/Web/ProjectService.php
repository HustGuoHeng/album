<?php
namespace App\Http\Services\Web;

use App\Http\Models\Web\ProjectModel;

class ProjectService
{
    public function page($page, $pageSize, $project, $createTime, $endTime)
    {
        $page     = intval($page) ? intval($page) : 1;
        $pageSize = intval($pageSize) ? intval($pageSize) : 10;

        $orm     = $this->getPageOrm($project, $createTime, $endTime);
        $count   = $orm->count('id');
        $maxPage = intval(ceil(floatval($count) / $pageSize));
        $page    = ($maxPage >= 1 && $page > $maxPage) ? $maxPage : $page;
        $data    = $orm->limit($pageSize, $pageSize * ($page - 1))->orderBy('id', 'desc')->get()->toArray();
        return [
            'data'     => $data,
            'total'    => $count,
            'page'     => $page,
            'maxPage'  => $maxPage,
            'pageSize' => $pageSize
        ];
    }

    public function create($project)
    {
        if (empty($project)) {
            throw new \Exception('项目名称不能为空');
        }

        if ($this->projectExist($project)) {
            throw new \Exception('项目名称已经存在');
        }

        if (!$this->add($project)) {
            throw new \Exception('添加失败');
        }

        return true;
    }

    public function update($id, $project)
    {
        $num = ProjectModel::where('id', $id)
            ->update(['project' => $project]);

        if ($num == 0) {
            throw new \Exception('未找到修改对象');
        }
        return true;
    }

    public function delete($id)
    {
        $num = ProjectModel::where('id', $id)
            ->where('status', 1)
            ->update(['status' => 0]);
        if ($num == 0) {
            throw new \Exception('未找到删除对象');
        }
        return true;
    }


    protected function projectExist($project)
    {
        return $this->find($project) ? true : false;
    }

    protected function find($project)
    {
        return ProjectModel::where('status', 1)->where('project', $project)->get()->toArray();
    }

    protected function add($project)
    {
        $model          = new ProjectModel();
        $model->project = $project;
        $model->status  = 1;
        $status         = $model->save();
        return $status ? true : false;
    }


    private function getPageOrm($project, $startTime, $endTime)
    {
        $orm = ProjectModel::where('status', 1);
        if ($project) {
            $orm = $orm->where('project', 'like', '%' . $project . '%');
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