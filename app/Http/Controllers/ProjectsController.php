<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProjectRepository $repo
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $repo)
    {
        return response()->json($repo->getAll());
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
     * @param  ProjectRepository $repo
     * @throws ValidationException
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProjectRepository $repo)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $repo->create($request->post('name'));

        return response('OK');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, ProjectRepository $repo)
    {
        return response()->json($repo->get($id));
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
