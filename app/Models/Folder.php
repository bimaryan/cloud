<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Folder extends Model
{
    protected $table = "folders";

    protected $fillable = ['uuid', 'name', 'parent_id', 'user_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($folder) {
            $folder->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
