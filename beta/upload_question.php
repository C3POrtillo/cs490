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
  //   "action": "addquestion",
  //   "contents": {
  //     "topic": "variables",
  //     "difficulty": "0",
  //     "question": "p",
  //     "arg_c": 1,
  //     "arg_v": {
  //       "1": {
  //         "name": "1",
  //         "desc": "2"
  //       }
  //     },
  //     "test_c": 1,
  //     "test_v": {
  //       "1": {
  //         "input": "3",
  //         "output": "4"
  //       }
  //     }
  //   }
  // }

  $req = $_POST["req"];

  $json = json_decode($req);

  $contents = $json->contents;
  $name = mysqli_real_escape_string($db, $contents->name);
  $topic = $contents->topic;
  $diff =  $contents->difficulty;
  $f_name = $contents->f_name;
  $constraint = $contents->constraint;
  $question = mysqli_real_escape_string($db, $contents->question);
  $arg_c = $contents->arg_c;
  $arg_v = $contents->arg_v;
  $test_c = $contents->test_c;
  $test_v = $contents->test_v;

  $arg_str = "{";
  for ($i = 1; $i <= $arg_c; $i++) {
    $arg_str .= "\"$i\":{\"name\":\"";
    $arg_str .= urlencode($arg_v->{"$i"}->name);
    $arg_str .= "\",\"desc\":\"";
    $arg_str .= urlencode($arg_v->{"$i"}->desc);
    $arg_str .= "\"}";
    if ($i < $arg_c) {
      $arg_str .= ",";
    }
  }
  $arg_str .= "}";

  $test_str = "{";
  for ($i = 1; $i <= $test_c; $i++) {
    $test_str .= "\"$i\":{\"input\":\"";
    $test_str .= urlencode($test_v->{"$i"}->input);
    $test_str .= "\",\"output\":\"";
    $test_str .= urlencode($test_v->{"$i"}->output);
    $test_str .= "\"}";
    if ($i < $test_c) {
      $test_str .= ",";
    }
  }
  $test_str .= "}";

  echo set_question( $name, $topic, $diff, $f_name, $constraint, $question, $arg_c, $arg_str, $test_c, $test_str );
?>