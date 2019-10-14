<?php
session_start();


$mensaje="";
if (isset($_POST['btnAccion'])){

    switch($_POST['btnAccion']){

        case 'Agregar':

        if(is_numeric( openssl_decrypt( $_POST['id'],COD,KEY))){
            $ID=openssl_decrypt($_POST['id'],COD,KEY);
            $mensaje.="Ok ID correcto".$ID."<br/>";

        }else{
            $mensaje.="ID incorrecto".$ID."<br/>";
        }

        if(is_string( openssl_decrypt( $_POST['nombre'],COD,KEY))){
            $NOMBRE=openssl_decrypt($_POST['nombre'],COD,KEY);
            $mensaje.="Ok NOMBRE correcto".$NOMBRE."<br/>";

        }else{
            $mensaje.="Nombre incorrecto".$NOMBRE."<br/>";
    
        }

        if(is_numeric( openssl_decrypt( $_POST['cantidad'],COD,KEY))){
            $CANTIDAD=openssl_decrypt($_POST['cantidad'],COD,KEY);
            $mensaje.="Ok Cantidad".$CANTIDAD."<br/>";
            
        }else{
            $mensaje.="Cantidad incorrecta"."<br/>";
            
        }

        if(is_numeric( openssl_decrypt( $_POST['precio'],COD,KEY))){
            $PRECIO=openssl_decrypt($_POST['precio'],COD,KEY);
            $mensaje.="Ok Precio".$PRECIO."<br/>";
        }else{
            $mensaje.="Precio incorrecto"."<br/>";
        }

        //Otiene toda la información que el usuario esta mandando a traves de POST
        if(!isset($_SESSION['CARRITO'])){
            $producto=array(
                'ID'=>$ID,
                'NOMBRE'=>$NOMBRE,
                'CANTIDAD'=>$CANTIDAD,
                'PRECIO'=>$PRECIO,
                
            );
            $_SESSION['CARRITO'][0]=$producto;
            $mensaje= "Producto agregado al carrito";

        }else{
//Si hay producto da la opción de agregarlo otra vez
            $idProductos=array_column($_SESSION['CARRITO'],"ID");
           

            $NumeroProductos=count($_SESSION['CARRITO']);

            $producto=array(
                'ID'=>$ID,
                'NOMBRE'=>$NOMBRE,
                'CANTIDAD'=>$CANTIDAD,
                'PRECIO'=>$PRECIO,
                
            );
            $_SESSION['CARRITO'][$NumeroProductos]=$producto;
            $mensaje= "Producto agregado al carrito";
            
            
        }
        //$mensaje= print_r($_SESSION,true);
        

        break;
        case "Eliminar":
        //modificar para que elimine un único elemento
        if(is_numeric( openssl_decrypt( $_POST['id'],COD,KEY))){
            $ID=openssl_decrypt($_POST['id'],COD,KEY);
            $mensaje.="Ok ID correcto".$ID."<br/>";

            foreach($_SESSION['CARRITO'] as $indice=>$producto){

                if($producto['ID']==$ID){
                    unset($_SESSION['CARRITO'][$indice]);
                    echo "<script>alert('Elemento borrado');</script>";

                }
            }


        }else{
            $mensaje.="ID incorrecto".$ID."<br/>";
        }
     

        break;

    }


}

?>