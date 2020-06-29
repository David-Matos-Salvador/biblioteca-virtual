<?php
if($peticionAjax){
    require_once "../core/mainModel.php";

}else{ require_once "./core/mainModel.php";
}

class LoginModels extends mainModel{
    
    protected function log_in_model($datos){
        $sql=mainModel::Connecion()->prepare("SELECT * FROM cuenta WHERE CuentaUsuario=:Usuario 
        And CuentaClave=:Clave AND CuentaEstado='Activo'");
        $sql->bindParam("Usuario",$datos['Usuario']);
        $sql->bindParam("Clave",$datos['Clave']);
        $sql->execute();
        return $sql;
        
    }
    protected function close_Sesion_Model($datos){
        if($datos['Usuario']!="" && $datos['Token_S']==$datos['Token'])
        {
            $Abitacora=mainModel::update_Bitacora($datos['Hora'],$datos['Codigo']);

            if ($Abitacora->rowCount()==1) {
                session_unset();
                session_destroy();
                $respuesta="true";
            } else {
                $respuesta="false";
            }
            
        }else{
            $respuesta="error";
        }
        return $respuesta;
    }
}