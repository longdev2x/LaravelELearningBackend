<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
   public function payment(Request $request) {
    $userId = $request->user_id;
    $courseId = $request->course_id;

    $customer = User::find($userId);
    $token = $customer->token;

    $order = Order::where(['user_token' => $token, 'course_id' => $courseId])->first();

    session()->put('user_token', $token);
    session()->put('id', $order->id);

    if (isset($customer) && isset($order)) {
        $data = [
            'name' => $customer['name'],
            'email' => $customer['email'],
        ];

        session()->put('data', $data);
        return view('payment-view');
    }

    return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
   }


   public function success()
    {
        $order = Order::where(['id' => session('id'), 'user_token'=>session('user_token')])->first();
        /*if ($order->callback != null) {
            return redirect($order->callback . '&status=success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200); */
        return view('success');
    }

    public function fail()
    {
        $order = Order::where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        /*if ($order->callback != null) {
            return redirect($order->callback . '&status=fail');
        }
        return response()->json(['message' => 'Payment failed'], 403);*/
         return view('fail');
    }
}
