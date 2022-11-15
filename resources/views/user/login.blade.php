<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/chat/css/index.css'); }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
  <div class="wrapper">
    <section class="form login">
      <header>Web Nhắn Tin</header>
      <form action="{{route('login')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="error-text"></div>
        <div class="field input">
          <label for="email">Địa chỉ email</label>
          <input type="email" name="email" placeholder="Nhập email của bạn" value="{{$Remember ? $Remember['email'] : ""}}" required>
        </div>
        @if ($errors->has('email'))
          <span class="text-danger">{{ $errors->first('email') }}</span>
        @endif
        <div class="field input">
          <label for="password">Mật khẩu</label>
          <input type="password" name="password" placeholder="Nhập mật khẩu của bạn" value="{{$Remember ? $Remember['password'] : ""}}" required>
          <i class="fas fa-eye"></i>
        </div>
        @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
        <div class="field button">
          <input type="submit" name="submit" value="Đăng nhập">
        </div>
        <div class="form-check mb-3">
          <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="remember" value="1" {{$Remember ? 'checked' : ""}}> Nhớ tôi
          </label>
        </div>
      </form>
      <div class="link">Bạn chưa có tài khoản? <a href="/register">Đăng ký ngay</a></div>
    </section>
  </div>
  
  <script src="{{ asset('assets/chat/js/pass-show-hide.js'); }}"></script>
  <script src="{{ asset('assets/chat/js/.js'); }}"></script>

</body>
</html>