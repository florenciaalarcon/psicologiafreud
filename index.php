<?php 

include_once("api.php");

if (isset($_POST["contacto-submit"])) {
  $para      = 'freudI@psi.uba.ar';
  $titulo    = 'Nuevo Contacto desde nuestro sitio web';
  $mensaje   = 'Nombre: '.$_POST["contacto-nombre"].' Mensaje: '.$_POST["contacto-mensaje"];
  $cabeceras = 'From: '.$_POST["contacto-email"] . "\r\n" .
      'Reply-To: '.$_POST["contacto-email"] . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

  mail($para, $titulo, $mensaje, $cabeceras);  
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

  <!-- slider -->

  <header id="home">

      <div id="slider-principal" class="carousel slide" data-ride="carousel">

        <ol class="carousel-indicators">

          <?php 

            $slider = obtenerSlider();

            foreach ($slider as $key => $value) {
              ?>

                <li data-target="#slider-principal" data-slide-to="<?php echo $key ?>" class="<?php echo $key == 0?"active":"" ?>"></li>
              
              <?php 
            }

          ?>

        </ol>

        <div class="carousel-inner" role="listbox">

          <?php 

            foreach ($slider as $key => $value) {
              ?>

                <div class="carousel-item <?php echo $key == 0?"active":"" ?>" style="background-image: url('backend/uploads/<?php echo $value["imagen"] ?>')" data-toggle="tooltip" data-placement="left" data-html="true" title="<?php echo $value["informacion"] ?>">
                  <div class="carousel-caption d-none d-md-block">
                    <h3 class="display-2 bold"><?php echo $value["titulo"] ?></h3>
                    <p class="lead"><?php echo $value["subtitulo"] ?></p>
                    <a href="<?php echo $value["link"] ?>" class="btn btn-primary">Ver más</a>
                  </div>
                </div>
              
              <?php 
            }           

          ?>
          
        </div>

        <a class="carousel-control-prev" href="#slider-principal" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Anterior</span>
        </a>

        <a class="carousel-control-next" href="#slider-principal" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Siguiente</span>
        </a>

      </div>
    </header>

  <!-- fin slider -->

    <!-- circulos -->

    <?php accesosDirectos() ?>


  <!-- fin circulos -->

  <!-- llamado -->
<!--
  <section class="bg-azul py-5">
    <div class="container">
      <h3 class="cl-blanco text-center display-3 bold">Título</h3>
      <p class="cl-blanco text-center my-5 lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime consectetur sit sunt fuga, delectus id omnis voluptate dignissimos ratione vero aliquam ea eum dolorum voluptas quidem perferendis, reiciendis excepturi et.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas expedita animi maxime voluptatum pariatur, in voluptates qui, voluptate ea accusamus nemo quo perferendis quasi molestias cumque quia sed aut earum! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus ipsam perspiciatis veritatis maxime consequatur et eos pariatur accusantium voluptates, dolores tempore sunt quas repellendus suscipit iusto totam atque dolorum autem. 
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore aliquid eaque quasi sint cumque nam magnam accusamus excepturi beatae et officiis, adipisci debitis, omnis, aperiam deleniti obcaecati tempora labore. Recusandae.</p>
    </div>
  </section>
-->
  <!-- fin llamado -->

  <!-- blog -->

  <?php ultimasEntradasHome() ?>

  <!-- fin blog -->

  <!-- formulario contacto -->
  <section class="bg-gris py-5">
    <div class="container"> 
      <h2 class="cl-blanco text-center display-4 bold mb-2">Contacto</h2>
      <p class="cl-blanco text-center mb-5">Envianos un mensaje</p>

      <form class="form-horizontal" method="POST">
        <fieldset>
          <div class="row">
            <!-- Text input-->
            <div class="col-md-6">
              <div class="form-group">
                <input id="contacto-nombre" name="contacto-nombre" type="text" placeholder="Nombre" class="form-control input-md"> 
              </div>
            </div>

            <!-- Text input-->
            <div class="col-md-6">
              <div class="form-group">
                <input id="contacto-email" name="contacto-email" type="text" placeholder="Email" class="form-control input-md"> 
              </div>
            </div>
          </div>

          <!-- Textarea -->
          <div class="form-group">
            <textarea class="form-control" id="contacto-mensaje" name="contacto-mensaje" placeholder="Mensaje"></textarea>
          </div>

          <div class="form-group btn-azul text-center">
            <input id="contacto-submit" name="contacto-submit" type="submit" class="btn bg-azul cl-blanco">
          </div>
        </fieldset>
      </form>
    </div>
  </section>

  <!-- fin formulario contacto -->

  <!-- redes sociales -->

  <section class="m-3">
    <div class="row">
      <div class="col-12 text-center cl-azul">
        <a class="cl-azul" target="_blank" href="https://www.facebook.com/Freud1Delgado"><i class="fab fa-3x mx-2 fa-facebook-f"></i></a>
        <a class="cl-azul" target="_blank" href="https://twitter.com/Freud1UBA"><i class="fab fa-3x mx-2 fa-twitter"></i></a>
        <a class="cl-azul" target="_blank" href="https://www.instagram.com/psicoanalisisfreud1/"><i class="fab fa-3x mx-2 fa-instagram"></i></a>
      </div>
    </div>
  </section>

  <!-- fin redes sociales -->

  <!-- footer -->

  <?php footer() ?>

  <!-- fin footer -->

</body>

  <?php scripts() ?>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>  

</html>
