<?php
require_once(__DIR__.'/backend/request.php');

if(isset($_POST['req'])){
    $object=new Data();
    $object->useClass();
}



?>