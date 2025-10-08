<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    //
     public function index()
    {
        $courses = Course::with('lecturer', 'students')->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        if($user->role !== 'lecturer'){
            return response()->json(['message'=>'Hanya dosen yang bisa menambahkan mata kuliah'], 403);
        }

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'lecturer_id' => $user->id
        ]);

        return response()->json(['message'=>'Mata kuliah berhasil dibuat', 'course'=>$course]);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        if($user->id !== $course->lecturer_id){
            return response()->json(['message'=>'Hanya dosen pembuat yang bisa mengedit'], 403);
        }

        $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string'
        ]);

        $course->update($request->only('name','description'));

        return response()->json(['message'=>'Mata kuliah berhasil diupdate', 'course'=>$course]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        if($user->id !== $course->lecturer_id){
            return response()->json(['message'=>'Hanya dosen pembuat yang bisa menghapus'], 403);
        }

        $course->delete();
        return response()->json(['message'=>'Mata kuliah berhasil dihapus']);
    }

    public function enroll($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        if($user->role !== 'student'){
            return response()->json(['message'=>'Hanya mahasiswa yang bisa mendaftar'], 403);
        }

        $course->students()->syncWithoutDetaching($user->id);

        return response()->json(['message'=>'Berhasil mendaftar mata kuliah', 'course'=>$course]);
    }
}
