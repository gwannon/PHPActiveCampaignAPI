<?php

namespace Gwannon\PHPActiveCampaignAPI;

/*
 * TODO:
 *
 */

/* ----------------- CLASS CURL -------------------- */
class curlAC {

  //Funciones CURL-----------------------------
  public static function curlCall($link, $request = 'GET', $payload = false) {
    $curl = curl_init();
    $headers[] = 'Api-Token: '.AC_API_TOKEN;
    curl_setopt($curl, CURLOPT_URL, AC_API_DOMAIN.$link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    if (in_array($request, array("PUT", "POST", "DELETE"))) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request);
    if ($payload) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
      $headers[] = 'Content-Type: application/json';
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    $json = json_decode($response);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if (in_array($httpcode, array(200, 201, 204))) {
      if(AC_LOG_API_CALLS) curlAC::curlLog("logs", $link, $request, $httpcode, $payload );
      return $json;
    } else {
      if(AC_LOG_API_CALLS) curlAC::curlLog("errors", $link, $request, $httpcode, $payload, json_encode($json));
      //throw new Exception($httpcode." - ".json_encode($json));
      return false;
    }
  }

  //GET
  public static function curlCallGet($link) { return curlAC::curlCall($link); }

  //PUT
  public static function curlCallPut($link, $payload) { return curlAC::curlCall($link, "PUT", $payload); }

  //POST
  public static function curlCallPost($link, $payload) { return curlAC::curlCall($link, "POST", $payload); }

  //DELETE
  public static function curlCallDelete($link) { return curlAC::curlCall($link, "DELETE"); }

  //Log system
  public static function curlLog($file, $link, $request, $payload, $httpcode = "", $json = "") {
    $f = fopen(dirname(__FILE__)."/../logs/".$file.".txt", "a+");
    $line = date("Y-m-d H:i:s")."|".$link."|".$request."|".$httpcode;
    if($payload != '') $line .= "|".$payload;
    if($json != '') $line .= "|".$json;
    $line .= "\n";
    fwrite($f, $line);
    fclose($f);
  }
}