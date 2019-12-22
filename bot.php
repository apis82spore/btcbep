<?php

if(!file_exists("data.json")){
  echo "creating json file \n";
  $jsonfile = fopen("data.json", "w");
  fwrite($jsonfile,json_encode(["PHPSESSID"=>null,"UserID"=>null]));
  fclose($jsonfile);
}

$json = json_decode(file_get_contents("data.json"),true);

sleep(1);

$sid = $json['PHPSESSID']; //PHPSESSID
$uid = $json['UserID']; //User ID

$current_balance = 0;
$total = 0;        
$count = 1;
$site = "bitcoinbep.com";
// $site = "earnyourcrypto.com";

function phc(){
    echo "\e[H\e[J";
        echo "\e[1;34;40m
      ___         ___           ___     
     /\  \       /\  \         /\__\    
    /::\  \      \:\  \       /:/  /    
   /:/\:\__\      \:\  \     /:/  /     
  /:/ /:/  /  ___ /::\  \   /:/  /  ___ 
 /:/_/:/  /  /\  /:/\:\__\ /:/__/  /\__\
 \:\/:/  /   \:\/:/  \/__/ \:\  \ /:/  /
  \::/__/     \::/__/       \:\  /:/  / 
   \:\  \      \:\  \        \:\/:/  /  
    \:\__\      \:\__\        \::/  /   
     \/__/       \/__/         \/__/\e[0m\n\n";
     echo "🥐🥐\e[1;34;40m BITCOINBEP \e[0m🥐🥐\n";
     echo "==============================================\n";
}

function setdata(){
  global $sid,$uid;
  phc();
  echo "Action:\n";
  echo "[1] Set PHPSESSID \n";
  echo "[2] Set User ID \n";
  echo "[3] Start Bot \n";
  $opt = readline("Enter: ");

  if($opt == 1){
    $sid = readline("Enter PHPSESSID: ");
    $json = json_decode(file_get_contents("data.json"),true);
    $json['PHPSESSID'] = $sid;
    $jsonfile = fopen("data.json", "w");
    fwrite($jsonfile,json_encode($json));
    fclose($jsonfile);

    echo "PHPSESSID updated\n";
    sleep(2);
    setdata();
  }
  elseif($opt == 2){
    $uid = readline("Enter User ID: ");
    $json = json_decode(file_get_contents("data.json"),true);
    $json['UserID'] = $uid;
    $jsonfile = fopen("data.json", "w");
    fwrite($jsonfile,json_encode($json));
    fclose($jsonfile);

    echo "User ID updated\n";
    sleep(2);
    setdata();
  }
  elseif($opt == 3){
    if(!empty($sid) && !empty($uid)){
      phc();
      check(true);
    }
    else{
      echo "Please Update PHPSESSID/User ID";
      sleep(2);
      setdata();
    }
  }
  else{
    setdata();
  }
}


function init(){
  global $sid,$total,$site,$uid;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://$site/friend.php?user_id=$uid&t=1576840046&aid=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HEADER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Connection: keep-alive", 
      "Host: $site",
      "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
      "accept-language: en-US,en;q=0.9",
      "cache-control: no-cache",
      "cookie: PHPSESSID=$sid",
      "sec-fetch-mode: navigate",
      "sec-fetch-site: none",
      "sec-fetch-user: ?1",
      "upgrade-insecure-requests: 1",
      "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36"
    ),
  ));

  $err = curl_error($curl);
  
  $response = curl_exec($curl);
  
  $httpcode = curl_getinfo($curl);
  curl_close($curl);

  if($httpcode["http_code"] == "200"){
    if($httpcode["url"] == "https://$site/captcha"){
      echo "Total points: $total\n";
      echo "Captcha Required\n";
      readline("Enter to Continue: ");
      init();

    }
    elseif($httpcode["url"] == "https://$site/"){
      echo "Please Update Session ID\n";
      $sid = readline("Enter: ");
      $json = json_decode(file_get_contents("data.json"),true);
      $json['PHPSESSID'] = $sid;
      $jsonfile = fopen("data.json", "w");
      fwrite($jsonfile,json_encode($json));
      fclose($jsonfile);
      init();
    }
    elseif($httpcode["url"] == "https://$site/visit-friends?show=upgrade_text"){
      echo "No ADS Available\n";
    }
    else{
      check();
    }
  }
  else{
      echo "Failed Http Code: ".$httpcode["http_code"];
  }
}


function check($first = false){
  global $current_balance,$sid,$total,$count,$site;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://$site/home",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HEADER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Connection: keep-alive", 
      "Host: $site",
      "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
      "accept-language: en-US,en;q=0.9",
      "cache-control: no-cache",
      "cookie: PHPSESSID=$sid",
      "sec-fetch-mode: navigate",
      "sec-fetch-site: none",
      "sec-fetch-user: ?1",
      "upgrade-insecure-requests: 1",
      "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36"
    ),
  ));

  $err = curl_error($curl);
  
  $response = curl_exec($curl);

  $httpcode = curl_getinfo($curl);
  curl_close($curl);

  if($httpcode["http_code"] == "200"){
    if($httpcode["url"] == "https://$site/captcha"){
      echo "Total points: $total\n";
      echo "Captcha Required\n";
      readline("Enter to Continue: ");
      check($first);
    }
    elseif($httpcode["url"] == "https://$site/"){
      echo "Please Update Session ID.\n";
      $sid = readline("Enter: ");
      $json = json_decode(file_get_contents("data.json"),true);
      $json['PHPSESSID'] = $sid;
      $jsonfile = fopen("data.json", "w");
      fwrite($jsonfile,json_encode($json));
      fclose($jsonfile);
      check($first);
    }
    else{
      $dom = new domdocument;
      @$dom->loadHTML($response);
      $balance = "";
      foreach ($dom->getElementsByTagName("a") as $a) {
          if( $a->getAttribute("href") == "https://$site/exchange"){
              $balance = (double) str_replace("$","",$a->textContent);
          }
      }


      if($first){
        $current_balance = $balance;
        sleep(2);
        init();
      }
      else{
        $points = $balance - $current_balance;
        $current_balance = $balance;
        $total += $points;
        if($points == 0){
          echo "Total points: $total\n";
          echo "No Ads Available\n";
        }
        else{
          echo ($count < 10 ? "0".$count : $count)." | \e[1;32;40m+$".number_format($points,6)."\e[0m | Balance: $$balance\n";
          $count++;
          sleep(3);
          init();
        }
      }
    }
  }
  else{
      echo "Failed Http Code: ".$httpcode["http_code"];
  }
}

setdata();