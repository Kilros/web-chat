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
    $("#idChat").html(id);
    if(window.innerWidth < 720){
        $("#nav").hide();
        $("#chat").show();
        $("#back").show();
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post('/admin/chat', {
        'action' : 'get_chat',
        'id' : id,
        'load' : 15, 
    }, function(data){
        $('#nameUserChat').html(data.name);
        $('#imageChat').attr("src",data.image);
        $('#statusUserChat').html(data.status);
        chatBox.innerHTML = data.msg;
        $("#count").html(data.count);
        $("#numberLine").html(data.load);
        // count = parseInt(data.count);
        if(!chatBox.classList.contains("active")){
            scrollToBottom();
        }        
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
    id = $("#idChat").html();
    numberLine = $("#numberLine").html();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post('/admin/chat', {
        'action' : 'get_chat',
        'id' : id,
        'load' : numberLine,
        'count' : $("#count").html(),
      }, function(data){
        $('#nameUserChat').html(data.name);
        $('#statusUserChat').html(data.status);
        // console.log(data);
        if(data.msg){
            $(".chat-box").append(data.msg);
            $("#count").html(data.count);
            // if(!chatBox.classList.contains("active")){
            scrollToBottom();
            // } 
        }
        // count = parseInt(data.count);    
      });
}, 1000);
function getchat() {
    id = $("#idChat").html();
    numberLine = $("#numberLine").html();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      $.post('/admin/chat', {
        'action' : 'get_chat',
        'id' : id,
        'load' : numberLine,
      }, function(data){
        $('#nameUserChat').html(data.name);
        $('#statusUserChat').html(data.status);
        chatBox.innerHTML = data.msg;
        $("#count").html(data.count);
        $("#numberLine").html(data.load);
        // count = parseInt(data.count);
        // if(!chatBox.classList.contains("active")){
        scrollToBottom();
        // }     
    });
}
function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
}
var currentScroll = chatBox.scrollTop;
chatBox.addEventListener("scroll", (event) => {
    if(chatBox.scrollTop <= 10){
        load = parseInt($("#numberLine").html());
        if(load < $("#count").html()){
            $("#numberLine").html(load+6);
            getchat(); 
            chatBox.scrollTop = 20;
        }
    }
});

const input = document.getElementById('image_uploads')
input.addEventListener('change', updateImageDisplay);
var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.mp3|\.mp4)$/i;
function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}
function updateImageDisplay() {
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

