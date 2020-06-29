<?php
require_once "./modelos/viewModel.php";


class viewControler extends viewModel{


    public function obtener_Plantilla_controler(){
    return  require_once "./vistas/plantilla.php";
    }

    public function obtener_vista_Controler(){

        if(isset($_GET['views'])){
            $ruta = explode("/", $_GET['views']);
            $respuesta =viewModel::obtener_vistas_modelo($ruta[0]);//solo el valor no con la raya O.O?
        }else{
            $respuesta="login";

        }
        return $respuesta;
    }


}