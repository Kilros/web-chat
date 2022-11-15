const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list");

searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}
$(document).ready(function(){
  $(".search input").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".users-list a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
  });
});

setInterval(() =>{
  if($(".search input").val() == ""){
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


