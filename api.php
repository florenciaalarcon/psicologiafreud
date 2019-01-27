<?php

include_once("backend/ewcfg13.php");

$con = mysqli_connect($EW_CONN["DB"]["host"],$EW_CONN["DB"]["user"],$EW_CONN["DB"]["pass"],$EW_CONN["DB"]["db"]);

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

function consulta($consulta){

	global $con;
	
	$respuesta = array();

	$sql = $consulta;

	$resultado = mysqli_query($con, $sql);

	if ($resultado) {
		if (mysqli_num_rows($resultado) > 0) {
			for ($i=0; $i < mysqli_num_rows($resultado)	; $i++) { 
				$registro = mysqli_fetch_assoc($resultado);
				$registro = array_map("utf8_encode", $registro);

				array_push($respuesta, $registro);
			}
		}
	}

	return $respuesta;	

}

/*******************************************************************/

function navegador(){
	?>
    <!-- cabecera -->
    <div class="fixed-top">

      <div class="bg-gris">
        <div class="container">
          <div class="row">
            <div class="col-lg-4">
                <a class="navbar-brand" href="#">
                  <img class="logo" src="img/logo-blanco.svg" alt="logo">
                </a>
            </div>
            <div class="col-lg-3 pt-lg-6 menu-superior">
              <div class="row">
                <div class="col-lg-12 d-none d-lg-block text-right">
                  <a class= "lead unstyled cl-blanco" href="">Contacto</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 pt-lg-5 menu-superior">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent cl-azul border-0" id="icono-buscador"><i class="fas fa-search"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="Buscar" aria-describedby="icono-buscador">
              </div>
            </div>
            <div class="d-none d-lg-block col-lg-1 pt-lg-7">
                <a class="cl-azul" target="_blank" href="https://www.facebook.com/Freud1Delgado"><i class="fab fa-facebook-f"></i></a>
                <a class="cl-azul" target="_blank" href="https://twitter.com/Freud1UBA"><i class="fab fa-twitter"></i></a>
                <a class="cl-azul" target="_blank" href="https://www.instagram.com/psicoanalisisfreud1/"><i class="fab fa-instagram"></i></a>
            </div>
          </div>  
        </div>
        
      </div>
      <!-- fin cabecera -->

      <!-- dropdown -->

    <nav class="navbar navbar-expand-lg navbar-light bg-azul">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item dropdown horizontal first">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              INFORMACIÓN ACADÉMICA</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Presentación de la materia</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Objetivos, modalidad de cursada y evaluación</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Programa de la materia</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Docentes</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Información de teóricos</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">información de seminarios</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Información de prácticos</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Áreas de responsabilidad</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Información de la cursada</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Investigaciones</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Textos de los docentes de la cátedra</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Agenda académica</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              EXTENSIÓN</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Ateneos clínicos</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Psicoanálisis y cultura</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Taller de escritura</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Taller de exámenes finales</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Pasantías</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Conferencias</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Cursos</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Jornadas</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Material de la I Jornada de Psicoanalisis Freud I realizada en 2008</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Escuela de formación de ayudantes</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Dispositivo asistencial</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              REFERENCIAS AL PROGRAMA</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Presentación </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Instructivo</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Referencias al programa</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              REFERENCIAS SOBRE LA OBRA DE FREUD</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Presentación</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lo contemporaneo de Freud</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lo actual</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Galería fotográfica de Freud</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              MATERIA ELECTIVA</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Materia electiva: Construcción de los conceptos psicoanalíticos</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Programa</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Agenda académica</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Contacto</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              CONEXIONES</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              AGENDA ABIERTA</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
            </div>
          </li>
          <li class="nav-item dropdown horizontal">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              COMUNICACIÓN</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Lorem Impsum</a>
            </div>
          </li>
      </div>
    </nav>

      <!-- fin dropdown -->
      
    </div>	
	<?php
}

function footer(){
	?>
  <footer class="bg-azul pt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled cl-blanco">
            <li class="bold">Responsables Página Web</li>
            <li>Susi Epsztein y Verónica Wainszelbaum</li>
            <li class="bold">Colaboran</li>
            <li>Federico Giachetti, Liliana Mariño, Celeste Silanes y Noelia Sabelli</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled cl-blanco">
            <li class="bold">Responsables Facebook y Twitter</li>
            <li>Gerardo Battista</li>
            <li class="bold">Colaboran</li>
            <li>Santiago Hormanstorfer, Julián Pilar y Gisela Contino</li>
          </ul>
        </div>
      </div>

      <!-- copyright -->

      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled cl-blanco">
            <li>
              <a class="cl-blanco" href="">Desarrollado por Oh my colour!</a>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled cl-blanco">
            <li>
              <p>© Cátedra de Psicoanálisis Freud 1 - 2018 - Todos los derechos reservados</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- fin copyright -->	
	<?php
}

function scripts(){
	?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <script>

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
     })

  </script>

	<?php
}

/*******************************************************************/

function head(){
	?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Psicoanálisis Freud 1</title>
  <link rel="icon" href="img/favicon.ico">

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/full.css" rel="stylesheet">

  <!-- Fontawesome -->
  <link href="vendor/fontawesome/css/all.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

	<?php
}

/*******************************************************************/

function obtenerSlider(){

	$sql = "SELECT * FROM slider";

	return consulta($sql);

}

/*******************************************************************/

function obtenerMenu(){

	$sql = "SELECT
	categorias_blog.id,
	categorias_blog.denominacion,
	categorias_blog.idMenu,
	categorias_blog.orden,
	menu.denominacion as denominacionmenu,
	menu.orden as ordenmenu,
	'blog' AS tipo
	FROM categorias_blog
	INNER JOIN menu ON categorias_blog.idMenu = menu.id
	UNION
	SELECT
	paginas_estaticas.id,
	paginas_estaticas.titulo,
	paginas_estaticas.idMenu,
	paginas_estaticas.orden,
	menu.denominacion as denominacionmenu,
	menu.orden as ordenmenu,
	'estatica' AS tipo
	FROM paginas_estaticas
	INNER JOIN menu ON paginas_estaticas.idMenu = menu.id
	ORDER BY ordenmenu, orden, denominacion";

	return consulta($sql);

}

if (isset($_GET["debug"])) {

	var_dump(obtenerMenu()) ;

	var_dump(obtenerSlider()) ;
	
}

?>
