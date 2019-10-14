<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php
    if($_POST){
        $total=0;
        $SID=session_id();
        $Correo=$_POST['email'];
        //lee toda la información del carrito de compras    
        foreach($_SESSION['CARRITO'] as $indice=>$producto){

            $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);
    }

    $sentencia=$pdo->prepare("INSERT INTO `tblventas` (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) VALUES (NULL, :ClaveTransaccion, '', NOW(), :Correo, :Total, 'pendiente')");

    $sentencia->bindParam(":ClaveTransaccion",$SID);
    $sentencia->bindParam(":Correo",$Correo);
    $sentencia->bindParam(":Total",$total);
    $sentencia->execute();
    $idVenta=$pdo->lastInsertId();


    foreach($_SESSION['CARRITO'] as $indice=>$producto){
//Insertar la información que el usuario está seleccionando o sea la información de los productos de la tabla de ventas
     $sentencia=$pdo->prepare("INSERT INTO `tbldetalleventa` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) VALUES (NULL, :IDVENTA, :IDPRODUCTO, :PRECIOUNITARIO, :CANTIDAD, '0');");

     $sentencia->bindParam(":IDVENTA",$idVenta);
     $sentencia->bindParam(":IDPRODUCTO",$producto['ID']);
     $sentencia->bindParam(":PRECIOUNITARIO",$producto['PRECIO']);
     $sentencia->bindParam(":CANTIDAD",$producto['CANTIDAD']);
     
     $sentencia->execute();

     
    }


}
?>


<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<style>
   
    /* Media query for mobile viewport */
    @media screen and (max-width: 400px) {
        #paypal-button-container {
           width: 100%;
        }
    }
   
    /* Media query for desktop viewport */
    @media screen and (min-width: 400px) {
        #paypal-button-container {
           width: 250px;
            display: inline-block;
        }
    }
   
</style>

<div class="jumbotron text-center">
    <h1 class="display-4">PAGO</h1>
    <p class="lead"> <h5>La cantidad a pagar es:</h5>

         <h4>$<?php echo number_format($total,2);?></h4>   
             <!-- Set up a container element for the button -->
        <div id="paypal-button-container"></div>
 
    </p>
    <hr class="my-4">
</div>




<script>
    paypal.Button.render({
        env: 'sandbox', // sandbox | production
        style: {
            label: 'checkout',  // checkout | credit | pay | buynow | generic
            size:  'responsive', // small | medium | large | responsive
            shape: 'pill',   // pill | rect
            color: 'gold'   // gold | blue | silver | black
        },
 
        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create
 
        client: {
            sandbox:    'AY5gozTvfNnJUtSQPZ4Ex9EbS9LY_scZVc9Ccs0mbQfd_HRnnkrimlrwRU_WusrQezy6fWlF9nQ1mgAP',
            production: 'AfkynWnfITe2-wELz20mkcOuuF0ClFluqS9MBC1HOeLmiyji0t76YgD_9GAkPUZ2eAVOPHTG8EUb5fqz'
        },
 
        // Wait for the PayPal button to be clicked
 
        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '0.01', currency: 'MXN' },
                            description:"Compra de productos a Sunsure<?php echo number_format($total,2); ?>",
                            custom:"<?php echo $SID;?>#<?php echo openssl_encrypt($idVenta,COD,KEY); ?>"
                        }
                    ]
                }
            });
        },
 
        // Wait for the payment to be authorized by the customer
 
        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
                console.log(data);
                window.location="verificador.php?paymentToken="+data.paymentToken+"&paymentID="+data.paymentID;
            
            });
        }
   
    }, '#paypal-button-container');
 
</script>
    

    

<?php 
include 'templates/pie.php';
?>