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
use Illuminate\Support\Facades\File;

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
            $pathImg = resource_path('appchat/image');
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
    public function changePassword(Request $request) {
        $request->validate([
            'passwordOld' => [
                'required',
                'string',
                'min:6',
            ],
            'passwordNew' => [
                'required',
                'string',
                'min:6',
            ],
            'passwordVerify' => [
                'required',
                'string',
                'min:6',
            ],
        ]); 
        $user = User::where('id', Auth::user()->id)->first();
        if(!Hash::check($request->get('passwordOld'), $user->password)){
            return Redirect('admin')->with('message', 'Mật khẩu cũ không chính xác!');
        }
        if($request->get('passwordNew') != $request->get('passwordVerify')){
            return Redirect('admin')->with('message', 'Mật khẩu xác thực không giống mật khẩu mới!');
        }
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->get('passwordNew'),),
        ]);
        return Redirect('admin')->with('message', 'Đổi mật khẩu thành công');
    }
    public function changeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]); 
        if($request->has('photo')){
            $file = $request->file('photo');
            $allowedfileExtension=['jpg','jpeg','png','gif'];      
            $extension = $file->extension(); 
            $name = time().rand(1,100).'.'.$extension;  
            $check=in_array(strtolower($extension),$allowedfileExtension);
            if($check){
                $pathImg = resource_path('appchat/image');
                $file->move($pathImg, $name);
                $user = User::find(Auth::user()->id);
                $PathImgDel = resource_path("appchat/image/").$user['image'];
                if(File::exists($PathImgDel)) {
                    File::delete($PathImgDel);
                }
                User::where('id', Auth::user()->id)->update([
                    'name' => $request->get('name'),
                    'image' => $name,
                ]);
                return Redirect('admin')->with('message', 'Cập nhật hồ sơ thành công');
            }
        }
        else{
            User::where('id', Auth::user()->id)->update([
                'name' => $request->get('name')
            ]);
            return Redirect('admin')->with('message', 'Cập nhật hồ sơ thành công');
        }
    }

    public function userOnlineStatus()
    {
        $users = User::all();
        return view('user.status',[
            'users' => $users,
        ]);
    }

}
