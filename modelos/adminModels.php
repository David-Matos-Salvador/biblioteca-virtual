<?php
if($peticionAjax){
    require_once "../core/mainModel.php";

}else{ require_once "./core/mainModel.php";
}
// extiende a main models para usar su clase connecion
class adminModels extends mainModel{
    protected function add_admin_model($datos){
        $sql=mainModel::Connecion()->prepare("INSERT INTO admin (AdminDNI,AdminNombre,AdminApellido,AdminTelefono,AdminDireccion,CuentaCodigo) 
        VALUES(:DNI,:Nombre,:Apellido,:Telefono,:Direccion,:Codigo)");
        $sql->bindParam(":DNI",$datos['DNI']);
        $sql->bindParam(":Nombre",$datos['Nombre']);
        $sql->bindParam(":Apellido",$datos['Apellido']);
        $sql->bindParam(":Telefono",$datos['Telefono']);
        $sql->bindParam(":Direccion",$datos['Direccion']);
        $sql->bindParam(":Codigo",$datos['Codigo']);
        $sql->execute();
        return  $sql;
    }

    protected function delete_admin_model($codigo){
        $query=mainModel::Connecion()->prepare("DELETE  FROM admin WHERE CuentaCodigo=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
        
    }
}