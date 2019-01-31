<?php 

include_once("api.php");

$contenido = obtenerCategoriaBlog($_GET["id"]);

if (count($contenido) == 0) {
  header("location: index.php");
  exit();
}

$entradas = obtenerEntradasBlog($_GET["id"]);

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
          <li class="breadcrumb-item active"><a class="cl-azul" href="menu.php?id=<?php echo $contenido[0]["idmenu"] ?>"> <?php echo $contenido[0]["denominacionmenu"] ?></a></li>
          <li class="breadcrumb-item active" aria-current="page cl-azul"><?php echo $contenido[0]["denominacion"] ?></li>
        </ol>
      </nav>
    </div>
    <!-- fin breadcrumb -->            

    <div class="container">
      <h2 class="cl-azul text-center display-4 bold mb-5"><?php echo $contenido[0]["denominacion"] ?></h2>
      <div class="row">

        <div class="col-md-8">

          <?php

            if (count($entradas) == 0) {
              ?>
                <h1>No hay entradas</h1>
              <?php   
            }else{
              foreach ($entradas as $key => $value) {
                ?>

                  <div class="row mb-5">

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
                            <a class="cl-azul" href="entradablog.php?id=<?php echo $value["id"] ?>">Leer más...</a>
                          </div>
                          
                        <?php
                        
                      }else{
                        ?>
                          <div class="col-12">
                            <h4 class="bold"><?php echo $value["titulo"] ?></h4>
                            <h6 class="cl-rojo mb-4"><?php echo fecha($value["fechaModificacion"]) ?></h6>
                            <p><?php echo substr(strip_tags($value["contenido"]), 0,50)."..." ?></p>
                            <a class="cl-azul" href="entradablog.php?id=<?php echo $value["id"] ?>">Leer más...</a>
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