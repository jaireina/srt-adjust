<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  //options
  $offset = "2";        // seconds to bring forward or back
  $offset_type = -1;    // 1 for forward -1 for back
  $file = "test.srt";   // name of the srt file to edit
  $new_file = "new.srt";// new file name

  //time pattern
  $pattern = '/\d{2}:\d{2}:\d{2},/';

  //get content of the file
  $contents = explode("\n", file_get_contents($file,true));

  //read the file and look for the time to change
  $new_contents = '';
  foreach ($contents as $key => $line) {

    $new_line = $line;

    //check if this line is a time line
    preg_match_all($pattern, $line, $matches);
    if(count($matches)>0){
      $new_line = preg_replace_callback($pattern,
                                      function($match) use ($offset,$offset_type){
                                        return adjust_time($match[0],$offset,$offset_type) . ",";
                                      },
                                      $line);
    }
    //put every new_line in the $new_contents array
    $new_contents[$key] = $new_line;
  }

  // write the new file to
  file_put_contents($new_file, $new_contents);


  /*
  * Adjust time function
  * Add or substract to/from $time the amount specified in $offset.
  * @param $time [string]: the time to be edited in the format ##:##:## (H:i:s)
  * @param $offset [string]|[int]: the amount of seconds to be added or substracted from $time.
  * @param $offset_time [int]: -1 to substract and 1 to add
  */
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