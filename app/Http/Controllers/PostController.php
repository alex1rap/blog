<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostRating;
use \InvalidArgumentException;
use Illuminate\Support\Facades\DB;

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
        $userId = auth()->user()->getAuthIdentifier();

        $vote = DB::table('post_ratings')->where([
            'post_id' => $post->id,
            'author' => $userId
        ])->first();

        $likes = DB::table('post_ratings')->where('post_id', $post->id)
            ->where('rating', 1)
            ->groupBy('post_id')->selectRaw('post_id, SUM(rating) AS likes')->first();

        $dislikes = DB::table('post_ratings')->where('post_id', $post->id)
            ->where('rating', -1)
            ->groupBy('post_id')->selectRaw('post_id, -SUM(rating) AS dislikes')->first();

        $post->likes = ($likes) ? $likes->likes : 0;
        $post->dislikes = ($dislikes) ? $dislikes->dislikes : 0;
        $post->canLike = empty($vote) || $vote->rating == -1;
        $post->canDislike = empty($vote) || $vote->rating == 1;

        return view('posts.show', compact('post', 'userId'));
    }

    public function create()
    {
        $author = auth()->user()->getAuthIdentifier();
        return view("posts.create", compact('author'));
    }

    public function store()
    {
        $userId = auth()->user()->getAuthIdentifier();
        if (request('author') != $userId) {
            throw new InvalidArgumentException("You haven't rights to change post author.");
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

    public function like(Post $post)
    {
        $userId = auth()->user()->getAuthIdentifier();
        $rating = DB::table('post_ratings')->where([
            'post_id' => $post->id,
            'author' => $userId
        ])->first();
        if (empty($rating)) {
            PostRating::create([
                'post_id' => $post->id,
                'author' => $userId,
                'rating' => 1
            ]);
        } else {
            DB::table('post_ratings')->where([
                'post_id' => $post->id,
                'author' => $userId
            ])->update(['rating' => 1]);
        }
        return redirect("/posts/{$post->id}");
    }

    public function dislike(Post $post)
    {
        $userId = auth()->user()->getAuthIdentifier();
        $rating = DB::table('post_ratings')->where([
            'post_id' => $post->id,
            'author' => $userId
        ])->first();
        if (empty($rating)) {
            PostRating::create([
                'post_id' => $post->id,
                'author' => $userId,
                'rating' => -1
            ]);
        } else {
            DB::table('post_ratings')->where([
                'post_id' => $post->id,
                'author' => $userId
            ])->update(['rating' => -1]);
        }
        return redirect("/posts/{$post->id}");
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/');
    }

}
