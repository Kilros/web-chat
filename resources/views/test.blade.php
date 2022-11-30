
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <script>
        function test() {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.post("http://127.0.0.1:8000/get", "test", function(response){ 
                console.log(response);
                // var data = decodeURIComponent(response);
                // audio = new Audio("data:audio/wav;base64," + data);
                // var playPromise = audio.play();
                // if (playPromise !== undefined) {
                // playPromise.then(function() {
                //     requestAnimationFrame(test);
                // }).catch(function(error) {
                    
                //     });
                // }  
            });
        }
        </script>
</body>
</html>
