<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>
<div class="container">
            <img src="../img/logo.png" alt="Sensure" id="image" class="img-fluid">
            
    </div>
        <?php if ($mensaje!="") {?>
        <div class="alert alert-success" role="alert">
            <?php echo $mensaje; ?>
            <a href="mostrarCarrito.php" class="badge badge-succes">Ver carrito</a>
        </div>
        <?php }?>

        <div class="row">
            <?php
                $sentencia=$pdo->prepare("SELECT * FROM `tblproductos`");
                $sentencia->execute();
                $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
               // print_r($listaProductos);
            ?>
            <?php foreach($listaProductos as $producto) { ?>
                <div class="col-3">
                <div class="card">
                    <img
                    title="<?php echo $producto['Nombre'];?>"
                    alt="<?php echo $producto['Nombre'];?>"
                    class="card-img-top rounded" 
                    src="<?php echo $producto['Imagen'];?>" 
                    data-toggle="popover"
                    data-trigger="hover"
                    data-content="<?php echo $producto['Descripcion'];?>"
                    height="317px auto";
                    >
                    <div class="card-body">
                        <span><?php echo $producto['Nombre']; ?></span>
                        <h5 class="card-title">$<?php echo $producto['Precio'];?></h5>
                        <p class="card-text">Descripción</p>
                        
                        <form action="" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'],COD,KEY); ?>">
                            <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['Nombre'],COD,KEY); ?>">
                            <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['Precio'],COD,KEY); ?>">
                            <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY); ?>">

                        <button class="btn btn-primary" name="btnAccion" value="Agregar" type="submit">Agregar al carrito</button>

                        </form>

                        
                    </div>
                </div>
            </div>

            <?php } ?>
        
        </div>

    </div>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
});
</script>
<?php
include 'templates/pie.php';
?>