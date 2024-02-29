<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    //return all the course list
    public function courseList(){

        //select the fields
        $result = Course::select('name', 'thumbnail', 'lesson_num', 'id')->get();
        // //Different way is 
        // $result = Course::get(['name', 'thumbnail', 'lesson_num', 'id']);
        // $result = Course::get(); //Get All Fields

        return response()->json([
            'code' => 200,
            'msg' => 'Course List',
            'data' => $result
        ], 200);
    }
}
