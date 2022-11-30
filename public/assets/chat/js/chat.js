const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e)=>{
    e.preventDefault();
}

inputField.focus();
inputField.onkeyup = ()=>{
    if(inputField.value != ""){
        sendBtn.classList.add("active");
    }else{
        sendBtn.classList.remove("active");
    }
}

function selectUser(id) {
    if(window.innerWidth < 720){
        $("#nav").hide();
        $("#chat").show();
        $("#back").show();
    }
    // $("#count").html(0);
    // $("#numberLine").html(0);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post('/admin/chat', {
        'action' : 'get_chat',
        'actionDetail' : 'select',
        'id' : id,
    },function(data){
        $('#nameUserChat').html(data.name);
        $('#imageChat').attr("src",data.image);
        $('#statusUserChat').html(data.status);
        $(".chat-box").html(data.msg);   
        $("#count").html(data.count);
        $("#numberLine").html(data.load);
        $("#idChat").html(id);
        // $(".chat-box").animate({ scrollTop: $(".chat-box").prop('scrollHeight')});
        scrollToBottom(); 
        // count = parseInt(data.count);
    });    
}

sendBtn.onclick = ()=>{
    sendBtn.classList.remove("active");
    id = $("#idChat").html();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post('/admin/chat', {
        'action' : 'insert_chat',
        'id' : id,
        'msg' : inputField.value
        }, function(data){
        if(data == 1){
            inputField.value = "";
            scrollToBottom();   
        }
        else{
            sendBtn.classList.add("active");
        }         
    });
}
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}
setInterval(() =>{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    $.post('/admin/chat', {
        'action' : 'get_chat',
        'actionDetail' : 'single',
        'id' : $("#idChat").html(),
        'load' : $("#numberLine").html(),
        'count' : $("#count").html(),
      }, function(data){
        $('#nameUserChat').html(data.name);
        $('#statusUserChat').html(data.status);
        $('#imageChat').attr("src",data.image);
        if(data.msg){
            $(".chat-box").append(data.msg);
            $("#count").html(data.count);
            $("#numberLine").html(data.load);
            scrollToBottom(); 
        } 
      });
}, 1000);
function loadChat() {
    var load = $("#numberLine").html();
    if(parseInt($("#count").html()) - parseInt($("#numberLine").html()) < 10){
        $("#numberLine").html(parseInt($("#numberLine").html())+10);
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post('/admin/chat', {
        'action' : 'get_chat',
        'actionDetail' : 'load',
        'id' : $("#idChat").html(),
        'load' : load,
    }, function(data){
        $(".chat-box").prepend(data.msg);
        $("#count").html(data.count);
        $("#numberLine").html(data.load)
        $("#load").hide();   
        $('.chat-box').css('overflow-y','auto');
    });
}
function scrollToBottom(){
    new Promise(function(resolve, reject) {
        setTimeout(() => resolve($(".chat-box").prop('scrollHeight')), 200); // (*)
    }).then(function(result) {
        $(".chat-box").animate({ scrollTop: result}, 200);
    });
    // $(".chat-box").animate({ scrollTop: $(".chat-box")[0].scrollHeight}, 500);
    // chatBox.scrollTop = chatBox.scrollHeight;
}
$(document ).ready(function() {
    chatBox.addEventListener("scroll", (event) => {
        if(chatBox.scrollTop <= 10){
            chatBox.scrollTop = 20;
            if(parseInt($("#numberLine").html()) < parseInt($("#count").html()) && $("#count").html()!='0'){
                $('#load').show();
                $('.chat-box').css('overflow-y','visible');
                loadChat();    
            }
        }
    });
});


const input = document.getElementById('image_uploads')
input.addEventListener('change', updateImageDisplay);
var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.mp3|\.mp4)$/i;
function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}
function updateImageDisplay() {
    $('#load').show();
    const curFiles = input.files;
    id = $("#idChat").html();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (curFiles.length === 0) {
        alert('Bạn chưa chọn tập tin!');
    } else {
        for (const file of curFiles) {
            if(!allowedExtensions.exec(file.name)){
                alert('Tập tin đuôi '+getExtension(file.name)+' không được hỗ trợ!');
            }
            else{
                var list = new FormData();
                list.append('file', file);
                list.append('id', id);
                list.append('action', 'insert_file');
                $.ajax({
                    url: '/admin/chat',
                    type: 'post',
                    data: list,
                    contentType: false,
                    processData: false,
                    success: function(response){    
                        if(response == 1){
                            $("#load").hide(); 
                        }else{
                            alert(response);
                        }
                    },
                 });
            }
        }
        input.value=null;
        scrollToBottom();  
    }
}
function show(link){
    $('#modal-show').css('position', 'fixed');
    $('#modal-image').attr("src", link);
    $('#modal-image').show();
    // $('#modal-previous').show();
    // $('#modal-next').show();
    $('#modal-close').show();
    $('html, body').css('overflow', 'hidden');
}
$('#modal-close, #modal-show').click(function(){
    $('#modal-show').css('position', 'static');
    $('#modal-image').hide();
    // $('#modal-previous').hide();
    // $('#modal-next').hide();
    $('#modal-close').hide();
    $('html, body').css('overflow', 'visible');
});

