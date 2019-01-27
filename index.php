<?php 

include_once("api.php");

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

  <section class="m-3 py-5">
    <div class="row">
      <div class="col-8 offset-2">
        <div class="row">
          <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Construcción de los conceptos psicoanalíticos</a></h3>
          </div>
            <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Información Académica</a></h3>
          </div>
            <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Extensión</a></h3>
          </div>
            <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Referencias a la obra de Freud</a></h3>
          </div>
            <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Conexiones</a></h3>
          </div>
            <div class="col-lg-4 col-sm-6 text-center mb-4">
            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="">Agenda</a></h3>
          </div>
        </div>
      </div>  
    </div>
  </section>


  <!-- fin circulos -->

  <!-- llamado -->

  <section class="bg-azul py-5">
    <div class="container">
      <h3 class="cl-blanco text-center display-3 bold">Título</h3>
      <p class="cl-blanco text-center my-5 lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime consectetur sit sunt fuga, delectus id omnis voluptate dignissimos ratione vero aliquam ea eum dolorum voluptas quidem perferendis, reiciendis excepturi et.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas expedita animi maxime voluptatum pariatur, in voluptates qui, voluptate ea accusamus nemo quo perferendis quasi molestias cumque quia sed aut earum! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus ipsam perspiciatis veritatis maxime consequatur et eos pariatur accusantium voluptates, dolores tempore sunt quas repellendus suscipit iusto totam atque dolorum autem. 
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore aliquid eaque quasi sint cumque nam magnam accusamus excepturi beatae et officiis, adipisci debitis, omnis, aperiam deleniti obcaecati tempora labore. Recusandae.</p>
    </div>
  </section>

  <!-- fin llamado -->

  <!-- blog -->

  <section class="py-5">
    <h2 class="cl-azul text-center display-4 bold mb-5">Blog</h2>
      <div class="container">
        <div class="row">
          <div class="col-md-4 cuadricula">
            <div class="inner-cuadricula" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
              <div class="row mt-3 titulo-blog">
                <div class="col-md-3">
                  <div class="bg-rojo py-1">
                    <h5 class="cl-blanco text-center display-5">10</h5>
                    <h5 class="cl-blanco text-center bold text-uppercase my-0">ene</h5>
                  </div>
                </div>
                <div class="col-md-9">
                  <h3 class="cl-azul"><a class="cl-azul" href="">Título de la noticia</a></h3>
                </div>
              </div>
          </div>
          <div class="col-md-4 cuadricula">
            <div class="inner-cuadricula" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
              <div class="row mt-3 titulo-blog">
                <div class="col-md-3">
                  <div class="bg-rojo py-1">
                    <h5 class="cl-blanco text-center display-5">10</h5>
                    <h5 class="cl-blanco text-center bold text-uppercase my-0">ene</h5>
                  </div>
                </div>
                <div class="col-md-9">
                  <h3 class="cl-azul"><a class="cl-azul" href="">Título de la noticia</a></h3>
                </div>
              </div>
          </div>
          <div class="col-md-4 cuadricula">
            <div class="inner-cuadricula" style="background-image:url('img/imagen-muestra.jpg')">
            </div>
              <div class="row mt-3 titulo-blog">
                <div class="col-md-3">
                  <div class="bg-rojo py-1">
                    <h5 class="cl-blanco text-center display-5">10</h5>
                    <h5 class="cl-blanco text-center bold text-uppercase my-0">ene</h5>
                  </div>
                </div>
                <div class="col-md-9">
                  <h3 class="cl-azul"><a class="cl-azul" href="">Título de la noticia</a></h3>
                </div>
              </div>
          </div>
          
        </div>  
      </div>
  </section>

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
                <input id="textinput" name="textinput" type="text" placeholder="Nombre" class="form-control input-md"> 
              </div>
            </div>

            <!-- Text input-->
            <div class="col-md-6">
              <div class="form-group">
                <input id="textinput" name="textinput" type="text" placeholder="Email" class="form-control input-md"> 
              </div>
            </div>
          </div>

          <!-- Textarea -->
          <div class="form-group">
            <textarea class="form-control" id="textarea" name="textarea" placeholder="Mensaje"></textarea>
          </div>

          <div class="form-group btn-azul text-center">
            <input type="submit" class="btn bg-azul cl-blanco">
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

</html>
