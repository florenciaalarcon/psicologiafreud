<?php 

include_once("api.php");

if (!isset($_GET["q"])) {
  header("location: index.php");
  exit();    
}

$entradas = obtenerResultadoBusqueda($_GET["q"]);

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
      
  <!-- paginas -->

  <section class="titulo-pagina">

    <!-- breadcrumb -->
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home cl-gris"></i></a></li>
          <li class="breadcrumb-item active" aria-current="page cl-azul">Resultado de la Búsqueda: <?php echo $_GET["q"] ?></li>
        </ol>
      </nav>
    </div>
    <!-- fin breadcrumb -->            

    <div class="container">
      <h2 class="cl-azul text-center display-4 bold mb-5">Resultado de la Búsqueda: <?php echo $_GET["q"] ?></h2>
      <div class="row">

        <div class="col-md-8">

          <?php

            if (count($entradas) == 0) {
              ?>
                <h1>No hay Resultados que coincidan</h1>
              <?php   
            }else{
              foreach ($entradas as $key => $value) {
                ?>

                  <div class="row">

                    <?php 

                      if ($value["imagenPrincipal"] != NULL) {

                        ?>
                          <div class="col-md-4 mb-5">
                            <div class="inner-cuadricula" style="background-image:url('backend/uploads/<?php echo $value["imagenPrincipal"] ?>')"></div>
                          </div>

                          <div class="col-md-8">
                            <h4 class="bold mt-3"><?php echo $value["titulo"] ?></h4>
                            <h6 class="cl-rojo mb-4"><?php echo fecha($value["fechaModificacion"]) ?></h6>
                            <p><?php echo substr(strip_tags($value["contenido"]), 0,50)."..." ?></p>
                            <a class="cl-azul" href="<?php echo $value["tipo"] == "estatica" ? 'paginaestatica.php?id='.$value["id"]:'categoriablog.php?id='.$value["id"] ?>">Leer más...</a>
                          </div>
                          
                        <?php
                        
                      }else{
                        ?>
                          <div class="col-12">
                            <h4 class="bold"><?php echo $value["titulo"] ?></h4>
                            <h6 class="cl-rojo mb-4"><?php echo fecha($value["fechaModificacion"]) ?></h6>
                            <p><?php echo substr(strip_tags($value["contenido"]), 0,50)."..." ?></p>
                            <a class="cl-azul" href="<?php echo $value["tipo"] == "estatica" ? 'paginaestatica.php?id='.$value["id"]:'categoriablog.php?id='.$value["id"] ?>">Leer más...</a>
                          </div>                    
                        <?php
                      }

                    ?>

                  </div>

                <?php
              }
            } 

          ?>

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