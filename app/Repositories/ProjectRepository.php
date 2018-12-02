<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\Project;
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

    public function create($name)
    {
        $p = new Project();
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