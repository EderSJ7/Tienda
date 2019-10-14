<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>
<?php 

//print_r($_GET);


    $Login=curl_init(LINKAPI."/v1/oauth2/token");
    //Relación de la variable Login para que la api nos devuelva la informción
    curl_setopt($Login, CURLOPT_RETURNTRANSFER,TRUE);
        //Se pide el cliete y el Secret con dos puntos intermediarios
    curl_setopt($Login,CURLOPT_USERPWD,CLIENTID.":".SECRET);
        //SOLICITAMOS LA INFORMACIÓN VIA POST DICIENDOLE QUE NOS DE TODAS LAS CREDENCALES QUE UTILIZA EL CLIENTID
    curl_setopt($Login,CURLOPT_POSTFIELDS,"grant_type=client_credentials");

        //Ejecutar los curl
    $Respuesta=curl_exec($Login);
     

    $objRespuesta=json_decode($Respuesta);
    //Al imprimir la respuesta decodificamos el Json y se convierte en objeto para guardar en una variable en una variable accesss_token
    $AccessToken=$objRespuesta->access_token;

    //¡¡¡Es conveniente actualizar a v2!!!
    $venta= curl_init(LINKAPI."/v1/payments/payment/".$_GET['paymentID']);

    //Toda la información de la venta
    curl_setopt($venta,CURLOPT_HTTPHEADER,array("Content-Type: application/json","Authorization: Bearer ".$AccessToken));

    curl_setopt($venta, CURLOPT_RETURNTRANSFER,TRUE);

    $RespuestaVenta=curl_exec($venta);

    //print_r($RespuestaVenta);

    $objDatosTransaccion=json_decode($RespuestaVenta);

    //Obtener unicamente el email
   // print_r($objDatosTransaccion->payer->payer_info->email);

    $state=$objDatosTransaccion->state;
    //guarda en una variable "$email" el correo electrónico(email)
    $email=$objDatosTransaccion->payer->payer_info->email;

    $total = $objDatosTransaccion->transactions[0]->amount->total;

    $currency = $objDatosTransaccion->transactions[0]->amount->currency;

    $custom = $objDatosTransaccion->transactions[0]->custom;
    
 //   print_r($custom);

    $clave=explode("#",$custom);
    $SID=$clave[0];
    $claveVenta=openssl_decrypt($clave[1],COD,KEY);

    curl_close($venta);
    curl_close($Login);


    if ($state=="approved"){
        $mensajePaypal="<h3>Pago aprobado</h3>";

        $sentencia=$pdo->prepare("UPDATE `tblventas` 
        SET `PaypalDatos` = :PaypalDatos, `status` = 'aprobado' 
        WHERE `tblventas`.`ID` = :ID;");

        $sentencia->bindParam(":ID",$claveVenta);

        $sentencia->bindParam(":PaypalDatos",$RespuestaVenta); 

        $sentencia->execute();


        //Cambia a 'completo' cuando ya se aprobo el  pago y concuerdan todos los datos

        $sentencia=$pdo->prepare("UPDATE tblventas SET status='completo'
        WHERE ClaveTransaccion=:ClaveTransaccion
        AND Total=:TOTAL
        AND ID=:ID");

        $sentencia->bindParam(':ClaveTransaccion',$SID);

        $sentencia->bindParam(':TOTAL',$total);

        $sentencia->bindParam(':ID',$claveVenta);

        $sentencia->execute();


        $completado=$sentencia->rowCount();

      // session_destroy();

    }else{
        $mensajePaypal="<h3>Error al pagar</h3>";
    }


?>

<div class="jumbotron">

    <h1 class="display-4">¡Compra realizada con éxito!</h1>

    <p class="lead"><?php echo $mensajePaypal; ?></p>

    <hr class="my-4">
    

    <p class="lead">



        <?php 
if($completado>=1){
    

$sentencia=$pdo->prepare("SELECT * FROM tbldetalleventa,tblproductos WHERE tbldetalleventa.IDPRODUCTO=tblproductos.ID AND tbldetalleventa.IDVENTA=:ID");


        $sentencia->bindParam(':ID',$claveVenta);

        $sentencia->execute();

        $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
       // print_r($listaProductos);
       session_destroy();

}else{

    echo "<script> alert('¡ERROR AL PROCESAR EL PAGO!')</script>";
    
    echo "<html>
    <head>
    <meta http-equiv='Refresh' content='0;url=mostrarCarrito.php'>
    </head>
    <body>
    </body>
    </html>";

}
/*////////////////////////////////////////////////////////////////////////////////////////////////////*/

?>


<div class="row">
            <?php foreach($listaProductos as $producto){ ?>
            <div class="col-3">
            <div class="card">
                <img class="card-img-top" src="<?php echo $producto['Imagen'];?>">
                <div class="card-body">

                <p class="card-text"><?php echo $producto['Nombre'];?></p>
                    

                </div>
            </div>

            </div>
            <?php } ?>
        </div>
    </p>
    <a class="btn btn-success btn-lg btn-block" href="inicio.php" role="button">Regresar al inicio </a>

</div>

<?php 
include 'templates/pie.php';
?>