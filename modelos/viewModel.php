<?php
class viewModel{

    protected function obtener_vistas_modelo($vista){
      $listaBlanca=["admin","adminlist","adminsearch",
                    "book","bookconfig","bookinfo","catalog","category","categorylist","client","clientlist","clientsearch","company","companylist",
                    "home","myaccount","mydata","provider","providerlist","search"];

                if(in_array($vista,$listaBlanca)){

                    if(is_file("./vistas/contenido/".$vista."-view.php")){
                        $contenido="./vistas/contenido/".$vista."-view.php";
                    }else{
                        $contenido="login";
                    }

                }elseif($vista=="login"){
                    $contenido="login";

                }elseif($vista=="index"){
                    $contenido="login";

                }else{
                    $contenido="404";
                }
                return $contenido;


    }

}