<?php

namespace App\Http\Controllers;


use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index($name, FileRepository $repo)
    {
        return $repo->download($name);
    }

    public function destroy($id, FileRepository $repo)
    {
        $repo->delete($id);

        return response('OK');
    }
}
