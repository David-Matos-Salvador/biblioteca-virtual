<?php
if($peticionAjax){

    require_once "../core/configApp.php";

}else{

    require_once "./core/configApp.php";
}

class mainModel{
    
        protected function Connecion(){
            $link= new PDO(SGBD,USER,PASS);
            return $link;

        }

        protected  function obtener_consulta_simple($query){
            $answer=self::Connecion()->prepare($query);
            $answer->execute();
            return $answer;
        }

        protected static function add_count($datos){
            $sql=self::Connecion()->prepare("INSERT INTO  cuenta(CuentaCodigo,CuentaPrivilegio,CuentaUsuario,CuentaClave,CuentaEmail,CuentaEstado,CuentaTipo,CuentaGenero,CuentaFoto) 
            VALUES  (:Codigo,:Privilegio,:Usuario,:Clave,:Email,:Estado,:Tipo,:Genero,:Foto)");
            $sql->bindParam(":Codigo",$datos['Codigo']);
            $sql->bindParam(":Privilegio",$datos['Privilegio']);
            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Clave",$datos['Clave']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":Tipo",$datos['Tipo']);
            $sql->bindParam(":Genero",$datos['Genero']);
            $sql->bindParam(":Foto",$datos['Foto']);
            $sql->execute();
            return $sql;                                                 
                                            

        }
        protected static function delete_count($codigo){
            $sql=self::Connecion()->prepare("DELETE FROM cuenta WHERE CuentaCodigo=:codigo");
            $sql->bindParam(":codigo",$codigo);
            $sql->execute();
            return $sql;
        }
        protected function save_bitacora($datos){

            $sql=self::Connecion()->prepare("INSERT INTO bitacora(BitacoraCodigo,BitacoraFecha,BitacoraHoraInicio,BitacoraHoraFinal,BitacoraTipo,BitacoraYear,
            CuentaCodigo) VALUES(:Codigo,:Fecha,:HoraInicio,:HoraFinal,:Tipo,:Year,:Cuenta)");
            $sql->bindParam(":Codigo",$datos['Codigo']);
            $sql->bindParam(":Fecha",$datos['Fecha']);
            $sql->bindParam(":HoraInicio",$datos['HoraInicio']); 
            $sql->bindParam(":HoraFinal",$datos['HoraFinal']);               
            $sql->bindParam(":Tipo",$datos['Tipo']);
            $sql->bindParam(":Year",$datos['Year']);
            $sql->bindParam(":Cuenta",$datos['Cuenta']);
            $sql->execute();
            return $sql;
        }
        protected function update_Bitacora($hora,$codigo){ 
            $sql=self::Connecion()->prepare("UPDATE bitacora SET BitacoraHoraFinal=:Hora WHERE BitacoraCodigo=:codigo");
            $sql->bindParam(":Hora",$hora);
            $sql->bindParam(":codigo",$codigo);
            $sql->execute();
            return $sql;
        }

        protected function delete_Bitacora($codigo){//se supone cuando se elimine una bitacora es porque el usuario se ha eliminado
            $sql=self::Connecion()->prepare("DELETE FROM bitacora WHERE CuentaCodigo=:codigo");
            $sql->bindParam(":codigo",$codigo);
            $sql->execute();
            return $sql;
        }

        public static function encryption($string){
            $output=FALSE;
            $key=hash('sha256', SECRET_KEY);
            $iv=substr(hash('sha256', SECRET_IV), 0, 16);
            $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output=base64_encode($output);
            return $output;
        }
        public static function decryption($string){
            $key=hash('sha256', SECRET_KEY);
            $iv=substr(hash('sha256', SECRET_IV), 0, 16);
            $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;
        }
        
        protected function  generar_codigo_aleatorio($letra,$longitud,$num){
            for ($i=1; $i<=$longitud ; $i++) { 
                $numero=rand(0,9);
                $letra.=$numero;                              
            }
            return $letra."-".$num;              
        }

        protected function limpiar_cadena($cadena){
            $cadena=trim($cadena);
            $cadena=stripcslashes($cadena);
            $cadena=str_ireplace("<script>","",$cadena);
            $cadena=str_ireplace("</script>","",$cadena);
            $cadena = str_ireplace("SELECT", "", $cadena);
            $cadena = str_ireplace("COPY", "", $cadena);
            $cadena = str_ireplace("DELETE", "", $cadena);
            $cadena = str_ireplace("DROP", "", $cadena);
            $cadena = str_ireplace("DUMP", "", $cadena);
            $cadena = str_ireplace(" OR ", "", $cadena);
            $cadena = str_ireplace("%", "", $cadena);
            $cadena = str_ireplace("LIKE", "", $cadena);
            $cadena = str_ireplace("--", "", $cadena);
            $cadena = str_ireplace("^", "", $cadena);
            $cadena = str_ireplace("[", "", $cadena);
            $cadena = str_ireplace("]", "", $cadena);
            $cadena = str_ireplace("\\", "", $cadena);
            $cadena = str_ireplace("!", "", $cadena);
            $cadena = str_ireplace("¡", "", $cadena);
            $cadena = str_ireplace("?", "", $cadena);
            $cadena = str_ireplace("=", "", $cadena);
            $cadena = str_ireplace("&", "",$cadena);

            return $cadena;
        }

        protected  function sweet_alert($datos){
            if ($datos['Alerta']=="simple") { 
                $alerta="
                <script>
                    swal(
                        '".$datos['Titulo']."',
                        '".$datos['Texto']."',
                        '".$datos['Tipo']."'
                    )
                </script>
                ";
            }elseif ($datos['Alerta']=="recargar") {
                $alerta="
                <script>
 
                swal({
                 title: '".$datos['Titulo']."',
                 text: '".$datos['Texto']."',
                 type: '".$datos['Tipo']."',                          
                  confirmButtonText: 'Aceptar'
               }).then(function(){
                location.reload();
               })
                </script>
                ";
            }
            
            elseif ($datos['Alerta']=="clear") { 
                $alerta="
                <script>
 
                swal({
                    title: '".$datos['Titulo']."',
                    text: '".$datos['Texto']."',
                    type: '".$datos['Tipo']."',                          
                     confirmButtonText: 'Aceptar'
                  }).then(function(){
                   $('.FormularioAjax')[0].reset();
                  })
                </script>
                ";

            }
            return $alerta;

        } 
        
    }
