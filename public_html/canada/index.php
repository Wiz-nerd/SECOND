<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Trades";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

if(!isset($_SESSION['id'])){
  die(header("Location: /login/")); 
}
?>
<section class="section">
  <div class="container">
    <div class="content">
      <h1 class="title has-text-white">Trades</h1>
      <div class="columns">
        <div class="column is-5">
          <div class="select is-dark is-fullwidth">
            <select id="options">
              <option value="incoming">Incoming</option>
              <option value="outgoing">Outgoing</option>
              <option value="history">History</option>
            </select>
          </div>
        </div>
        <div class="column is-7">
          <div id="display"></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?=footer()?>
<script>
  $(function(){
    $("#options").change(function(){
      $.get("/canada/get-trades.php?get="+$("#options").val()+"", function(data, status){
        console.log(status)
        $("#display").html(data) 
      })
    })
    $.get("/canada/get-trades.php?get=incoming", function(data, status){
      console.log(status)
      $("#display").html(data) 
    })
  })
</script>