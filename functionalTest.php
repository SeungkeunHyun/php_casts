<?php
    $ip = gethostbyname($_GET['host']);
    echo $ip;
    echo file_get_contents('http://'.$_GET['host']);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>