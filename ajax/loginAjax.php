<?php

$peticionAjax=true;
require_once "../core/configGeneral.php";
if(isset($_GET['Token'])){
 
    require_once "../controladores/LoginControler.php";
    $logout = new LoginControler();
    
    echo $logout->close_Sesion_Controler();   
    
}else{
    session_start(['name'=>'SBP']);
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/" </script>';  

} 