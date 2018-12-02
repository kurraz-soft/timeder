<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileRepository
{
    public function upload(UploadedFile $uploadedFile, $ext_object)
    {
        $name = str_random(32);
        //$uploadedFile->move($_SERVER['DOCUMENT_ROOT'].'/public/upload', $name);

        if(!($path = Storage::putFileAs('upload',$uploadedFile, $name))) throw new UploadException();

        $orig_name = $uploadedFile->getClientOriginalName();

        $db_file = new File();
        $db_file->name = $name;
        $db_file->orig_name = $orig_name;
        $db_file->ext_id = $ext_object->id;
        $db_file->ext_type = get_class($ext_object);
        $db_file->save();

        return $db_file;
    }

    public function getByName($name)
    {
        /**
         * @var File $file
         */
        $file = File::where('name', $name)->first();
        if(!$file) throw new NotFoundHttpException();

        return $file;
    }

    public function delete($id)
    {
        $file = File::find($id);

        Storage::delete('upload/'.$file->name);

        File::destroy($id);
    }
}