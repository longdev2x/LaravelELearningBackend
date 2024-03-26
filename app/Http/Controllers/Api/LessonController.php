<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function lessonList(Request $request)
    {
        $id = $request->id;
        $result = Lesson::where("course_id", $id)->get();

        try {
            return response()->json([
                'code' => 200,
                'msg' => 'My Lesson List here',
                'data' => $result
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function lessonDetail(Request $request) {
        $id = $request->id;

        try  {
            $result = Lesson::where('id', $id)->select('video')->first();
            return response()->json(
                [
                    'code' => 200,
                    'msg' => 'Lesson Detail Here',
                    'data' => $result,
                ], 200
            );
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal error',
                'data' => $e->getMessage(),
            ], 500);
        }
        
    }
}