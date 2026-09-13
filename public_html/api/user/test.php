<?php
include("../../config/param.php");
include("../../config/header.php");
?>
<div id="items"></div>
<script>
  $.getJSON( "/api/user/getInventory", { 
    sort: "hat", 
    user: "2" 
  }).done(function( json ) {
    $.each(json.data, function( i, item ) {
      //$( "<img>" ).attr( "src", item.headshot ).appendTo( "#items" );
      $("#items").append("<img src='"+item.headshot+"'>")
    });
  }).fail(function(jqxhr, textStatus, error){
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
  });
</script>