<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;

class JWTController extends Controller
{
    public function __contruct()
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function index()
    {
        // $data = task::all();
         $data = User::latest()->get();
        //   $data = ["msg"=>"This is test mesg"];
        return response()->json($data);
        //return response(['tasks'=>TaskResource::collection($data)]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|min:8'
        ]);
        if($validator->fails())
        {
            $err = response()->json($validator->errors());
             return $err;
        }
        else
        {
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);
             $msg = response()->json([
                'message'=>'User Created Successfully',
                'user'=>$user
            ],201);
            return $msg;
        }
    }
    public function contact(Request $req){
        $validator = Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required'
        ]);
        if($validator->fails()){
            $err = response()->json($validator->errors());
            return $err;
        }
        else{
            $contact = Contact::create([
                'name'=>$req->name,
                'email'=>$req->email,
                'subject'=>$req->subject,
                'message'=>$req->message
            ]);
            $msg = response()->json([
                'message'=>'Message Submitted Successfully',
                'data' =>$contact
            ],201);
            return $msg;
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string|min:8'
        ]);
        if($validator->fails())
        {
            $err = response()->json($validator->errors());
            return $err;
        }
        else
        {
            if(!$token=auth()->attempt($validator->validated())){
                return response()->json(['error'=>'Unauthorized User'],401);
            }
            //return $this->respondWithToken($token);
            return response()->json(['error'=>0, 'access_token'=>$token, 'email'=>$request->email],200);
        }
    }
    public function logout(){
        auth()->logout();
        return response()->json(['message'=>'User logout Successfully']);
    }
    public function respondWithToken($token){
        return response()->json([
            'error' => 0,
            'message' => 'Logged in Successfully',
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60
        ]);
    }
    public function profile(){
        return response()->json(auth()->user());
    }
    public function refresh(){
        return $this->responseWithToken(auth()->refresh());
    }

}
