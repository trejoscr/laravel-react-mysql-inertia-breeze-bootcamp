<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			return Inertia::render('Posts/Index', [
        'posts' => Post::with('user:id,name')->latest()->get()
			]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // validate data
			$validated = $request->validate([
				'title' => 'required|string|max:100',
				'body' => 'required|string|max:255',
			]);

			$request->user()->posts()->create($validated);
    
			return redirect(route('posts.index'));
		}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
      $this->authorize('update',  $post);
      // validate data
			$validated = $request->validate([
				'title' => 'required|string|max:100',
				'body' => 'required|string|max:255',
			]);

      $post->update($validated);

      return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
      $this->authorize('delete',  $post);
      $post->delete();
      return redirect(route('posts.index'));
    }
}
