<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \Cache;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yuansir\Toastr\Facades\Toastr;

class PostController extends Controller
{
    private function fetchNewsType()
    {
        $typeArray = [];
        if (config('dofus.news_type')) {
            foreach (config('dofus.news_type') as $type) {
                $typeArray[$type['db']] = $type['name'];
            }
        }
        return $typeArray;
    }
    public function index()
    {
        return view('admin.posts.index');
    }

    public function create()
    {
        $typeArray = $this->fetchNewsType();
        return view('admin.posts.create', compact('typeArray'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Post::$rules['store']);

        if (!array_key_exists($request->type, config('dofus.news_type'))) {
            return redirect(route('admin.post.create'))
                ->withErrors(['type' => 'Le type est invalide'])
                ->withInput();
        }
        if ($validator->fails()) {
            return redirect(route('admin.post.create'))
                ->withErrors($validator)
                ->withInput();
        }
        // DATES //
        $published    = $request->published ? '1' : '0';
        $published_at = $request->published ? $request['published_at'] : Carbon::now();

        // IMAGE (RECEIVE LINK) //
        $explode    = explode(url('/'), $request->url_main_image);
        $image_link = $explode[1];

        // INSERT INTO DB //
        $request->user()->posts()->create([
            'title'        => $request->title,
            'type'         => $request->type,
            'preview'      => $request->preview,
            'content'      => $request->content,
            'image'        => $image_link,
            'published'    => $published,
            'published_at' => $published_at
        ]);

        $this->clearCache(null, $request->type);

        Toastr::success('Post created', $title = null, $options = []);
        return redirect(route('admin.posts'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('destroy', $post);

        if ($post->id != config('dofus.motd.postid')) {
            $post->delete();
            $this->clearCache($post->id, $post->type);

            return response()->json([], 200);
        } else {
            return response()->json(['motd' => ['0' => 'Can\'t delete this post (MOTD)']], 422);
        }
    }

    public function edit(Post $post)
    {
        $typeArray = $this->fetchNewsType();
        $post = Post::findOrFail($post->id);
        return view('admin.posts.edit', compact('post', 'typeArray'));
    }

    public function update(Post $post, Request $request)
    {
        $post = Post::findOrFail($post->id);

        $validator = Validator::make($request->all(), Post::$rules['store']);

        if (!array_key_exists($request->type, config('dofus.news_type'))) {
            return redirect()->back()
                ->withErrors(['type' => 'Le type est invalide'])
                ->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // DATES //
        $published    = $request->published ? '1' : '0';
        $published_at = $request->published ? $request['published_at'] : Carbon::now();

        // IMAGE (RECEIVE LINK) //
        $explode    = explode(url('/'), $request->url_main_image);
        $image_link = $explode[1];

        // UPDATE INTO DB //
        $post->update([
            'title'        => $request->title,
            'type'         => $request->type,
            'preview'      => $request->preview,
            'content'      => $request->content,
            'image'        => $image_link,
            'published'    => $published,
            'published_at' => $published_at
        ]);

        $this->clearCache($post->id, $post->type);

        Toastr::success('Post updated', $title = null, $options = []);
        return redirect(route('admin.posts'));
    }

    private function clearCache($id = null, $type = null)
    {
        // Clear specified post
        if ($id) {
            Cache::forget('posts_'.$id);
        }
        if ($type) {
            Cache::forget('posts_'.$type.'_page_1');
            Cache::forget('posts_'.$type.'_page_2');
        }

        // Clear posts index page
        Cache::forget('posts_index');
    }
}
