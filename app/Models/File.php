<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class File extends Model
{
    protected $table = "files";

    protected $fillable = ['uuid', 'name', 'status', 'path', 'size', 'mime_type', 'user_id', 'folder_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($file) {
            $file->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }
}
