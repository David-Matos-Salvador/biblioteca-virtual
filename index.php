
<?php
require_once "core/configGeneral.php";
require_once "./controladores/viewControler.php";
 // instanciamos el modelo
$plantilla = new viewControler();
//Inicialisamos la plantilla por defecto
$plantilla->obtener_Plantilla_controler();


