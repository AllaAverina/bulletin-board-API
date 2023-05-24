<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\CommentResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $users = User::where('name', 'LIKE', "%$q%")
            ->orWhere('nickname', 'LIKE', "%$q%")
            ->withCount('posts', 'comments')
            ->orderBy('name')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user, string $part = '')
    {
        if ($part == 'posts') {
            $posts = $user->posts()
                ->with('tags', 'user:id,nickname')
                ->withCount('comments')
                ->latest()
                ->paginate($request->get('per_page', 25));
            return (PostResource::collection($posts))->additional([
                'user' => new UserResource($user),
            ]);
        }

        if ($part == 'comments') {
            $comments = $user->comments()
                ->with('user:id,nickname')
                ->latest()
                ->paginate($request->get('per_page', 25));
            return (CommentResource::collection($comments))->additional([
                'user' => new UserResource($user),
            ]);
        }

        return new UserResource($user->loadCount('posts', 'comments'));
    }
}
