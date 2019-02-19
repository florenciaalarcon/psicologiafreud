<?php

include_once("backend/ewcfg13.php");

$MESES = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

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

function fecha($fecha){
	global $MESES;
	return date('d', strtotime($fecha))." de ".$MESES[date('n', strtotime($fecha))-1]." del ".date('Y', strtotime($fecha));
}

function accesosDirectos(){

	$contenido = obtenerAccesosDirectos();

	?>

	  <section class="m-3 py-5">
    <div class="row">
      <div class="col-8 offset-2">
        <div class="row">

      <?php 

      	foreach ($contenido as $key => $value) {
      		?>

	          <div class="col-lg-4 col-sm-6 text-center mb-4">
	          	<?php 

	          		if ($value["imagen"] != "") {
	          			?>
				            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5" style="background-image:url('backend/uploads/<?php echo $value["imagen"] ?>')">
				            </div>
	          			
	          			<?php 
	          		}else{
	          			?>
				            <div class="inner-cuadricula rounded-circle img-fluid d-block mx-5 bg-azul">
				            </div>

	          			<?php
	          		}

	          	 ?>
	            <h3 class="text-center"><a class="cl-azul titulo-circulo" href="menu.php?id=<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?></a></h3>
	          </div>
      		
      		<?php
      	}

      ?>        	

        </div>
      </div>  
    </div>
  </section>

	<?php
}

/*******************************************************************/

function ultimasEntradas(){

	global $MESES;	

	$ult = obtenerUltimasEntradas();

	?>

      <h3 class="text-center mb-5">Últimas Entradas</h3>

      <?php 

      	foreach ($ult as $key => $value) {
      		?>
			      <div class="row my-3">

			        <div class="col-lg-3 mb-1">
			          <div class="bg-rojo">
			            <h6 class="cl-blanco text-center display-5"><?php echo date('d', strtotime($value["fechaModificacion"])) ?></h6>
			            <h6 class="cl-blanco text-center bold text-uppercase my-0"><?php echo  substr($MESES[date('n', strtotime($value["fechaModificacion"]))-1],0,3) ?></h6>
			          </div>
			        </div>

			        <div class="col-lg-9">
			          <h5 class="cl-azul bold"><a class="cl-azul" href="entradablog.php?id=<?php echo $value["id"] ?>"><?php echo $value["titulo"] ?></a></h5>
			        </div>

			      </div>

			      <div class="dropdown-divider"></div>
      		
      		<?php
      	}

      ?>

	<?php
}

/*******************************************************************/

function ultimasEntradasHome(){

	global $MESES;	

	$ult = obtenerUltimasEntradas();

	?>

	  <section class="py-5">
    <h2 class="cl-azul text-center display-4 bold mb-5">Blog</h2>
      <div class="container">      	
        <div class="row">

		      <?php 

		      	foreach ($ult as $key => $value) {
		      		?>

		          <div class="col-md-4 cuadricula">
		          	<?php 
		          		if ($value["imagenPrincipal"] != "") {
		          			?>
		            			<div class="inner-cuadricula" style="background-image:url('backend/uploads/<?php echo $value["imagenPrincipal"] ?>')">
		          			<?php
		          		}else{
		          			?>
		            			<div class="inner-cuadricula bg-azul">
		          			<?php		          			
		          		}
		          	 ?>
		            </div>
		              <div class="row mt-3 titulo-blog">
		                <div class="col-md-3">
		                  <div class="bg-rojo py-1">
		                    <h5 class="cl-blanco text-center display-5"><?php echo date('d', strtotime($value["fechaModificacion"])) ?></h5>
		                    <h5 class="cl-blanco text-center bold text-uppercase my-0"><?php echo  substr($MESES[date('n', strtotime($value["fechaModificacion"]))-1],0,3) ?></h5>
		                  </div>
		                </div>
		                <div class="col-md-9">
		                  <h3 class="cl-azul"><a class="cl-azul" href="entradablog.php?id=<?php echo $value["id"] ?>"><?php echo $value["titulo"] ?></a></h3>
		                </div>
		              </div>
		          </div>
		      		
		      		<?php
		      	}

		      ?>

          
        </div>  
      </div>
  </section>


	<?php
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
                <a class="navbar-brand" href="index.php">
                  <img class="logo" src="img/logo-blanco.svg" alt="logo">
                </a>
            </div>
            <div class="col-lg-7 pt-lg-5 menu-superior">
							<form id="buscador" action="busqueda.php" method="GET">
								<div class="input-group mb-3">
								  <input value="<?php echo isset($_GET["q"])?$_GET["q"]:"" ?>" name="q" type="search" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="boton-buscador">
								  <div class="input-group-append">
								    <button class="btn btn-primary" type="button" id="boton-buscador"><i class="fas fa-search"></i> Buscar</button>
								  </div>
								</div>
							</form>
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
        	<li>
        		<div>
					
							<?php 

								$menu = obtenerMenu();

								$idmenu = 0;

								foreach ($menu as $key => $value) {

									if ($value["idMenu"] != $idmenu) {
										?>
						            </div>
						          </li>											
						          <li class="nav-item dropdown horizontal <?php echo $key == 0?'first':'' ?> ">
						            <a class="nav-link dropdown-toggle text-uppercase" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						              <?php echo $value["denominacionmenu"] ?></a>
						            <div class="dropdown-menu" aria-labelledby="navbarDropdown">								
										<?php
										$idmenu = $value["idMenu"];

									}

									?>

			              <a class="dropdown-item" href="<?php echo $value["tipo"] == "estatica" ? 'paginaestatica.php?id='.$value["id"]:'categoriablog.php?id='.$value["id"] ?>"><?php echo $value["denominacion"] ?></a>
			              <div class="dropdown-divider"></div>							

									<?php

								}

							?>

            </div>
          </li>
				</ul>
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
            <li>Susi Epsztein, Verónica Wainszelbaum y Gerardo Battista</li>
            <li class="bold">Colaboran</li>
            <li>Federico Giachetti, Liliana Mariño, Celeste Silanes, Noelia Sabelli, Santiago Hormanstorfer (Responsable de Contenidos), Julián Pilar, Gisela Contino y Bettina Quiroga.</li>
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

    $("#boton-buscador").click(function(){
    	$("#buscador").submit();
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

function obtenerCategoriaBlog($idcategoria){

	$sql = "SELECT
		categorias_blog.*,
		menu.id AS idmenu,
		menu.denominacion AS denominacionmenu
		FROM categorias_blog
		INNER JOIN menu
		ON categorias_blog.idMenu = menu.id
		WHERE categorias_blog.id = '".$idcategoria."'";

	return consulta($sql);

}


/*******************************************************************/

function obtenerEntradasBlog($idcategoria){

	$sql = "SELECT *
	FROM entradas_blog
		WHERE idCategoria = '".$idcategoria."'";

	return consulta($sql);

}

/*******************************************************************/

function obtenerDatosMenu($idmenu){

	$sql = "SELECT *
	FROM menu
	WHERE id = '".$idmenu."'";

	return consulta($sql);	
}

/*******************************************************************/

function obtenerItemsMenu($idmenu){

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
	AND menu.id = '".$idmenu."'
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
	AND menu.id = '".$idmenu."'
	ORDER BY ordenmenu, orden, denominacion";

	return consulta($sql);	
}

/*******************************************************************/

function obtenerAccesosDirectos(){

	$sql = "SELECT * FROM menu WHERE menu.accesoDirecto = 1";

	return consulta($sql);	
}
/*******************************************************************/


function obtenerUltimasEntradas(){

	$sql = "SELECT *
	FROM entradas_blog
	ORDER BY id desc
	LIMIT 3";

	return consulta($sql);	
}
/*******************************************************************/

function obtenerContenidoPaginaEstatica($id){

	$sql = "SELECT
	paginas_estaticas.*,
	menu.id AS idmenu,
	menu.denominacion AS denmenu
	FROM paginas_estaticas
	INNER JOIN menu
	ON paginas_estaticas.idMenu = menu.id
	WHERE paginas_estaticas.id = '".$id."'";

	return consulta($sql);		

}

function obtenerResultadoBusqueda($busqueda){

	$busqueda = utf8_decode($busqueda);

	$sql = "SELECT
	entradas_blog.id,
	entradas_blog.titulo,
	entradas_blog.contenido,
	entradas_blog.imagenPrincipal,
	entradas_blog.fechaModificacion,	
	categorias_blog.denominacion,
	menu.denominacion as denominacionmenu,
	'blog' AS tipo
	FROM entradas_blog
	INNER JOIN categorias_blog
	ON entradas_blog.idCategoria = categorias_blog.id
	INNER JOIN menu ON categorias_blog.idMenu = menu.id
	WHERE CONCAT(
		entradas_blog.titulo,
		entradas_blog.contenido,
		categorias_blog.denominacion,
		menu.denominacion
	) LIKE '%".$busqueda."%'
	UNION
	SELECT
	paginas_estaticas.id,
	paginas_estaticas.titulo,
	paginas_estaticas.contenido,
	paginas_estaticas.imagenPrincipal,
	paginas_estaticas.fechaModificacion,	
	'',
	menu.denominacion as denominacionmenu,
	'estatica' AS tipo
	FROM paginas_estaticas
	INNER JOIN menu ON paginas_estaticas.idMenu = menu.id
	WHERE CONCAT(
		paginas_estaticas.titulo,
		paginas_estaticas.contenido,
		menu.denominacion
	) LIKE '%".$busqueda."%'";

	return consulta($sql);		

}

/*******************************************************************/

function obtenerContenidoEntradaBlog($id){

	$sql = "SELECT
	entradas_blog.*,
	categorias_blog.id AS idcatblog,
	categorias_blog.denominacion AS dencatblog,
	menu.id AS idmenu,
	menu.denominacion AS denmenu
	FROM entradas_blog
	INNER JOIN categorias_blog
	ON entradas_blog.idCategoria = categorias_blog.id
	INNER JOIN menu
	ON categorias_blog.idMenu = menu.id
	WHERE entradas_blog.id = '".$id."'";

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

	var_dump(obtenerSlider());
	
}

?>
