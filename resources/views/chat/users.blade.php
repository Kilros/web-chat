@extends('layouts.home')
@section('title', 'Trang chủ')
@section('nav')
    <div class="wrapper">
      {{-- <p id="idChat">{{$user['id']}}</p> --}}
      <section  style="position: relative; height: 100%" class="users">
        <div id="headerTitle">
          <div class="search">
            <img src="{{asset('appchat/file/'.$user['image'])}}" alt="image">
            <span class="text">Chọn người để trò chuyện</span>
            <input id="inputSearch" type="text" placeholder="Tìm kiếm...">
            <button><i class="fas fa-search"></i></button>
          </div>
          {{-- <div style="float: left; width: 35%">
            <a href="#"><img style="float: left;" src="{{asset('assets/imageUser/'.$user['image'])}}" alt=""></a>
          </div>
          <div style="display: flex; align-items: center; width: 65%" class="details">
            <span>{{$user['name']}}</span>
          </div>  --}}
        </div>
        {{-- <div class="search">
          <span class="text">Chọn người để trò chuyện</span>
          <input type="text" placeholder="Tìm kiếm...">
          <button><i class="fas fa-search"></i></button>
        </div> --}}
        <div class="users-list">
    
        </div>
        <div id="setting">
          <div id="menuSetting">
            <ul>
              <li id="profile">Hồ sơ</li>
              <li id="changePassword">Đổi mật khẩu</li>
              <li id="logout">Đăng xuất</li>
            </ul>
          </div>
          <i class="fas fa-cog"></i>
        </div>
        <div id="modalSetting">
          <div id="modelContent">
            <button id="modalSettingClose">x</button>
            <div id="modelContentPassword">
              <h2>Đổi mật khẩu</h2>
              <form action="{{route('changePassword')}}" method="POST">
                @csrf
                <div class="mb-3 mt-3">
                  <label for="passwordOldpasswordOld">Mật khẩu cũ:</label>
                  <input type="password" class="form-control" id="passwordOld" placeholder="Nhập mật khẩu cũ" name="passwordOld" required>
                </div>
                <div class="mb-3 mt-3">
                  <label for="passwordNew">Mật khẩu mới:</label>
                  <input type="password" class="form-control" id="passwordNew" placeholder="Nhập mật khẩu mới" name="passwordNew" required>
                </div>
                <div class="mb-3 mt-3">
                  <label for="passwordVerify">Nhập lại mật khẩu mới:</label>
                  <input type="password" class="form-control" id="passwordVerify" placeholder="Nhập lại mật khẩu mới" name="passwordVerify" required>
                </div>
                <button class="submit" type="submit">Thay đổi</button>
              </form>
            </div>
            <div id="modelContentProfile">
              <h2>Đổi thông tin</h2>
              <form action="{{route('changeProfile')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 mt-3">
                  <label for="name">Họ và tên:</label>
                  <input type="text" class="form-control" id="name" placeholder="Nhập họ và tên" name="name" value="{{$user['name']}}" required>
                </div>
                <div class="mb-3 mt-3">
                  <label for="photo">Ảnh đại diện:</label>
                  <input type="file" class="form-control" id="photo" accept="image/x-png,image/gif,image/jpeg,image/jpg" name="photo">
                </div>
                <button class="submit" type="submit">Thay đổi</button>
              </form>
            </div>
          </div>
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
                <img id="imageChat" src="{{($user['id']==Auth::user()->id) ? asset('assets/cloud.png')  : asset('appchat/file/'.$user['image'])}}" alt="image">
                <div class="details">
                    <span id="nameUserChat">{{($user['id'] == Auth::user()->id) ? 'Cloud' :$user['name']}}</span>
                    <p id="statusUserChat">{{$status}}</p>
                    <p style="display: none;" id="idChat">{{$user['id']}}</p>
                    <p style="display: none;" id="numberLine">0</p>
                    <p style="display: none;" id="count">0</p>
                </div>
                <div id="load" style="position: absolute; left: 50%; top: 100px; display:none">
                  <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
            </header>
            <div class="chat-box">

            </div>
            
            <form action="#" class="typing-area">
                <div>
                    <label for="image_uploads">Tập tin</label>
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

