<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::all();

        $postIds = $posts->map(function ($post) {
            return $post->id;
        })->toArray();
        $author = auth()->user()->getAuthIdentifier();

        $likes = DB::table('post_ratings')->whereIn('post_id', $postIds)
            ->where('rating', 1)
            ->groupBy('post_id')->selectRaw('post_id, SUM(rating) AS likes')->get();

        $dislikes = DB::table('post_ratings')->whereIn('post_id', $postIds)
            ->where('rating', -1)
            ->groupBy('post_id')->selectRaw('post_id, -SUM(rating) AS dislikes')->get();

        $posts->each(function ($post) use ($likes, $dislikes) {
            $post->likes = 0;
            $post->dislikes = 0;
            $likes->each(function ($row) use ($post) {
                if ($row->post_id == $post->id) {
                    $post->likes = $row->likes;
                }
            });
            $dislikes->each(function ($row) use ($post) {
                if ($row->post_id == $post->id) {
                    $post->dislikes = $row->dislikes;
                }
            });
        });

        return view('posts.index', compact('posts', 'author'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $author = auth()->user()->getAuthIdentifier();
        return view("posts.create", compact('author'));
    }

    public function store()
    {
        $author = auth()->user()->getAuthIdentifier();
        if (request('author') != $author) {
            throw new ValidationException("You haven't rights to change post author.");
        }
        $this->validate(request(), [
            'title' => 'required|min:2',
            'intro' => 'required',
            'body' => 'required',
            'author' => 'required'
        ]);

        Post::create(
            request(['title', 'intro', 'body', 'author'])
        );

        return redirect('/');
    }

    public function edit(Post $post)
    {
        return view("posts.edit", compact('post'));
    }

    public function update(Post $post)
    {
        $this->validate(request(), [
            'title' => 'required|min:2',
            'intro' => 'required',
            'body' => 'required',
        ]);
        $post->update(request(['title', 'intro', 'body']));
        return redirect('/');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/');
    }

}
