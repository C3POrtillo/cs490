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

  // "contents" : {
  //   "student":"tfr0",
  //   "t_num":"0",
  //   "questions":{
  //     "1":{
  //       "q_num":"3",
  //       "answer":"def blah",
  //       "comment": "some comment",
  //       "item_points":{
  //         "1":0,
  //         "2":10,
  //         "f_name":2,
  //         "colon":1,
  //         "args":2,
  //         "constraint":0
  //       },
  //       "item_max_points":{
  //         "1":10,
  //         "2":10,
  //         "f_name":2,
  //         "colon":1,
  //         "args":2,
  //         "constraint":5
  //       },
  //       "score":15,
  //       "max":30,
  //       "test_c":2
  //     },
  //     "2":{
  //       "q_num":"2",
  //       "answer":"def blah",
  //       "comment": "some comment",
  //       "item_points":{
  //         "1":15,
  //         "2":15,
  //         "3":15,
  //         "f_name":0,
  //         "colon":1,
  //         "args":2
  //       },
  //       "item_max_points":{
  //         "1":15,
  //         "2":15,
  //         "3":15,
  //         "f_name":2,
  //         "colon":1,
  //         "args":2
  //       },
  //       "score":48,
  //       "max":50,
  //       "test_c":3
  //     }
  //   },
  //   "score":63,
  //   "max":80,
  //   "q_count":"2"
  // }

  $req = $_POST["req"];
  
  $json = json_decode($req);
  $content = $json->contents;
  $student = $content->student;
  $professor = get_professor( $student );
  $t_num = $content->t_num;
  $q_c = $content->q_count;
  $q_v = $content->questions;
  $score = $content->score;
  $max = $content->max;

  $q_str = $q_str = stringifyGradedQuestions( $q_v, $q_c );

  echo update_graded_test( $student, $professor, $t_num, $q_c, $q_str, $score, $max );
?>