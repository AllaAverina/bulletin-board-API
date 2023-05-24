<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get user information.
     */
    public function profile(Request $request, string $part = '')
    {
        if ($part == 'posts') {
            $posts = $request->user()->posts()
                ->with('tags', 'user:id,nickname')
                ->withCount('comments')
                ->latest()
                ->paginate($request->get('per_page', 25));
            return (PostResource::collection($posts))->additional([
                'user' => new UserResource($request->user()),
            ]);
        }

        if ($part == 'comments') {
            $comments = $request->user()->comments()
                ->with('user:id,nickname')
                ->latest()
                ->paginate($request->get('per_page', 25));
            return (CommentResource::collection($comments))->additional([
                'user' => new UserResource($request->user()),
            ]);
        }

        return new UserResource($request->user()->loadCount('posts', 'comments'));
    }
}
