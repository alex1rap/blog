<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRating extends Model
{
    protected $fillable = ['post_id', 'author', 'rating'];
    protected $guarded = ['post_id', 'author', 'rating'];
    public function getRouteKeyName()
    {
        return 'id';
    }
}
