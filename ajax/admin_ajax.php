<?php

$peticionAjax=true;
require_once "../core/configGeneral.php";
if(isset($_POST['dni-reg']) || isset($_POST['codigo-del'])){
 
    require_once "../controladores/adminControler.php";
    $insAdmin = new adminControler();
    if (isset($_POST['dni-reg']) && isset($_POST['nombre-reg']) && 
    isset($_POST['apellido-reg']) && isset($_POST['usuario-reg'])) {
        echo $insAdmin->add_admin_controler();   
    }
    if (isset($_POST['codigo-del']) && isset($_POST['privilegio-admin'])) {
        echo $insAdmin->delete_admin_Controler(); 
        
    }
    
    
}else{
    session_start(['name'=>'SBP']);
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/" </script>';  

} 