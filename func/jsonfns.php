<?php
  function convert_to_json( $status, $message ) {
    return json_encode( [ "status" => "$status", "message" => "$message" ] );
  }
?>