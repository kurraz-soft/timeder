<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

/**
 * Class File
 * @package App
 *
 * @property $id
 * @property $name
 * @property $orig_name
 * @property $ext_id
 * @property $ext_type
 *
 * @property $ext
 */
class File extends Model
{
    protected $appends = ['path'];

    public function ext()
    {
        return $this->morphTo();
    }

    public function getPathAttribute()
    {
        return URL::route('file',[
            'name' => $this->name,
        ]);
    }
}
