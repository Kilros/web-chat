<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-3">
        <h2>Đăng ký</h2>
        <form method="POST" action="{{route('register')}}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 mt-3">
              <label for="name">Họ và tên:</label>
              <input type="name" class="form-control" id="name" placeholder="Nhập họ và tên" name="name">
            </div>
          <div class="mb-3 mt-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Nhập email" name="email">
          </div>
          <div class="mb-3">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Nhập mật khẩu" name="password">
          </div>
          <div class="mb-3">
            <label for="pwd">Ảnh đại diện:</label>
            <input type="file" class="form-control"  name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>