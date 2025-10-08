<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'course_id'=>'required|exists:courses,id',
            'content'=>'required|string'
        ]);

        $user = Auth::user();

        $discussion = Discussion::create([
            'course_id'=>$request->course_id,
            'user_id'=>$user->id,
            'content'=>$request->content
        ]);

        return response()->json(['message'=>'Diskusi berhasil dibuat','discussion'=>$discussion]);
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['content'=>'required|string']);
        $user = Auth::user();
        $discussion = Discussion::findOrFail($id);

        $reply = Reply::create([
            'discussion_id'=>$discussion->id,
            'user_id'=>$user->id,
            'content'=>$request->content
        ]);

        return response()->json(['message'=>'Balasan berhasil dibuat','reply'=>$reply]);
    }
}
