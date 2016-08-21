<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yuansir\Toastr\Facades\Toastr;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index');
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Post::$rules['store']);

        if ($validator->fails()) {
            return redirect(route('admin.post.create'))
                ->withErrors($validator)
                ->withInput();
        }
        // DATES //
        $published = $request->published ? '1' : '0';
        $published_at = $request->published ? $request['published_at'] : Carbon::now();

        // IMAGE (RECEIVE LINK) //
        $explode = explode(url('/'), $request->url_main_image);
        $image_link = $explode[1];

        // INSERT INTO DB //
        $request->user()->posts()->create([
            'title' => $request->title,
            'type' => $request->type,
            'preview' => $request->preview,
            'content' => $request->content,
            'image' => $image_link,
            'published' => $published,
            'published_at' => $published_at
        ]);

        Toastr::success('Post created', $title = null, $options = []);
        return redirect(route('admin.posts'));
    }

    public function destroy(Post $post){
        $this->authorize('destroy', $post);
        $post->delete();
        return response()->json([], 200);
    }

    public function edit(Post $post)
    {
        $post = Post::findOrFail($post->id);

        return view('admin.posts.edit', compact('post'));
    }

    public function update(Post $post, Request $request)
    {
        $post = Post::findOrFail($post->id);

        $validator = Validator::make($request->all(), Post::$rules['store']);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // DATES //
        $published = $request->published ? '1' : '0';
        $published_at = $request->published ? $request['published_at'] : Carbon::now();

        // IMAGE (RECEIVE LINK) //
        $explode = explode(url('/'), $request->url_main_image);
        $image_link = $explode[1];

        // UPDATE INTO DB //
        $post->update([
            'title' => $request->title,
            'type' => $request->type,
            'preview' => $request->preview,
            'content' => $request->content,
            'image' => $image_link,
            'published' => $published,
            'published_at' => $published_at
        ]);

        Toastr::success('Post updated', $title = null, $options = []);
        return redirect(route('admin.posts'));
    }

}
