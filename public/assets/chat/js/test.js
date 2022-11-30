var mediaRecorder = null;
let chunks = [];

function test() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // console.log('getUserMedia supported.');
    navigator.mediaDevices.getUserMedia (
        {
            audio: true
        })
        .then(function (stream) {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start(2000);
            mediaRecorder.ondataavailable = async function(e) {
                chunks.push(e.data);
                const blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
                chunks = [];
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {
                var data = reader.result.split(";base64,")[1]; 
                requestp2("/admin/chat", "data="+encodeURIComponent(data));
                }
    }
        })
        .catch(function(err) {
            console.log('The following getUserMedia error occurred: ' + err);
        }
    );
    } else {
    console.log('getUserMedia not supported on your browser!');
    }
}
test()
function requestp2(path, data)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/admin/sent", data, function(response){ 
            // var data = decodeURIComponent(response);
            // audio = new Audio("data:audio/wav;base64," + response);
            // var playPromise = audio.play();
            // if (playPromise !== undefined) {
            // playPromise.then(function() {
            if(response == "true"){
                test()
                // setTimeout(, 2000);
            }
            // }).catch(function(error) {
                
            //     });
            // }
            // var dataAudio = Uint8Array.from(atob(encoded), c => c.charCodeAt(0))
            // playByteArray(dataAudio);
            // tao(channels, dataAudio.length, dataAudio);
            // audio.play();
            // audio.loop();  
      });
    // var http = new XMLHttpRequest();
    // http.onreadystatechange = function() { 
    //     if (http.readyState == 4 && http.status == 200)
    //         var encoded = decodeURIComponent(http.response);
    //         var audio = new Audio("data:audio/wav;base64," + encoded);
    //         audio.play();
    // }
    // http.open('POST', path, true);
    // http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    // http.send(data);
}


setInterval(
    function rqAudio(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/admin/get", {
            "action" : 'get',
        }, function(response){ 
            audio = new Audio("data:audio/wav;base64," + response.msg);
            var playPromise = audio.play();
            if (playPromise !== undefined) {
            playPromise.then(function() {
                
            }).catch(function(error) {
                
                });
            }
            // rqAudio();
        });
    }  
, 2000);
function _base64ToArrayBuffer(base64) {
    var binary_string = window.atob(base64);
    var len = binary_string.length;
    var bytes = new Uint8Array(len);
    for (var i = 0; i < len; i++) {
        bytes[i] = binary_string.charCodeAt(i);
    }
    return bytes.buffer;
}

window.onload = init;
var context;    // Audio context
var buf;        // Audio buffer
let source;


function init() {
if (!window.AudioContext) {
    if (!window.webkitAudioContext) {
        alert("Your browser does not support any AudioContext and cannot play back this audio.");
        return;
    }
        window.AudioContext = window.webkitAudioContext;
    }

    context =  new (window.AudioContext || window.webkitAudioContext)();
    source = context.createBufferSource();
}

function playByteArray(byteArray) {
    var arrayBuffer = new ArrayBuffer(byteArray.length);
    var bufferView = new Uint8Array(arrayBuffer);
    for (i = 0; i < byteArray.length; i++) {
      bufferView[i] = byteArray[i];
    }
    context.decodeAudioData(arrayBuffer, function(buffer) {
        // buf = buffer;
        source.disconnect();
        source.buffer = buffer;
        source.connect(context.destination);
        source.loop = true;
        source.start(0);
        // play();
    });
}


// Play the loaded file
function play() {
    // Create a source node from the buffer
    // source = context.createBufferSource();
    source.disconnect();
    
    source.buffer = buf;
    // Connect to the final output node (the speakers)
    source.connect(context.destination);
    // Play immediately
    // source.loop = true;
    source.start(0);
    // source.stop(0);
    
    // source.onended = () => {
    //     console.log("White noise finished.");
    // };
}
