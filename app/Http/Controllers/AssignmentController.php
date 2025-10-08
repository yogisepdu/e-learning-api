<?php

namespace App\Http\Controllers;

use App\Mail\NewAssignmentMail;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AssignmentController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string',
            'description' => 'required|string',
            'deadline'    => 'required|date'
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        if($user->id !== $course->lecturer_id){
            return response()->json(['message' => 'Hanya dosen pembuat yang bisa membuat tugas'], 403);
        }

        $assignment = Assignment::create($request->only('course_id','title','description','deadline'));

        $students = $course->students;
        foreach($students as $student){
            Mail::to($student->email)->send(new NewAssignmentMail($assignment));
        }

        return response()->json([
            'message' => 'Tugas berhasil dibuat & email dikirim',
            'assignment' => $assignment
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'assignment_id'=>'required|exists:assignments,id',
            'file'=>'required|file|max:10240'
        ]);

        $user = Auth::user();
        if($user->role !== 'student'){
            return response()->json(['message'=>'Hanya mahasiswa yang bisa submit tugas'], 403);
        }

        $assignment = Assignment::findOrFail($request->assignment_id);
        $filePath = $request->file('file')->store('submissions');

        $submission = Submission::create([
            'assignment_id'=>$assignment->id,
            'student_id'=>$user->id,
            'file_path'=>$filePath
        ]);

        return response()->json(['message'=>'Tugas berhasil diunggah', 'submission'=>$submission]);
    }

    public function grade(Request $request, $id)
    {
        $request->validate(['score'=>'required|integer|min:0|max:100']);

        $submission = Submission::findOrFail($id);
        $user = Auth::user();
        if($user->id !== $submission->assignment->course->lecturer_id){
            return response()->json(['message'=>'Hanya dosen pembuat yang bisa memberi nilai'], 403);
        }

        $submission->update(['score'=>$request->score]);

        return response()->json(['message'=>'Nilai berhasil diberikan','submission'=>$submission]);
    }
}
