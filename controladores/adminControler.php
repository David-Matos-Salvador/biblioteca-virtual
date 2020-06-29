<?php
if($peticionAjax){
    require_once "../modelos/adminModels.php";

}else{ require_once "./modelos/adminModels.php";}

class adminControler extends adminModels{
    // controlador para  agregar administrador y la cuenta del administrador
    public function add_admin_controler(){        
        $dni=mainModel::limpiar_cadena($_POST['dni-reg']);
        $nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
        $apellido=mainModel::limpiar_cadena($_POST['apellido-reg']);
        $telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
        $dirrecion=mainModel::limpiar_cadena($_POST['direccion-reg']);
        $usuario=mainModel::limpiar_cadena($_POST['usuario-reg']);
        $password1=mainModel::limpiar_cadena($_POST['password1-reg']);
        $password2=mainModel::limpiar_cadena($_POST['password2-reg']);
        $email=mainModel::limpiar_cadena($_POST['email-reg']);
        $genero=mainModel::limpiar_cadena($_POST['optionsGenero']);

        $privilegio=mainModel::decryption($_POST['optionsPrivilegio']);
        $privilegio=mainModel::limpiar_cadena($privilegio);

        if($genero="Masculino"){
            $foto="TeacherMaleAvatar.png";
        }
        else{
            $foto="StudetFemaleAvatar.png";
        }
        if ($privilegio<1 || $privilegio>3) {
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error Inesperado",
                "Texto"=>"El nivel de privilegio que intenta asignar es incorecto",
                "Tipo"=>"error"
            ];
        } else {
            if ($password1!=$password2) {
    
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrio un error Inesperado",
                    "Texto"=>"Las Contraseñas que acabas de ingresar no coinciden, por favor  intente nuevamente",
                    "Tipo"=>"error"
                ];
            }else {
                $consulta=mainModel::obtener_consulta_simple("SELECT AdminDNI FROM admin WHERE AdminDNI='$dni'");
                if ($consulta->rowCount()>=1) {
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrio un error Inesperado",
                        "Texto"=>"El DNI se encuentra en el sistema",
                        "Tipo"=>"error"
                    ];
                }else {
                    if ($email!="") {
                        $consulta2=mainModel::obtener_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$email' ");
                        $ec=$consulta2->rowCount();
                    }else {
                        $ec=0;
                    }
            
                    if($ec>=1){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrio un error Inesperado",
                            "Texto"=>"El EMAIL ya se encuentra Registrado",
                            "Tipo"=>"error"
                        ];
                        
                    }else{
                        $consulta3=mainModel::obtener_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
                        if ($consulta3->rowCount()>=1) {
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrio un error Inesperado",
                                "Texto"=>"El usuario ya se encuentra Registrado",
                                "Tipo"=>"error"
                            ];
                        } else {
                            $consulta4=mainModel::obtener_consulta_simple("SELECT id FROM cuenta");
                            $numero=($consulta4->rowCount())+1;
            
                            $codigo=mainModel::generar_codigo_aleatorio("AC",7,$numero);
            
                            $clave=mainModel::encryption($password1);
            
                            $dataAC=[   
                                                    
                                "Codigo"=>$codigo,
                                "Privilegio"=>$privilegio,
                                "Usuario"=>$usuario,
                                "Clave"=>$clave,
                                "Email"=>$email,
                                "Estado"=>"Activo",
                                "Tipo"=>"Administrador",
                                "Genero"=>$genero,
                                "Foto"=>$foto
                            ];
                            $saveCount=mainModel::add_count($dataAC);
            
                            if ($saveCount->rowCount()>=1) {
                                $dataAD=[
                                    "DNI"=>$dni,
                                    "Nombre"=>$nombre,
                                    "Apellido"=>$apellido,
                                    "Telefono"=>$telefono, 
                                    "Direccion"=>$dirrecion, 
                                    "Codigo"=>$codigo                                                            
                                ];
                                $guardarAdmin=adminModels::add_admin_model($dataAD);
                                if ($guardarAdmin->rowCount()>=1) {
                                    $alerta=[
                                        "Alerta"=>"clear",
                                        "Titulo"=>"Administrador Registrado",
                                        "Texto"=>"El Administrador se registro con exito en el sistema",
                                        "Tipo"=>"success"
                                    ];
                                        
                                }else {
            
                                    mainModel::delete_count($codigo);                                
                                    $alerta=[
                                        "Alerta"=>"simple",
                                        "Titulo"=>"Ocurrio un error Inesperado",
                                        "Texto"=>"No se pudo Registrar el Administrado GuardarAdmin   ",
                                        "Tipo"=>"error"
                                    ];
                                    
                                }
                            
                            } else {
                                $alerta=[
                                    "Alerta"=>"simple",
                                    "Titulo"=>"Ocurrio un error Inesperado",
                                    "Texto"=>"No se pudo registrar  la Cuenta Administrador addCount",
                                    "Tipo"=>"error"
                                ];
                            }
                            
                            
                        }
                        
                    }
                    
                }
                
            }   
        }
        


            
        return mainModel::sweet_alert($alerta);
    }

    //controlador para paginar administrador
    public function paginador_administrador_controler($pagina,$registros,$privilegio,$codigo){
        $pagina=mainModel::limpiar_cadena($pagina);
        $registros=mainModel::limpiar_cadena($registros);
        $privilegio=mainModel::limpiar_cadena($privilegio);
        $codigo=mainModel::limpiar_cadena($codigo);
        $tabla="";

        $pagina=(isset($pagina) && $pagina>0) ? (int) $pagina  : 1;
        $inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        $conexion = mainModel::Connecion();

        $datos = $conexion->query("SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE CuentaCodigo!='$codigo'
        AND id!='1' ORDER BY AdminNombre ASC LIMIT $inicio,$registros
        ");
        $datos=$datos->fetchAll();

        $total=$conexion->query("SELECT FOUND_ROWS()");
        $total= (int) $total->fetchColumn();

        $Npaginas=ceil($total/$registros);

        $tabla.='<div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">DNI</th>
                        <th class="text-center">NOMBRES</th>
                        <th class="text-center">APELLIDOS</th>
                        <th class="text-center">TELÉFONO</th>';
                        if ($privilegio<=2) {
                            $tabla.='  <th class="text-center">A. CUENTA</th>
                            <th class="text-center">A. DATOS</th>'; 
                        }
                        if ($privilegio==1) {
                            $tabla.='
                        <th class="text-center">ELIMINAR</th>';
                        }
        $tabla.=' </tr>
                </thead>
                <tbody>';

        if ($total>1 && $pagina<=$Npaginas) {

            $contador=$inicio+1;
            foreach($datos as $rows){
                $tabla.='
                <tr>
                <td>'.$contador.'</td>
                <td>'.$rows['AdminDNI'].'</td>
                <td>'.$rows['AdminNombre'].'</td>
                <td>'.$rows['AdminApellido'].'</td>
                <td>'.$rows['AdminTelefono'].'</td>';
                if ($privilegio<=2) {

                $tabla.='
                <td>
                    <a href="'.SERVERURL.'myaccount/admin/'.mainModel::encryption($rows['CuentaCodigo']).'/"
                     class="btn btn-success btn-raised btn-xs">
                        <i class="zmdi zmdi-refresh"></i>
                    </a>
                </td>
                <td>
                    <a href="'.SERVERURL.'mydata/admin/ '.mainModel::encryption($rows['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
                        <i class="zmdi zmdi-refresh"></i>
                    </a>
                </td>';
                    
                }
                if ($privilegio==1) {
                    $tabla.='
                        <td>
                            <form action="'.SERVERURL.'ajax/admin_ajax.php" method="POST" 
                                class="FormularioAjax" data-form="delete" entype="multipar/form-data"
                                autocomplete="off">
                                <input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['CuentaCodigo']).'">
                                <input type="hidden" name="privilegio-admin" value="'.mainModel::encryption($privilegio).'">
                                <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                    <i class="zmdi zmdi-delete"></i>
                                </button>
                                <div class="RespuestaAjax"></div>
                            </form>
                        </td>';}
                
            $tabla.='</tr>';
            $contador++;
            }
            
        } else {
            if ($total>=1) {
                $tabla.='
                    <tr>
                        <td colspan="5">
                        <a href="'.SERVERURL.'adminlist/" class="btn btn-sm btn-info btn-raised">
                        Haga click para recargar el listado</a>
                        </td>
                    </tr>
            ';
            } else {
                $tabla.='
                    <tr>
                        <td colspan="5">No hay Registros en el Sistema</td>
                    </tr>
            ';
            }           
            
        }                    

        $tabla.='</tbody></table></div>';

        if ($total>1 && $pagina<=$Npaginas) {
            $tabla.='<nav class="text-center"><ul class="pagination pagination-sm"> ';
            if ($pagina==1) {
                $tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>
                ';
            } else {
                $tabla.='<li><a href="'.SERVERURL.'adminlist/'.($pagina-1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>'; 
            }

            for ($i=1; $i<=$Npaginas; $i++) { 
                if($pagina==$i){
                    $tabla.='<li class="active"><a href="'.SERVERURL.'adminlist/'.$i.'/">'.$i.'</a></li>';
                }else{
                    $tabla.='<li><a href="'.SERVERURL.'adminlist/'.$i.'/">'.$i.'</a></li>';
                }
            }

            
            if ($pagina==$Npaginas) {
                $tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>
                ';
            } else {
                $tabla.='<li><a href="'.SERVERURL.'adminlist/'.($pagina+1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>'; 
            }


            $tabla.='</ul></nav>';


        }


        return $tabla;
    }

    //eliminar administrador
    
    public function delete_admin_Controler(){
        
        $codigo=mainModel::decryption($_POST['codigo-del']);
        $adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

        $codigo=mainModel::limpiar_cadena($codigo);
        $adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

        if($adminPrivilegio==1){
            $query1=mainModel::obtener_consulta_simple("SELECT id FROM admin WHERE CuentaCodigo='$codigo'");
            $datosAdmin=$query1->fetch();
            if($datosAdmin['id']!=1){
                
                $delAdmin=adminModels::delete_admin_model($codigo);
                mainModel::delete_Bitacora($codigo);
                if($delAdmin->rowCount()>=1){
                     
                    $delCuenta=mainModel::delete_count($codigo);
                    if($delCuenta->rowCount()==1){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Administrador Eliminado",
                            "Texto"=>"Se eliminó correctamenta",
                            "Tipo"=>"success"
                        ];  
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrio un error Inesperado",
                            "Texto"=>"No podemos eliminar esta cuenta en este momento1",
                            "Tipo"=>"error"
                        ];   
                    }

                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrio un error Inesperado",
                        "Texto"=>"No podemos eliminar el administrador en este momento2",
                        "Tipo"=>"error"
                    ];     
                }

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrio un error Inesperado",
                    "Texto"=>"No podemos eliminar el administrador principal del sistema
                    ",
                    "Tipo"=>"error"
                ];
            }

        }else{
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error Inesperado",
                "Texto"=>"tu no tienes los permisos necesarios para realizar esta operación ",
                "Tipo"=>"error"
            ];
            
        }
        return mainModel::sweet_alert($alerta);
    }
}
