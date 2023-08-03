<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllPostsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(fn ($post) => [
            'id' => $post->id,
            'text' => $post->text,
            'video' => url('/') . $post->video,
            'created_at' => $post->created_at->format(' M D Y'),
            'comments' => $post->comments->map(fn ($comment) => [
                'id' => $comment->id,
                'text' => $comment->text,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'image' => url('/') . $comment->user->image,
                ],
            ]),
            'likes' => $post->likes->map(fn ($like) => [
                'id' => $like->id,
                'user_id' => $like->user_id,
                'post_id' => $like->post_id,
            ]),
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
                'image' => url('/') . $post->user->image,
            ],
        ]);
    }
}
