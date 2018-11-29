<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\Task;

class TaskRepository
{
    public function getAll($project_id = null)
    {
        if($project_id)
            return Task::all(['project_id' => $project_id]);
        else
            return Task::all();
    }

    public function save($project_id, $name, $description, $owner_id, $doer_id, $id = null, $status = null, $rate = null)
    {
        if(!$id)
            $task = new Task();
        else
            $task = Task::find($id);

        $task->project_id = $project_id;
        $task->name = $name;
        $task->description = $description;
        $task->owner_id = $owner_id;
        $task->doer_id = $doer_id;
        $task->status = $status?$status:Task::STATUS_OPEN;
        $task->rate = $rate;
        if(!$task->save()) abort(500);

        return $task;
    }

    public function delete($id)
    {
        Task::destroy($id);
    }
}