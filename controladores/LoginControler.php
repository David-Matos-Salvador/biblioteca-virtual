<?php
if($peticionAjax){
    require_once "../modelos/LoginModels.php";
}else{ require_once "./modelos/LoginModels.php";}


class LoginControler extends LoginModels{
    
    public function log_in_Controler(){
        $user=mainModel::limpiar_cadena($_POST['users']);
        $password=mainModel::limpiar_cadena($_POST['password']);
        $password=mainModel::encryption($password);
        $datosAccount=[
            "Usuario"=>$user,
            "Clave"=>$password,
        ];
        $datosAccount1=LoginModels::log_in_model($datosAccount);
    
    if ($datosAccount1->rowCount()==1) {
            $row=$datosAccount1->fetch();

            $fechaActual=date("y-m-d");
            $yearActual=date("y");
            $horaActual=date("h:i:s a");

            $consulta1=mainModel::obtener_consulta_simple("SELECT  id FROM bitacora");
            $numero=($consulta1->rowCount())+1;
            
                    $codigoB=mainModel::generar_codigo_aleatorio("CB",7,$numero)  ;
            $datosBitacora=[
                "Codigo"=>$codigoB,
                "Fecha"=>$fechaActual,
                "HoraInicio"=>$horaActual,
                "HoraFinal"=>"sin registro",
                "Tipo"=>$row['CuentaTipo'],
                "Year"=>$yearActual,
                "Cuenta"=>$row['CuentaCodigo']
            ];
            $insertarBitacora=mainModel::save_bitacora($datosBitacora);
            if ($insertarBitacora->rowCount()>= 1) {
                session_start(['name'=>'SBP']);
                $_SESSION['usuario_sbp']=$row['CuentaUsuario'];
                $_SESSION['tipo_sbp']=$row['CuentaTipo'];
                $_SESSION['privilegio_sbp']=$row['CuentaPrivilegio'];
                $_SESSION['foto_sbp']=$row['CuentaFoto'];
                $_SESSION['token_sbp']=md5(uniqid(mt_rand(),true));
                $_SESSION['codigo_cuenta_sbp']=$row['CuentaCodigo'];
                $_SESSION['codigo_bitacora_sbp']=$codigoB;

                if($row['CuentaTipo']=='Administrador'){
                    $url=SERVERURL."home/";
                    

                }else {
                    $url=SERVERURL."catalog/";
                    
                }
                return $urlLocation='<script> window.location="'.$url.'"</script>';

                
            } else {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrio un error Inesperado",
                    "Texto"=>"No pudimos iniciar sesión por problemas técnicos, por favor Intente Nuevamente ...",
                    "Tipo"=>"error"
                ];
                return mainModel::sweet_alert($alerta);
            }      

        } else {
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error Inesperado",
                "Texto"=>"El Usuario y Contraseña es Incorrecto o tu cuenta puede estar desahabilitada",
                "Tipo"=>"error"
            ];
            return mainModel::sweet_alert($alerta);
        }
        
    }
    public function close_Sesion_Controler(){
        session_start(['name'=>'SBP']);
        $token=mainModel::decryption($_GET['Token']);
        $hora=date("h:i:s:a");
        $datos=[
            "Usuario"=>$_SESSION['usuario_sbp'],
            "Token_S"=>$_SESSION['token_sbp'],
            "Token"=>$token,
            "Codigo"=>$_SESSION['codigo_bitacora_sbp'],
            "Hora"=>$hora
        ];
        return LoginModels::close_Sesion_Model($datos);

    }


    public function force_logut_controler(){
        session_destroy();
        return header("Location: ".SERVERURL."login/");//HEADER ES UNA FUNCION QUE REDICCIONA  A PARTIR DEL URL
    }
    
}