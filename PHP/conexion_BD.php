<?php
    $conexion = mysqli_connect("localhost", "root", "", "TiendaPuntos");

    
    if($conexion) {
        // echo "Conexion exitosa";
    } else {
        echo "Conexion fallida";
    }
    
?>