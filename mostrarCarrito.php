<?php
include 'global/config.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<br>
<h3>Lista de productos</h3>
<?php if(!empty($_SESSION['CARRITO'])) { ?>

<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="40%">Descripcion</th>
            <th width="15%" class="text-center">Cantidad</th>
            <th width="20%" class="text-center">Precio</th>
            <th width="20%" class="text-center">Total</th>
            <th width="5%">--</th>
        </tr>
        <?php $total=0; ?>
        <?php foreach($_SESSION['CARRITO'] as $indice=>$producto){?>
        
        <tr>
            <td width="40%"><?php echo $producto['NOMBRE'] ?></td>
            <td width="15%" class="text-center"><?php echo $producto['CANTIDAD'] ?></td>
            <td width="20%" class="text-center"><?php echo $producto['PRECIO'] ?></td>
            <td width="20%" class="text-center"><?php echo number_format( $producto['PRECIO']*$producto['CANTIDAD'],2) ?></td>
            <td width="5%">
                
            <form action="" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'],COD,KEY); ?>">

            <button class="btn btn-danger" type="submit" name="btnAccion" value="Eliminar">Eliminar</button>
            </form>
            

        </td>
        </tr>
        <?php $total=$total+($producto['PRECIO']*$producto['CANTIDAD']); ?>
        <?php } ?>
        <tr>
            <td><a class="btn btn-secondary btn-lg btn-block" href="inicio.php" role="button">Seguir comprando</a></td>
            <td colspan="2" align="right"><h3>Total</h3></td>
            <td align="right"><h3>$<?php echo number_format($total,2);?></h3></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="5">
                <form action="pagar.php" method="post">
                    <div class="alert alert-info" role="alert">

                    <div class="form-group">
                    <label for="my-input">Correo de contacto</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Escriba su correo" required>
                    </div>
                   <small id="emailHelp" class="form-text text-muted">
                        Se enviará un correo de confirmación.
                    </small>
                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="btnAccion" value="proceder">Proceder a pagar</button>
                    </div>
                </form>

            </td>
        </tr>
       
      
    </tbody>
</table>
<?php }else{ ?>

    <div class="alert alert-danger" role="alert">
        No hay productos en el carrito
    </div>
<?php }?>


<?php
include 'templates/pie.php';
?>