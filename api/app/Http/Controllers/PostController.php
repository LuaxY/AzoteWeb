<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\Pagination;
use \Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Validator;
use App\Post;
use App\Comment;

class PostController extends Controller
{
    const CACHE_EXPIRE_MINUTES = 10;
    const POSTS_PER_PAGE = 10;
    const COMMENTS_PER_PAGE = 10;

    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->input('page') : 1;

        $posts = Cache::remember('posts_page_' . $page, self::CACHE_EXPIRE_MINUTES, function () {
            return Post::latest('published_at')->orderBy('id', 'desc')->published()->paginate(self::POSTS_PER_PAGE);
        });

        if ($request->ajax()) {
            return response()->json(View::make('posts.templates.posts', compact('posts'))->render());
        }

        return view('posts.index', compact('posts'));
    }

    public function show(Request $request, $id, $slug = "")
    {

        $page = $request->has('page') ? $request->input('page') : 1;

        $post = Cache::remember('posts_' . $id, self::CACHE_EXPIRE_MINUTES, function () use ($id) {
            return Post::findOrFail($id);
        });

        $comments = Cache::remember('posts_' . $id . '_comments_' . $page, self::CACHE_EXPIRE_MINUTES, function () use ($id) {
            return Comment::where('post_id', $id)->orderBy('created_at', 'asc')->paginate(self::COMMENTS_PER_PAGE);
        });

        if ($request->ajax()) {
            return response()->json(View::make('posts.templates.comments', compact('post', 'comments'))->render());
        }

        return view('posts.show', compact('post', 'comments'));
    }

    public function commentStore(Request $request, $id, $slug = "")
    {
        $page = $request->page ? $request->page : 1;

        $validator = Validator::make($request->all(), Comment::$rules['store']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $comment = new Comment;
        $comment->post_id = $id;
        $comment->author_id = Auth::user()->id;
        $comment->text = $request->comment;
        $comment->save();

        $post = Post::findOrFail($id);

        Cache::forget('posts_' . $id . '_comments_'. $page);

        return view('posts.templates.comment', compact('comment', 'post'));
    }

    public function commentDestroy(Request $request, $id, $slug = "", $commentid)
    {
        $comment = Comment::findOrFail($commentid);
        $this->authorize('destroy', $comment);
        $comment->delete();
        Cache::forget('posts_' . $id . '_comments_1');
        return redirect()->back();
    }
}
