<?php

require("config.php");

require("lib/db.php");
require("lib/sleep.php");

require("vendor/autoload.php");

// setup
define('APIKEY', $config['API_KEY']);
date_default_timezone_set('UTC');

// APIKEY helper
function verify_api_key() {
  $HDR = getallheaders();
  print_r($HDR);
  return isset($HDR['X-APIKEY']) && APIKEY === $HDR['X-APIKEY'];
}


// ///////////////////////////////////////////////////////////////////
class SleepHandler {
    function get() {
      echo json_encode( array("result" => get_last_notifications()) );
    }

    function post_xhr() {
      $resp = not_ok();

      // parse raw post data
      $request = file_get_contents('php://input');
      $input = json_decode($request, $asarray = true); // as array

      if( verify_api_key() && isset($input['origin']) && isset($input['event']) ) {
        if( insert_notification($input['origin'], $input['event']) ) {
          $resp = ok();
        }
      }

      echo $resp;
    }
}


ToroHook::add("404", function() {
    echo "Four-oh-oh-Four";
});

// open and close database connections before and anfter handling request
ToroHook::add("before_request", function() {
  DB::getInstance()->exec("CREATE TABLE IF NOT EXISTS sleep (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    origin VARCHAR(128),
    event VARCHAR(64),
    tstamp TEXT
  )");
});

ToroHook::add("after_request",  function() {
});


Toro::serve(array(
    "sleep/" => "SleepHandler"
));
