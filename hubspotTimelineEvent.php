<?php

// We check if post email and product are set
if((!empty($_POST['email']) && (!empty($_POST['field'])))){

  // If email is not valid
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die();
  }

  /** 0. DEFINE HUBSPOT APP CONSTANTS */
  
  define('CLIENT_ID', 'REPLACE_CLIENT_ID');
  define('CLIENT_SECRET', 'REPLACE_CLIENT_SECRET');
  define('REDIRECT_URI', 'REPLACE_REDIRECT_URI');
  define('REFRESH_TOKEN', 'REPLACE_REFRESH_TOKEN');
  define('EVENT_TEMPLATE_ID', 'REPLACE_EVENT_TEMPLATE_ID');
  
  /** 1. GET TOKEN REFRESHED */
  
  $urlToken = 'https://api.hubapi.com/oauth/v1/token';
  $dataToken = 'grant_type=refresh_token&client_id=' . CLIENT_ID . '&client_secret=' . CLIENT_SECRET . '&refresh_token=' . REFRESH_TOKEN;
  
  $cURLConnectionToken = curl_init($urlToken);
  curl_setopt($cURLConnectionToken, CURLOPT_HTTPHEADER, array(
    'Content-Type:  application/x-www-form-urlencoded;charset=utf-8'
  ));
  curl_setopt($cURLConnectionToken, CURLOPT_POSTFIELDS, $dataToken);
  curl_setopt($cURLConnectionToken, CURLOPT_RETURNTRANSFER, true);
  
  $apiResponseToken = curl_exec($cURLConnectionToken);
  curl_close($cURLConnectionToken);
  
  $responseToken = json_decode($apiResponseToken, true);

  /** 2. SEND TIMELINE EVENT TO HUBSPOT */
  
  $token = $responseToken['access_token'];
  $urlEvent = 'https://api.hubapi.com/crm/v3/timeline/events';
  $headerEvent = [
    'Content-Type: application/json;charset=utf-8',
    'Authorization: Bearer ' . $token
  ];
  $dataEvent = json_encode(
    [
      "eventTemplateId" => EVENT_TEMPLATE_ID,
      "email" => $_POST['email'],
      "tokens" => [
        "field" => $_POST['field'],
      ]
    ]
  );

  $cURLConnectionEvent = curl_init($urlEvent);
  curl_setopt($cURLConnectionEvent, CURLOPT_HTTPHEADER, $headerEvent);
  curl_setopt($cURLConnectionEvent, CURLOPT_POSTFIELDS, $dataEvent);
  curl_setopt($cURLConnectionEvent, CURLOPT_RETURNTRANSFER, true);
  
  echo $apiResponseEvent = curl_exec($cURLConnectionEvent);
  curl_close($cURLConnectionEvent);
  
  $responseEvent = json_decode($apiResponseEvent, true);

  die();

} else {
  die();
}
