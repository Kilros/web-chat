@extends('layouts.home')
@section('title', 'Trang chủ')
@section('nav')
    <div class="wrapper">
      {{-- <p id="idChat">{{$user['id']}}</p> --}}
      <section  style="position: relative; height: 100%" class="users">
        <div id="headerTitle">
          <div class="row" class="content">
              <div class="col-sm-3">
                <a onclick="selectUser({{$user['id']}})" href="#"><img style="float: left;" src="{{asset('assets/imageUser/'.$user['image'])}}" alt=""></a>
              </div>
              <div class="col-sm-6" style="display: flex; align-items: center" class="details">
                <span>{{$user['name']}}</span>
                {{-- <p>{{$status}}</p> --}}
              </div>
              <div class="col-sm-3" style="display: flex; align-items: center">
                <a href="/logout" id="logout">Đăng xuất</a>  
              </div>   
          </div>          
        </div>
        <div class="search">
          <span class="text">Chọn người để trò chuyện</span>
          <input type="text" placeholder="Tìm kiếm...">
          <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">
    
        </div>
      </section>
    </div>
    {{-- <div id="footer">Footer</div> --}}
    <script src="{{ asset('assets/chat/js/users.js'); }}"></script>
@endsection
@section('chat')
    <div class="wrapper">
        <section style="position: relative; height: 100%" class="chat-area">
            <header>
                <a id="back" href="#" onclick="back()" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img id="imageChat" src="{{asset('assets/imageUser/'.$user['image'])}}" alt="">
                <div class="details">
                    <span id="nameUserChat">{{$user['name']}}</span>
                    <p id="statusUserChat">{{$status}}</p>
                    <p style="display: none;" id="idChat">{{$user['id']}}</p>
                    <p style="display: none;" id="numberLine">0</p>
                    <p  style="display: none;" id="count">0</p>
                </div>
            </header>
            <div class="chat-box">

            </div>
            
            <form action="#" class="typing-area">
                <div>
                    <label for="image_uploads">File</label>
                    <input
                    type="file"
                    id="image_uploads"
                    name="image_uploads"
                    accept=".jpg, .jpeg, .png, .mp4, .mp3"
                    multiple />
                </div>
                @csrf
                <input type="text" class="incoming_id" name="incoming_id" value="{{Auth::user()->id}}" hidden>
                <input type="text" name="message" id="msg" class="input-field" placeholder="Nhập tin nhắn ở đây..." autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
            <button id="trigger">😎</button>
        </section>
    </div>
    <div id="modal-show">
      <button id="modal-close">x
        {{-- <i class="fa fa-close" ></i> --}}
      </button>
      {{-- <button id="modal-previous"><</button>
      <button id="modal-next">></button> --}}
      <img id="modal-image" src="" alt="">
  </div>

    <script src="{{ asset('assets/chat/js/chat.js'); }}"></script>
@endsection

