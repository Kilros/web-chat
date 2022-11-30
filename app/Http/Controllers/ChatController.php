<?php

namespace App\Http\Controllers;

use App\Models\ListChat;
use App\Models\Message;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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
                // $youself = User::where('id', Auth::user()->id)->first();
                $lists = ListChat::where('outgoing_id', Auth::user()->id)->orderBy('updated_at', 'DESC')->get();
                if($lists){
                    $listId = array(Auth::user()->id);
                    foreach ($lists as $list) {
                        array_push($listId, $list['incoming_id']);
                    }
                    // $listUser = User::whereIn('id', $listId)->get();  
                    foreach ($listId as $incoming_id) {
                        $message = Message::where(function($query) use ($incoming_id){
                            $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $incoming_id);
                        })
                        ->orwhere(function($query) use ($incoming_id){
                            $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $incoming_id);
                        })
                        ->orderBy('id', 'DESC')->first();
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
                        (Cache::has('user-is-online-' . $incoming_id)) ? $offline = "" : $offline = 'style = "background: #ccc"';
                        $user = User::where('id', $incoming_id)->first();
                        $output .= '
                        <div onclick="selectUser('.$incoming_id .')" class="list-user-chat">
                            <div class="icons">
                                <img src="'.(($user['id']==Auth::user()->id) ? asset('assets/cloud.png')  : asset('appchat/file/'.$user['image'])).'" alt="anh">
                                <span '.$offline.'></span>
                            </div>
                            <div class="info">
                                <div class="name">'.(($user['id']==Auth::user()->id) ? 'Cloud'  : $user['name']).'</div>
                                <div class="msg">'. (($user['id']==Auth::user()->id) ? ''  : $you) . $msg .'</div>
                            </div>
                        </div>';
                    }
                }
                $listUser = User::whereNotIn('id', $listId)->orderBy('id', 'DESC')->get();
                // if(count($listUser) == 0 && count($lists) == 0){
                //     $output .= '<p style="color: #fff; margin-left: 20px">Không có người sẳn sàng để nhắn tin</p>';
                // }
                if($listUser){      
                    foreach ($listUser as $user) {
                        (Cache::has('user-is-online-' . $user->id)) ? $offline = "" : $offline = 'style = "background: #ccc"';
                        $output .= '
                        <div onclick="selectUser('.$user['id'] .')" class="list-user-chat">
                            <div class="icons">
                                <img src="'.asset('appchat/file/'.$user['image']).'" alt="anh">
                                <span '.$offline.'></span>
                            </div>
                            <div class="info">
                                <div class="name">'. $user['name'].'</div>
                                <div class="msg">Bắt đầu nhắn tin</div>
                            </div>
                        </div>';
                    }
                }
                return $output;
            }
        }
    }
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
                if(Auth::user()->id != $id){  
                    $check = ListChat::where('outgoing_id', Auth::user()->id)->where('incoming_id', $id)->first();
                    if($check){
                        ListChat::where('outgoing_id', Auth::user()->id)->where('incoming_id', $id)->update([
                            'outgoing_id' => Auth::user()->id
                        ]);
                    } 
                    else{
                        ListChat::create([
                            'outgoing_id' => Auth::user()->id,
                            'incoming_id' => $id,
                        ]);
                    }
                    $check1= ListChat::where('incoming_id', Auth::user()->id)->where('outgoing_id', $id)->first();
                    if($check1){
                        ListChat::where('incoming_id', Auth::user()->id)->where('outgoing_id', $id)->update([
                            'incoming_id' => Auth::user()->id
                        ]);
                    } 
                    else{
                        ListChat::create([
                            'incoming_id' => Auth::user()->id,
                            'outgoing_id' => $id,
                        ]);
                    }
                }
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
                    $pathImg = resource_path('appchat/image');
                    $file->move($pathImg, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'image',
                        'msg' => $name,
                    ]);
                }
                else if(strtolower($extension) === 'mp4'){
                    $pathVideo = resource_path('appchat/video');
                    $file->move($pathVideo, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'video',
                        'msg' => $name,
                    ]);
                }    
                else if(strtolower($extension) === 'mp3'){
                    $pathVideo = resource_path('appchat/audio');
                    $file->move($pathVideo, $name);       
                    $msg = Message::create([
                        'outgoing_msg_id' => Auth::user()->id,
                        'incoming_msg_id' => $id,
                        'style' => 'audio',
                        'msg' => $name,
                    ]);
                } 
                if(Auth::user()->id != $id){  
                    $check = ListChat::where('outgoing_id', Auth::user()->id)->where('incoming_id', $id)->first();
                    if($check){
                        ListChat::where('outgoing_id', Auth::user()->id)->where('incoming_id', $id)->update([
                            'outgoing_id' => Auth::user()->id
                        ]);
                    } 
                    else{
                        ListChat::create([
                            'outgoing_id' => Auth::user()->id,
                            'incoming_id' => $id,
                        ]);
                    }
                    $check1= ListChat::where('incoming_id', Auth::user()->id)->where('outgoing_id', $id)->first();
                    if($check1){
                        ListChat::where('incoming_id', Auth::user()->id)->where('outgoing_id', $id)->update([
                            'incoming_id' => Auth::user()->id
                        ]);
                    } 
                    else{
                        ListChat::create([
                            'incoming_id' => Auth::user()->id,
                            'outgoing_id' => $id,
                        ]);
                    }  
                }               
                return 1;
            }
            else if($action == 'get_chat'  && $request->has('actionDetail')){
                $request->validate([
                    'id' => 'required',
                ]);
                $output = "";
                $msgs = [];
                $id = $request->get('id');
                $user = User::find($id);
                $count = Message::where(function($query) use ($id){
                    $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                })
                ->orwhere(function($query) use ($id){
                    $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                })->orderBy('id','desc')->count();
                $actionDetail = $request->get('actionDetail');
                if($actionDetail == 'select'){
                    $msgs = Message::where(function($query) use ($id){
                        $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                    })
                    ->orwhere(function($query) use ($id){
                        $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                    })->orderBy('id','desc')->skip(0)->take(15)->get();
                    $data = $this->formatMsg($user, $msgs, $output, $count, count($msgs));
                }    
                else if($actionDetail == 'single'){
                    $request->validate([
                        'load' => 'required',
                        'count' => 'required',
                    ]);
                    $load = $request->get('load');
                    $rqCount = ($request->get('count'));
                    $limit = $count - (int)$rqCount;
                    if($limit > 0){
                        if($limit>15){
                            $limit = 15;
                        }
                        $msgs = Message::where(function($query) use ($id){
                            $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                        })
                        ->orwhere(function($query) use ($id){
                            $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                        })->orderBy('id','desc')->skip(0)->take($limit)->get();  
                    }
                    $data = $this->formatMsg($user, $msgs, $output, $count, (int)$load + count($msgs));
                }
                else if($actionDetail == 'load'){
                    $request->validate([
                        'load' => 'required',
                    ]);
                    $load = $request->get('load');
                    // $rqCount = ($request->get('count'));
                    if((int)$load < (int)$count){
                        $msgs = Message::where(function($query) use ($id){
                            $query->where('incoming_msg_id', Auth::user()->id)->Where('outgoing_msg_id', $id);
                        })
                        ->orwhere(function($query) use ($id){
                            $query->where('outgoing_msg_id', Auth::user()->id)->Where('incoming_msg_id', $id);
                        })->orderBy('id','desc')->skip((int)$load)->take(10)->get(); 
                    }
                    $data = $this->formatMsg($user, $msgs, $output, $count, (int)$load + count($msgs));
                }
                return $data;
            }
        }
        
    }
    public function formatMsg($user, $msgs, $output, $count, $load)
    {
        if(count($msgs) > 0){
            for ($i = count($msgs)-1; $i >= 0; $i--) { 
                if($msgs[$i]['style'] == 'image'){
                    $text = '<img style="border-radius: 0; cursor: pointer; width: 300px; right: 0" class="imgMsg" onclick="show('."'".asset('appchat/file/'.$msgs[$i]['msg'])."'".')" src="'.asset('appchat/file/'.$msgs[$i]['msg']).'" alt="'.$msgs[$i]['msg'].'">';
                }
                else if($msgs[$i]['style'] == 'video'){
                    $text = '<video width="300" controls>
                        <source src="'.asset('appchat/file/'.$msgs[$i]['msg']).'">
                  </video>';
                }
                else if($msgs[$i]['style'] == 'audio'){
                   $text = '<audio controls>
                        <source src="'.asset('appchat/file/'.$msgs[$i]['msg']).'">
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
                                <img class="incomingImg" src="'.asset('appchat/file/'.$user['image']).'" alt="">
                                <div class="details">
                                    '.$text.'
                                </div>
                                </div>';
                }
            }
        }else{
            // $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        $status = "Không hoạt động";
        if(Cache::has('user-is-online-' . $user->id)){
            $status = "Đang hoạt động";
        }
        $data = array(
            'name' => ($user['id'] == Auth::user()->id) ? 'Cloud' :$user['name'],
            'image' => ($user['id']==Auth::user()->id) ? asset('assets/cloud.png')  : asset('appchat/file/'.$user['image']),
            'status' => $status,
            'msg' => $output,
            'count' => $count,
            'load' => $load,
        );
        return $data;
    }
    // public function sent(Request $request)
    // {
    //     if ($request->has('data')) {
    //         Test::truncate();
    //         Test::insert([
    //             'msg' => $request->get('data'),
    //         ]);
    //         return "true";
    //     }
    //     return "false";
    // }
    // public function get(Request $request)
    // {
    //     return Test::first();
    // }
}
