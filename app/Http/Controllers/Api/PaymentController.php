<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    public function checkout(Request $request) {
        try {
        $courseId = $request->course_id;
        $user = $request->user();
        $token = $user->token;

        $searchCourse = Course::where('id', '=', $courseId)->first();

        if(empty($searchCourse)) {
            return response()->json([
                'code' => '200',
                'msg' => 'No course found',
                'data' => ""
            ], 200);
        }

        $orderMap = [];
        $orderMap['course_id'] = $courseId;
        $orderMap['user_token'] = $token;
        $orderMap['status'] = 1;

        $orderRes = Order::where($orderMap)->first();

        // if we fall in the below condition, it means we already have an order from the same User_token with the same course_id and status
        if(!empty($orderRes)) {
            return response()->json([
                'code' => '200',
                'msg' => 'The order already exist',
                'data' => ""
            ], 200);
        }

        $map = [];
        $map['user_token'] = $token;
        $map['course_id'] = $courseId;
        //Status equal 1 means successful order
        $map['status'] = 0;
        $map['total_amount'] = $searchCourse->price;
        $map['created_at'] = Carbon::now();

        $orderNumb = Order::insertGetId($map);
 
       // $checkoutSession = Session::create([
    //     'user_token' => $token,
    //       'course_id' => $courseId
        // ]);
        

        return response()->json([
            'code' => 200,
            'msg' => 'link to webview',
            'data' => 'payment-mobile'
        ], 200);
        
        } catch (\Throwable $ex) {
            return response()->json([
                'status' => false,
                'msg' => $ex->getMessage()
            ],500);
        }
    }
}
