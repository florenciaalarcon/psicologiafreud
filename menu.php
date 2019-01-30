<?php 

include_once("api.php");

$contenido = obtenerDatosMenu($_GET["id"]);

if (count($contenido) == 0) {
  header("location: index.php");
  exit();
}

$itemsmenu = obtenerItemsMenu($_GET["id"]);

 ?>

<!DOCTYPE html>
<html lang="es">

<head>

  <?php head() ?>

</head>

<body>

  <!-- navegador -->
    <?php navegador() ?>
  <!-- fin navegador -->
      
    </div>

  <!-- paginas -->

  <section class="titulo-pagina">

    <!-- breadcrumb -->
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home cl-gris"></i></a></li>
          <li class="breadcrumb-item active" aria-current="page cl-azul"><?php echo $contenido[0]["denominacion"] ?></li>
        </ol>
      </nav>
    </div>
    <!-- fin breadcrumb -->            

    <div class="container">
      <h2 class="cl-azul text-center display-4 bold mb-5"><?php echo $contenido[0]["denominacion"] ?></h2>
      <div class="row">

        <div class="col-md-8">

          <ul>

              <?php

                if (count($itemsmenu) == 0) {
                  ?>
                    <h1>No hay Items</h1>
                  <?php   
                }else{
                  foreach ($itemsmenu as $key => $value) {
                    ?>
                      
                      <li><a class="cl-azul" href="<?php echo $value["tipo"] == "estatica" ? 'paginaestatica.php?id='.$value["id"]:'categoriablog.php?id='.$value["id"] ?>"><?php echo $value["denominacion"] ?></a></li>

                    <?php
                  }
                } 

              ?>
            
          </ul>


        </div>

        <div class="col-md-4">

          <?php ultimasEntradas() ?>

        </div>

      </div>

    </div>

  </section>

  <!-- paginas -->

  <!-- footer -->

  <?php footer() ?>

  <!-- fin footer -->

</body>

<?php scripts() ?>

</html>