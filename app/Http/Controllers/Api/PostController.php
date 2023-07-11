<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchPostRequest;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SearchPostRequest $request)
    {
        $q = $request->get('q', '');
        $posts = Post::where('title', 'LIKE', "%$q%")
            ->whereHas('tags', function ($query) use ($request) {
                if ($request->has('tags')) {
                    $query->whereIn('id', $request->tags);
                }
            })
            ->with('tags', 'user:id,nickname')
            ->withCount('comments')
            ->orderBy($request->get('sort', 'created_at'), $request->get('order', 'asc'))
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = $request->user()->posts()->create($request->only('title', 'price', 'description'));
        $post->tags()->attach($request->tags);

        return new PostResource($post->load('tags','user:id,nickname'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        $post->load('tags', 'user:id,nickname');
        $comments = $post->comments()->with('user:id,nickname')->latest()->paginate($request->get('per_page', 25));
        
        return (CommentResource::collection($comments))->additional(['post' => new PostResource($post),]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->only('title', 'price', 'description'));

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }
        
        return new PostResource($post->load('tags', 'user:id,nickname'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
