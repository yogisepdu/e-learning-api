<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function courses()
    {
        $data = Course::withCount('students')->get();
        return response()->json($data);
    }

    public function assignments()
    {
        $data = Assignment::withCount([
            'submissions as graded_count'=>function($q){
                $q->whereNotNull('score');
            },
            'submissions as ungraded_count'=>function($q){
                $q->whereNull('score');
            }
        ])->get();

        return response()->json($data);
    }

    public function student($id)
    {
        $student = User::findOrFail($id);
        $assignments = $student->assignmentsSubmitted()->with('assignment')->get();

        $average = $assignments->avg('score');

        return response()->json([
            'student'=>$student,
            'assignments'=>$assignments,
            'average_score'=>$average
        ]);
    }
}
