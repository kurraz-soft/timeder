<?php

namespace App\Http\Controllers;

use App\Repositories\FileRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param TaskRepository $repo
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TaskRepository $repo)
    {
        return response()->json($repo->getAll($request->get('project_id')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param TaskRepository $repo
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TaskRepository $repo, FileRepository $repoFile)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'owner_id' => 'required',
            'doer_id' => 'required',
            'project_id' => 'required',
            'id' => 'integer|nullable',
            'status' => 'integer|nullable',
            'rate' => 'string|nullable',
        ]);

        $task = $repo->save(
            $request->post('project_id'),
            $request->post('name'),
            $request->post('description'),
            $request->post('owner_id'),
            $request->post('doer_id'),
            $request->post('id'),
            $request->post('status'),
            $request->post('rate')
        );

        if(!$task) abort(500);

        $files = $request->file('attachments');
        if($files)
        {
            foreach ($files as $file)
            {
                /**
                 * @var \Symfony\Component\HttpFoundation\File\UploadedFile $file
                 */
                $repoFile->upload($file, $task);
            }
        }

        return response('ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param TaskRepository $repo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, TaskRepository $repo)
    {
        $repo->delete($id);

        return response('OK');
    }
}
