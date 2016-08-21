<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\Pagination;
use \Cache;

use App\Post;
use App\Comment;

class PostController extends Controller
{
    const CACHE_EXPIRE_MINUTES = 10;
	const POSTS_PER_PAGE = 6;

    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->input('page') : 1;

        $posts = Cache::remember('posts_page_' . $page, self::CACHE_EXPIRE_MINUTES, function() {
            return Post::latest('published_at')->orderBy('id', 'desc')->published()->paginate(self::POSTS_PER_PAGE);
        });

		return view('posts.index', compact('posts'));
    }

    public function show($id, $slug = "")
    {
        $post = Cache::remember('posts_' . $id, self::CACHE_EXPIRE_MINUTES, function() use($id) {
            return Post::findOrFail($id);
        });

        $comments = Cache::remember('posts_' . $id . '_comments', self::CACHE_EXPIRE_MINUTES, function() use($id) {
            return Comment::where('post_id', $id)->orderBy('created_at', 'desc')->paginate(self::POSTS_PER_PAGE);
        });

        return view('posts.show', compact('post', 'comments'));
    }
}
