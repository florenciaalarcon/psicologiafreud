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
