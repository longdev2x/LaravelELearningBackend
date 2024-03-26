<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function login(Request $request)
    {
        try {
            //Validated, make tạo 1 đối tượng Validator, key là tên biến, values là điều kiện ngăn cách bởi |
            $validateUser = Validator::make($request->all(), 
            [
                'avatar' => 'required',
                'type' => 'required',
                'open_id' => 'required',
                'name' => 'required',
                'email' => 'required',
                // 'password' => 'required|min:6'
            ]);

            // fails true nếu đối tượng Validator có lỗi k đúng điều kiện
            if($validateUser->fails()){
                return response()->json([
                    'status' => 401,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // validated() will have all user field values;
            //we can save in the database
           $validated = $validateUser->validated();

           $map= [];
           //emai, phone, facebook, google, app ( 1, 2,3,4..)
           $map['type'] = $validated['type'];
           $map['open_id'] = $validated['open_id'];  

           //return every column form User have type and open_id like 
           //empty means dose not exist
           //then save the user in the database for first time 
           $user = User::where($map)->first();

           //Whether user has already logged in or not
           if(empty($user->id)) { 
            // this certain user has never been in our database
            //our job is to assign the user in the database
                $validated['token'] = md5(uniqid().rand(10000, 99000));
                //user first time created
                $validated['created_at'] = Carbon::now();

                //encrip password
                //$validated['password'] = Hash::make($validated['password']);

                //save validated in the database, return the id of the row after saving
                $userId = User::insertGetId($validated);

                //Validator -> validated -> User's all infomation
                $userInfo = User::where('id', '=', $userId)->first();
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;
                //saving after create
                $userInfo->access_token = $accessToken;
                User::where('id', '=', $userId)->update(['access_token' => $accessToken]);

                return response()->json([
                    'code' => 200,
                    'msg' => 'User Created Successfully',
                    'data' => $userInfo
                ], 200);

           }

           //User previously has logged in
            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->accsess_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accessToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'User Logged In Successfully',
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}