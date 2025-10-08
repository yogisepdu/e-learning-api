<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'course_id'=>'required|exists:courses,id',
            'title'=>'required|string',
            'file'=>'required|file|max:10240' // max 10MB
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);
        if($user->id !== $course->lecturer_id){
            return response()->json(['message'=>'Hanya dosen pembuat yang bisa upload materi'], 403);
        }

        $filePath = $request->file('file')->store('materials');

        $material = Material::create([
            'course_id'=>$course->id,
            'title'=>$request->title,
            'file_path'=>$filePath
        ]);

        return response()->json(['message'=>'Materi berhasil diupload', 'material'=>$material]);
    }

    public function download($id)
    {
        $material = Material::findOrFail($id);
        if(!Storage::exists($material->file_path)){
            return response()->json(['message'=>'File tidak ditemukan'], 404);
        }

        return response()->download(storage_path('app/private/'.$material->file_path), $material->title);
    }
}
