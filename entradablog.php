<?php 

include_once("api.php");

$contenido = obtenerContenidoEntradaBlog($_GET["id"]);

if (count($contenido) == 0) {
  header("location: index.php");
  exit();  
}

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
          <li class="breadcrumb-item active" aria-current="page cl-azul"><a class="cl-azul" href="menu.php?id=<?php echo $contenido[0]["idmenu"] ?>"><?php echo $contenido[0]["denmenu"] ?></a></li>
          <li class="breadcrumb-item active" aria-current="page cl-azul"><a class="cl-azul" href="categoriablog.php?id=<?php echo $contenido[0]["idcatblog"] ?>"><?php echo $contenido[0]["dencatblog"] ?></a></li>
          <li class="breadcrumb-item active" aria-current="page cl-azul"><?php echo $contenido[0]["titulo"] ?></li>
        </ol>
      </nav>
    </div>
    <!-- fin breadcrumb -->            
        

    <div class="container">

      <h2 class="cl-azul text-center display-4 bold mb-5"><?php echo $contenido[0]["titulo"] ?></h2>
      <div class="row">
        <div class="col-md-8">
          <?php 
            if ($contenido[0]["imagenPrincipal"] != NULL) {
              ?>
                <img width="100%" src="backend/uploads/<?php echo $contenido[0]["imagenPrincipal"] ?>" alt="">
              
              <?php
            }
          ?>
          <h6 class="cl-rojo mb-4"><?php echo fecha($contenido[0]["fechaModificacion"]) ?></h6>

          <?php echo $contenido[0]["contenido"] ?>

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