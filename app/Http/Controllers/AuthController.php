<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        if($validated->fails()){
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        }else{
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                "username" => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'note' => $request->note,
                'api_token' => Str::random(60),

            );
            $userSave = User::create($data);
            if($userSave){
                $status = 'Success';
                $message = 'Data Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $userSave], 201);
            }else{
                $status = 'Failed';
                $message = 'Data Not Saved';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email',$request->emailusername)->orWhere('username', $request->emailusername)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $status = 'Success';
                $message = 'Success Get User';
                $data = $user;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
            }else{
                $status = 'Failed';
                $message = 'Wrong Password';
                $data = null;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
            }
        } else{
            $status = 'Failed';
                $message = 'User Doesnt Exist';
                $data = null;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }
    }
    public function show($id)
    {
        $peserta = User::where('id', $id)->get();
        if($peserta){
            $status = 'Success';
            $message = 'Success Get Detail Peserta';
            $data = $peserta;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }else{
            $status = 'Failed';
            $message = 'Failed Get Detail Peserta';
            $data = null;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit(User $User)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'is_person' => 'required',
            'service_money' => 'required',
            'service_zakat' => 'required',
            'service_qurban' => 'required',
        ]);

        if($validated->fails()){
            $status = 'Failed';
            $message = 'Validation Error';
            $data = $validated->messages();
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
        }else{
            $data = array(
                'name' => $request->name,
                'is_person' => $request->is_person,
                'service_money' => $request->service_money,
                'service_zakat' => $request->service_zakat,
                'service_qurban' => $request->service_qurban,
                'url_qrcode' => $request->url_qrcode,
                'note' => $request->note
            );
            $userUpdate = User::where('id', $id)->update($data);
            if($userUpdate){
                $status = 'Success';
                $message = 'Data Updated';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
            }else{
                $status = 'Failed';
                $message = 'Data Not Updated';
                $data = $data;
                return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesertaDelete = User::find($id)->delete();

        if($pesertaDelete){
            $status = 'Success';
            $message = 'Data Deleted';
            $data = $pesertaDelete;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200);
        }else{
            $status = 'Failed';
            $message = 'Data Not Deleted';
            $data = $pesertaDelete;
            return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 500);
    }
}
}
