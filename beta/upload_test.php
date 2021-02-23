<?php
  include("../env/account.php");
  include("../func/jsonfns.php");
  include("../func/validation.php");

  header('Content-type: application/json');

  $db = mysqli_connect($hostname, $username, $password, $project);
  if (mysqli_connect_errno()) {
    echo convert_to_json("503", "Unable to connect to database");
    exit();
  }

  // {
  //   "action": "createexam",
  //   "contents": {
  //     "professor": "cda23",
  //     "t_num": "0",
  //     "q_c": 3,
  //     "q_v": {
  //       "1": {
  //         "q_num": "1",
  //         "value": "33"
  //       },
  //       "2": {
  //         "q_num": "3",
  //         "value": "33"
  //       },
  //       "3": {
  //         "q_num": "5",
  //         "value": "34"
  //       }
  //     }
  //   }
  // }

  $req = $_POST["req"];

  $json = json_decode($req);

  $contents = $json->contents;
  $professor = $contents->professor;
  $t_num = $contents->t_num;
  $q_c = $contents->q_c;
  $q_v = $contents->q_v;

  $q_str = "{";
  for ($i = 1; $i <= $q_c; $i++) {
    $q_str .= "\"$i\":{\"q_num\":\"";
    $q_str .= $q_v->{"$i"}->q_num;
    $q_str .= "\",\"value\":\"";
    $q_str .= $q_v->{"$i"}->value;
    $q_str .= "\"}";
    if ($i < $q_c) {
      $q_str .= ",";
    }
  }
  $q_str .= "}";

  echo set_test( $t_num, $professor, $q_c, $q_str );
?>