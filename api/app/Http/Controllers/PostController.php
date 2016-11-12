<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericException;
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
use ChrisKonnertz\OpenGraph\OpenGraph;

class PostController extends Controller
{
    const CACHE_EXPIRE_MINUTES = 10;
    const POSTS_PER_PAGE = 8;
    const POSTS_IN_INDEX = 6;
    const COMMENTS_PER_PAGE = 10;

    public function index(Request $request)
    {
        $posts = Cache::remember('posts_index', self::CACHE_EXPIRE_MINUTES, function () {
            return Post::latest('published_at')->orderBy('id', 'desc')->published()->paginate(self::POSTS_IN_INDEX);
        });

        return view('posts.index', compact('posts'));
    }

    public function news(Request $request)
    {
        $page = $request->has('page') ? $request->input('page') : 1;

        $posts = Cache::remember('posts_page_' . $page, self::CACHE_EXPIRE_MINUTES, function () {
            return Post::latest('published_at')->orderBy('id', 'desc')->published()->paginate(self::POSTS_PER_PAGE);
        });

        if ($request->ajax())
        {
            return response()->json(View::make('posts.templates.posts', compact('posts'))->render());
        }

        return view('posts.news', compact('posts'));
    }

    public function newsType(Request $request, $type)
    {
        if(config('dofus.news_type.'.$type))
        {
            $typeConfig = config('dofus.news_type.'.$type);
            $page = $request->has('page') ? $request->input('page') : 1;

            $posts = Cache::remember('posts_'.$type.'_page_' . $page, self::CACHE_EXPIRE_MINUTES, function () use($type) {
                return Post::latest('published_at')->orderBy('id', 'desc')->published()->where('type', $type)->paginate(self::POSTS_PER_PAGE);
            });

            if ($request->ajax())
            {
                return response()->json(View::make('posts.templates.posts', compact('posts'))->render());
            }

            return view('posts.type', compact('posts', 'type', 'typeConfig'));
        }
        else
        {
            throw new GenericException('invalid_news_type');
        }

    }

    public function show(Request $request, $id, $slug = "")
    {
        $page = $request->has('page') ? $request->input('page') : 1;

        $post = Cache::remember('posts_' . $id, self::CACHE_EXPIRE_MINUTES, function () use ($id) {
            return Post::findOrFail($id);
        });

        if ($post->isDraft() && (Auth::guest() || !Auth::user()->isAdmin()))
        {
            abort(404);
        }

        if ($slug == "" || $slug != $post->slug)
        {
            return redirect()->route('posts.show', ['id' => $id, 'slug' => $post->slug]);
        }

        $og = new OpenGraph();

        $og->title($post->title)
           ->type('article')
           ->image(URL::asset($post->image))
           ->description(html_entity_decode(strip_tags($post->preview)))
           ->url($request->url())
           ->siteName(config('dofus.title') . ' - ' . config('dofus.subtitle'));

        $comments = Cache::remember('posts_' . $id . '_comments_' . $page, self::CACHE_EXPIRE_MINUTES, function () use ($id) {
            return Comment::where('post_id', $id)->orderBy('created_at', 'asc')->paginate(self::COMMENTS_PER_PAGE);
        });

        if ($request->ajax())
        {
            return response()->json(View::make('posts.templates.comments', compact('post', 'comments'))->render());
        }

        return view('posts.show', compact('post', 'comments', 'og'));
    }

    public function commentStore(Request $request, $id, $slug = "")
    {
        $page = $request->page ? $request->page : 1;

        if(!Auth::user()->isAdmin())
        {
            $validator = Validator::make($request->all(), Comment::$rules['store']);
            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            }
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

    public function redirect(Request $request, $id, $slug = "")
    {
        return redirect()->route('posts.show', ['id' => $id, 'slug' => $slug]);
    }

    public function commentDestroy(Request $request, $id, $slug = "", $commentid)
    {
        $comment = Comment::findOrFail($commentid);
        $this->authorize('destroy', $comment);
        $comment->delete();
        Cache::forget('posts_' . $id . '_comments_1');
        Cache::forget('posts_' . $id . '_comments_2');
        Cache::forget('posts_' . $id . '_comments_3');
        return redirect()->back();
    }

}
