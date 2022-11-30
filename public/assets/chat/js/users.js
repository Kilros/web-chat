const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
searchSpan = document.querySelector(".search span"),
usersList = document.querySelector(".users-list"),
setting = document.querySelector("#setting i"),
menuSetting = document.querySelector("#menuSetting"),
profile = document.querySelector("#profile"),
modelContent = document.querySelector("#modelContent"),
modelContentProfile = document.querySelector("#modelContentProfile"),
changePassword = document.querySelector("#changePassword"),
modelContentPassword = document.querySelector("#modelContentPassword"),
passwordNew = document.querySelector("#passwordNew"),
passwordVerify = document.querySelector("#passwordVerify"),
modalSetting = document.querySelector("#modalSetting"),
modalSettingClose = document.querySelector("#modalSettingClose"),
logout = document.querySelector("#logout");

setting.onclick =(e)=>{
  e.stopPropagation();
  menuSetting.style.display = "block";
}
document.onclick = (e)=>{
  menuSetting.style.display = "none";
};
changePassword.onclick = ()=>{
  modalSetting.style.position = "fixed";
  modelContentProfile.style.display = "none";
  modelContentPassword.style.display = "block";
  modelContent.style.display = "block";
};
profile.onclick = ()=>{
  modalSetting.style.position = "fixed";
  modelContentPassword.style.display = "none";
  modelContentProfile.style.display = "block";
  modelContent.style.display = "block";
};
modalSettingClose.onclick = ()=>{
  modalSetting.style.position = "static";
  modelContent.style.display = "none";
};

searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchSpan.classList.toggle("hide");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(!searchIcon.classList.contains("active")){
    searchBar.value = "";
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post("/admin", {
      'action' : 'get_user',
    }, function(data){ 
        usersList.innerHTML = data;                 
    });
    // searchBar.classList.remove("active");
  }
};
passwordVerify.onchange = ()=>{
  if(passwordNew.value != passwordVerify.value){
    passwordVerify.style.color = 'red';
  }
  else{
    passwordVerify.style.color = 'black';
  }
}

logout.onclick = ()=>{
  var check = confirm('Bạn có chắn chắn muốn đăng xuất!');
  if(check){
    window.location="/logout";
  }
};

$(document).ready(function(){
  $("#inputSearch").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".users-list .list-user-chat").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
  });
});

setInterval(() =>{
  if(searchBar.value == ""){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post("/admin", {
      'action' : 'get_user',
    }, function(data){ 
      // console.log(data);
      // $(".users-list").empty();
      // $(".users-list").append(data);
        usersList.innerHTML = data;                 
    });
  }
}, 2000);


window.addEventListener('resize', (event) => {
  if(window.innerWidth > 750){
    $("#nav").show();
    $("#chat").show();
  }
});

function back() {
  if(window.innerWidth < 720){
    $("#nav").show();
    $("#chat").hide();
    $("#back").hide();
  }
}
// $(document).ready(function(){
//   if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
//     $("#nav").width("100%");
//     $("#chat").width("100%");
//     $("#nav").show();
//     $("#chat").hide();
//     console.log("mobile device");
//   }else{
//     // false for not mobile device
//     console.log("not mobile device");
//   }
// })


