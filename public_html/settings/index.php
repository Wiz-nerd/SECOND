<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

$pageName = "Settings";
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");

csrf();
?>
<div id="popup">
  <div class="modal">
    <div onclick="closeModal()" class="modal-background"></div>
    <div class="modal-content">
      <div class="box">
        <p class="subtitle has-text-white">Change Username</p>
        <input type="text" id="newUsername" placeholder="New Username" class="input is-dark">
        <p id="errorMsgs" class="help is-danger"></p>
        <div style="height:10px"></div>
        <button id="changeUsername" class="button is-success">Change Username (Costs 10 Diamonds)</button>
      </div>
    </div>
    <button onclick="closeModal()" class="modal-close is-large" aria-label="close"></button>
  </div>
</div>
<div class="column is-6 is-centered">
  <div class="box">
    <div class="tabs is-centered"><ul>
      <li><a onclick="tab(1)">General</a></li>
      <li><a onclick="tab(2)">Security</a></li>
      <li><a onclick="tab(3)">Privacy</a></li>
      <li><a onclick="tab(4)">Extra</a></li>
      </ul></div>
    <div id="settings"></div>
  </div>
</div>
<?php
footer();
?>
<script>
  $(document).ready(function(){
    $("#settings").load("/settings/pages/general");
  })

  function tab(num){
    switch(num){
      case 1:
        $.get("/settings/pages/general", function(status, result){
              $("#settings").html(status);
    })
    break;
    case 2:
    $.get("/settings/pages/security", function(status, result){
          $("#settings").html(status);
  })
  break;
  case 3:
  $.get("/settings/pages/privacy", function(status, result){
        $("#settings").html(status);
  })
  break;
  case 4:
  $.get("/settings/pages/extra", function(status, result){
        $("#settings").html(status);
  })
  break;
  default:

  }
  }
</script>