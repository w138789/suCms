<?php
$echoStr = '';
if(isset($_GET['echostr'])){
    $echoStr = $_GET['echostr'];
}
if(!empty($echoStr)){
    echo $echoStr; exit;
}else{
    writeLog('开始回复sss1');
    writeLog($GLOBALS["HTTP_RAW_POST_DATA"]);
}


function writeLog($text) {
    file_put_contents( "log.txt", date("Y-m-d H:i:s") . "  " . $text . "\r\n", FILE_APPEND);
}
