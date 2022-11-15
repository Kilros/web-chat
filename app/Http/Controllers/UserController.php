<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return redirect('admin');
        }
        $remember = json_decode(request()->cookie('info'), True);
        return view("user.login",[
            'Remember' => $remember,
        ]);
    }  
      
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        $remember = $request->get('remember');
        $credentials = $request->only('email', 'password'); 
        if (Auth::attempt($credentials, $remember)) {
            if($remember){
                $minutes = 30*24*60;
                $data = [
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                ];
                Cookie::queue('info', json_encode($data), $minutes);
            }
            else{
                Cookie::queue(Cookie::forget('info'));
            }
            return redirect()->intended('admin')
                        ->withSuccess('Đăng nhập thành công');
        }
  
        return redirect("login")->with('error', 'Tài khoản không chính xác!');
    }

    public function registration()
    {
        return view('user.register');
    }
      
    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:6',             // must be at least 10 characters in length
                // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                // 'regex:/[0-9]/',      // must contain at least one digit
                // 'regex:/[@$!%*#?&.]/', // must contain a special character
            ],
            'image' => 'required',
        ]);    
        $file = $request->file('image');
        $allowedfileExtension=['jpg','jpeg','png','gif'];      
        $extension = $file->extension(); 
        $name = time().rand(1,100).'.'.$extension;  
        $check=in_array(strtolower($extension),$allowedfileExtension);
        if($check){
            $pathImg = public_path('assets/imageUser');
            $file->move($pathImg, $name);       
            User::create([
                'name' => $request->get('name'),
                // 'name' => uniqid(),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'),),
                'image' => $name,
            ]);
            return redirect("login")->withSuccess('You have signed-in');
        }
        return redirect('register')->with('err', 'Ảnh không đúng định dạng!');
    }
    
    public function signOut() {
        Cache::forget('user-is-online-' . Auth::user()->id);
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function userOnlineStatus()
    {
        $users = User::all();
        return view('user.status',[
            'users' => $users,
        ]);
    }

}
