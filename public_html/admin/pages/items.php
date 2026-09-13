<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
?>
<form method="post" id="poset">
<input id="search" class="input is-dark" placeholder="Input Item Name Here">
</form>
<p class="help is-danger" id="error"></p>
<p class="help is-success" id="succ"></p>
<div id="user"></div>
<script>
$("#poset").submit(function(e){
  e.preventDefault();
  $.post("/admin/posts/item", {
    search: $("#search").val()
  }).done(function(resp){
    if(resp != "succ"){
      $("#error").text(resp)
    }else{
      $("#user").load("/admin/posts/get-item?query="+encodeURIComponent($("#search").val())+"")
      $("#error").empty()
      $("#succ").empty()
    }
  }).fail(function(){
    alert("There was an error processing this request")
  })
})
</script>