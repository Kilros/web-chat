<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use PhpParser\Node\Expr\Cast;

class ChatController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $status = "Offline";
        if(Cache::has('user-is-online-' . $user->id)){
            $status = "Online";
        }
        
        // $sql0 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id={$_SESSION['unique_id']}");
        // $query = mysqli_query($conn, $sql0);
        // $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
        return view('chat.users',[
            'user' => $user,
            'status' => $status,
        ]);
    }
    public function userAction(Request $request)
    {
        if($request->has('action')){
            $action = $request->get('action');
            if($action == 'get_user'){
                $output = "";
                $listUser = User::where('id', '!=', Auth::user()->id)->orderBy('id', 'DESC')->get();
                if(!$listUser){
                    $output .= "No users are available to chat";
                }
                elseif($listUser){      
                    foreach ($listUser as $user) {
                        $message = Message::where(function($query) use ($user){
                            $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $user['id']);
                        })
                        ->orwhere(function($query) use ($user){
                            $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $user['id']);
                        })
                        ->orderBy('id', 'DESC')->first();
                        // $message = Message::where(function($query) use ($user){
                        //     $query->where('incoming_msg_id', $user['id'])-> orWhere('incoming_msg_id', Auth::user()->id);
                        // })
                        // ->where(function($query) use ($user){
                        //     $query->where('outgoing_msg_id', $user['id'])-> orWhere('outgoing_msg_id', Auth::user()->id);
                        // })
                        // ->orderBy('id', 'DESC')->first();
                        ($message) ? $result = $message['msg'] : $result ="Bắt đầu nhắn tin";
                        if($message && $message['style'] == 'image') {
                            $msg = 'Hình ảnh';
                        }
                        else if($message && $message['style'] == 'audio') {
                            $msg = 'Tập tin âm thanh';
                        }
                        else if($message && $message['style'] == 'video') {
                            $msg = 'Video';
                        }
                        else{
                            (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
                        }
                        if(isset($message['outgoing_msg_id'])){
                            (Auth::user()->id == $message['outgoing_msg_id']) ? $you = "Bạn: " : $you = "";
                        }else{
                            $you = "";
                        }
                        (Cache::has('user-is-online-' . $user->id)) ? $offline = "" : $offline = 'style = "background: #ccc"';
                        // (Auth::user()->id == $user['id']) ? $hid_me = "hide" : $hid_me = "";
                        // $output .= '<a href="/admin/chat/'. $user['id'] .'">
                        //     <div class="content">
                        //     <img src="'. "" .'" alt="">
                        //     <div class="details">
                        //         <span>'. $user['name'].'</span>
                        //         <p>'. $you . $msg .'</p>
                        //     </div>
                        //     </div>
                        //     <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                        // </a>';
                        //  $output .= '<a onclick="selectUser('.$user['id'] .')" href="#">
                        //         <div class="content">
                        //         <img src="'. "https://v5m6g2b4.rocketcdn.me/wp-content/uploads/images/wppipes/2020-12/3edae1dd0a.jpeg" .'" alt="">
                        //         <div class="details">
                        //             <span>'. $user['name'].'</span>
                        //             <p>'. $you . $msg .'</p>
                        //         </div>
                        //         </div>
                        //         <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                        //     </a>';
                        $output .= '
                        <a onclick="selectUser('.$user['id'] .')" href="#">
                        <div class="list-user-chat">
                            <div class="icons">
                                <img src="'.asset('assets/imageUser/'.$user['image']).'" alt="anh">
                                <span '.$offline.'></span>
                            </div>
                            <div class="info">
                                <div class="name">'. $user['name'].'</div>
                                <div class="msg">'. $you . $msg .'</div>
                            </div>
                        </div>
                        </a>';
                    }
                }
                return $output;
            }
        }
    }
    // public function chat($id)
    // {
    //     $user = User::find($id);
    //     $status = "Offline";
    //     if(Cache::has('user-is-online-' . $user->id)){
    //         $status = "Online";
    //     }
    //     return view('chat.chat',[
    //         'user' => $user,
    //         'status' => $status,
    //     ]);
    // }
    public function chatAction(Request $request)
    {
        // return $request->get('data');
        // $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
        // Cache::put('call', $request->get('data'), $expiresAt);
        // return Cache::get('call');
        if($request->has('action')){
            $action = $request->get('action');
            if($action == 'insert_chat' && $request->has('id')){
                $id = $request->get('id');
                $msg = Message::create([
                    'outgoing_msg_id' => Auth::user()->id,
                    'incoming_msg_id' => $id,
                    'style' => 'text',
                    'msg' => $request->get('msg'),
                ]);
                if($msg){
                    return 1;
                }

            }
            else if($request->has('file') && $action == 'insert_file' && $request->has('id')){
                $file = $request->file('file');
                $id = $request->get('id');
                $allowedfileExtension=['jpg','jpeg','png'];      
                $extension = $file->getClientOriginalExtension(); 
                $name = time().rand(1,100).'.'.$extension;  
                $check=in_array(strtolower($extension),$allowedfileExtension);
                if($check){
                    $size = $file->getSize(); 
                    if($size > 15728640){
                        return 'Hệ thống chỉ cho gửi ảnh không quá 15Mb!';               
                    }
                    $pathImg = public_path('assets/imageMsg');
                    $file->move($pathImg, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'image',
                        'msg' => $name,
                    ]);
                }
                else if(strtolower($extension) === 'mp4'){
                    $pathVideo = public_path('assets/videoMsg');
                    $file->move($pathVideo, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'video',
                        'msg' => $name,
                    ]);
                }    
                else if(strtolower($extension) === 'mp3'){
                    $pathVideo = public_path('assets/audioMsg');
                    $file->move($pathVideo, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'audio',
                        'msg' => $name,
                    ]);
                }                    
                return 1;
            }
            else if($action == 'get_chat'  && $request->has('id') && $request->has('load')){
                $id = $request->get('id');
                $load = $request->get('load');
                $output = "";
                $msgs = [];
                $user = User::find($id);
                $status = "Không hoạt động";
                if(Cache::has('user-is-online-' . $user->id)){
                    $status = "Đang hoạt động";
                }
                $count = Message::where(function($query) use ($id){
                    $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                })
                ->orwhere(function($query) use ($id){
                    $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                })->orderBy('id','desc')->count();
                if($request->has('count')){  
                    $rqcount = ($request->get('count'));
                    if($count > $rqcount){   
                        $limit = $count - $rqcount;
                        $msgs = Message::where(function($query) use ($id){
                            $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                        })
                        ->orwhere(function($query) use ($id){
                            $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                        })->orderBy('id','desc')->skip(0)->take($limit)->get();
                        
                    }          
                }else{
                    $msgs = Message::where(function($query) use ($id){
                        $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                    })
                    ->orwhere(function($query) use ($id){
                        $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                    })->orderBy('id','desc')->skip(0)->take((int)$load)->get();
                }
                if(count($msgs) > 0){
                    // return count($msgs);
                    // error_log($len);
                    for ($i = count($msgs)-1; $i >= 0; $i--) { 
                        if($msgs[$i]['style'] == 'image'){
                            $text = '<img style="border-radius: 0; cursor: pointer; width: 300px; right: 0" class="imgMsg" onclick="show('."'".asset('assets/imageMsg/'.$msgs[$i]['msg'])."'".')" src="'.asset('assets/imageMsg/'.$msgs[$i]['msg']).'" alt="'.$msgs[$i]['msg'].'">';
                        }
                        else if($msgs[$i]['style'] == 'video'){
                            $text = '<video src="'.asset('assets/videoMsg/'.$msgs[$i]['msg']).'" width="300" controls>
                            '.$msgs[$i]['msg'].'
                          </video>';
                        }
                        else if($msgs[$i]['style'] == 'audio'){
                           $text = '<audio controls>
                                <source src="'.asset('assets/audioMsg/'.$msgs[$i]['msg']).'">
                            </audio>';
                        }
                        else{
                            $text = '<p>'. $msgs[$i]['msg'] .'</p>';
                        }
                        if($msgs[$i]['outgoing_msg_id'] === Auth::user()->id){
                            $output .= '<div class="chat outgoing">
                                        <div class="details">
                                            '.$text.'
                                        </div>
                                        </div>';
                        }else{
                            $output .= '<div class="chat incoming">
                                        <img class="incomingImg" src="'.asset('assets/imageUser/'.$user['image']).'" alt="">
                                        <div class="details">
                                            '.$text.'
                                        </div>
                                        </div>';
                        }
                    }
                }else{
                    // $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
                }
                $data = array(
                    'name' => $user['name'],
                    'image' => asset('assets/imageUser/'.$user['image']),
                    'status' => $status,
                    'msg' => $output,
                    'count' => $count,
                    'load' => count($msgs),
                );
                return $data;
            }
        }
        
    }
}
