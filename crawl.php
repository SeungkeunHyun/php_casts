<?php
    print_r($_GET);
    header('Content-Type: text/html; charset=utf-8');
    $provider = $_GET['provider'];
    $id = $_GET['id'];
    if($provider == 'podbbang') {
        $uri = '//www.podbbang.com/podbbangchnew/episode_list?id='.$id;
    } else {
        $uri = '//www.podty.me/cast/'.$id;
    }
    echo $uri;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  //  echo exec("wget ".$_GET['uri']);
?>
