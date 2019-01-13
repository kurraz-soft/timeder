<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\Project;
use App\Task;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    /**
     * @param int $pageSize
     * @return Collection
     */
    public function getByPage($pageSize = 20)
    {
        return Project::paginate($pageSize);
    }

    public function getAll()
    {
        $projects = Project::with('tasks.files')->get();

        return $projects;
    }

    public function getByMonth($month)
    {
        $projects = Project::all()->toArray();

        if(!$projects) return [];

        $month = explode('-',$month);
        $date = new \DateTime();
        $date->setDate($month[1],$month[0],1);

        $project_ids = [];
        foreach ($projects as $project)
        {
            $project_ids[] = $project['id'];
        }

        $tasks = Task::with('files')
                ->whereIn('project_id', $project_ids)
                ->whereMonth('created_at', $date)
                ->orderByDesc('created_at')
                ->get()
                ->mapToDictionary(function($task) {
                    return [$task['project_id'] => $task];
                });

        foreach ($projects as &$project)
        {
            if(!empty($tasks[$project['id']]))
                $project['tasks'] = $tasks[$project['id']];
            else
                $project['tasks'] = [];
        }

        return $projects;
    }

    public function create($name)
    {
        $p = new Project();
        $p->name = $name;
        $p->save();

        return $p;
    }

    public function update($id,$name)
    {
        $p = Project::find($id);
        $p->name = $name;
        $p->save();

        return $p;
    }

    /**
     * @param $id
     * @return Project
     */
    public function get($id)
    {
        return Project::with('tasks')->find($id);
    }
}