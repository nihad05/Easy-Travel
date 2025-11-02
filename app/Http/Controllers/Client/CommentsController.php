<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function placeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required'],
        ]);
        $entityType = 'place';
        $userId = auth()->id();
        $body = $request->comment;
        $insert = Comment::create([
            'entity_type' => $entityType,
            'entity_id' => $id,
            'user_id' => $userId,
            'body' => $body,
        ]);
        if ($insert) {
            return back()->with('success', 'Comment added successfully!');
        }
    }

    public function propertyComment(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required'],
        ]);
        $entityType = 'property';
        $userId = auth()->id();
        $body = $request->comment;
        $insert = Comment::create([
            'entity_type' => $entityType,
            'entity_id' => $id,
            'user_id' => $userId,
            'body' => $body,
        ]);
        if ($insert) {
            return back()->with('success', 'Comment added successfully!');
        }
    }

    public function guideComment(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required'],
        ]);
        $entityType = 'guide';
        $userId = auth()->id();
        $body = $request->comment;
        $insert = Comment::create([
            'entity_type' => $entityType,
            'entity_id' => $id,
            'user_id' => $userId,
            'body' => $body,
        ]);
        if ($insert) {
            return back()->with('success', 'Comment added successfully!');
        }
    }

    public function blogComment(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required'],
        ]);
        $entityType = 'blog';
        $userId = auth()->id();
        $body = $request->comment;
        $insert = Comment::create([
            'entity_type' => $entityType,
            'entity_id' => $id,
            'user_id' => $userId,
            'body' => $body,
        ]);
        if ($insert) {
            return back()->with('success', 'Comment added successfully!');
        }
    }
}
