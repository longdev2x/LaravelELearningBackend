<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class PaymentController extends Controller
{
    public function checkout(Request $request) {
        try {
        $courseId = $request->id;
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

        $orderNumber = Order::insertGetId($map);

        return response()->json([
            'code' => '200',
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
    public function payment(Request $request)
    {
        //Check if the incoming HTTP request contains a parameter named 'callback' -> update 
        if ($request->has('callback')) {
            Order::where(['id' => $request->order_id])->update(['callback' => $request['callback']]);
        }
        session()->put('customer_id', $request['customer_id']);
        session()->put('order_id', $request->order_id);
        
        $customer = User::find($request['customer_id']);
        return view('payment-view');
        $order = Order::where(['id' => $request->order_id, 'user_id' => $request['customer_id']])->first();
        // dd(session()->all(), $order, $customer->email);
        if (isset($customer) && isset($order)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);
            return view('payment-view');
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }

    public function success()
    {
        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        /*if ($order->callback != null) {
            return redirect($order->callback . '&status=success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200); */
         return redirect('&status=success');
    }

    public function fail()
    {
        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        /*if ($order->callback != null) {
            return redirect($order->callback . '&status=fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);*/
         return redirect('&status=success');
    }
}
