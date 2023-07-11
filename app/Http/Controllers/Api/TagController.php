<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $tags = Tag::where('name', 'LIKE', "%$q%")->withCount('posts')->orderByDesc('posts_count')->get();

        return TagResource::collection($tags);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Tag $tag)
    {
        $posts = $tag->posts()->with('user:id,nickname')->latest()->paginate($request->get('per_page', 25));
        
        return (PostResource::collection($posts))->additional(['tag' => new TagResource($tag),]);
    }
}
