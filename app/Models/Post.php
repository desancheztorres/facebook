<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    public function path()
    {
        return "/posts/{$this->id}";
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
