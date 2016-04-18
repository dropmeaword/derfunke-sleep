<?php

function get_last_notifications() {
  $q = DB::getInstance()->query("SELECT origin, event, tstamp FROM sleep ORDER BY tstamp DESC");
  return $q->fetchAll(PDO::FETCH_ASSOC);
}

function insert_notification($origin, $event) {
  $st = DB::getInstance()->prepare("INSERT INTO sleep (origin, event, tstamp) VALUES ( :origin, :event, :tstamp )");
  $time = date('c');
  return $st->execute( array(
            ':origin' => $origin,
            ':event' => $event,
            ':tstamp' => $time
          ));
}

function ok() {
  return json_encode( array("ok" => "true") );
}

function not_ok() {
  return json_encode( array("ok" => "false") );
}
