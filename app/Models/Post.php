<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'intro', 'body', 'author'];
    protected $guarded = ['title', 'intro', 'body', 'author'];
    public function getRouteKeyName()
    {
        return 'id';
    }
}
