<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  $offset = "2";
  $offset_type = -1;

  $contents = explode("\n", file_get_contents("test.srt",true));


  $pattern = '/\d{2}:\d{2}:\d{2},/';

  $new_contents = '';

  foreach ($contents as $key => $line) {

    //$line = "00:00:45,212 --> 00:00:47,506";
    $new_line = $line;
    $replacements;
    preg_match_all($pattern, $line, $matches);
    if(count($matches)>0){
      $new_line = preg_replace_callback($pattern,
                                      function($match) use ($offset,$offset_type){
                                        return adjust_time($match[0],$offset,$offset_type) . ",";
                                      },
                                      $line);
    }
    $new_contents[$key] = $new_line;
  }


  file_put_contents("new.srt", $new_contents);

  //adjust_time("00:00:45","2")


  function adjust_time($time, $offset, $offset_type=-1){
    $date = new DateTime($time);
    $interval = new DateInterval('PT'. $offset . 'S');

    if($offset_type == 1){
      $date->add($interval);
    }else{
      $date->sub($interval);
    }
    return $date->format("H:i:s");
  }



?>