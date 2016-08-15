<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function author()
	{
		return $this->hasOne(User::class, 'id', 'author_id');
	}

	public function comments()
	{
		return $this->hasMany(Comment::class, 'post_id', 'id')->orderBy('created_at', 'desc');
	}
}
