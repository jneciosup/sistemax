<?php

// Inicia o reanuda la sesión del usuario para gestionar datos globales ($_SESSION)
session_start(); 

// Importa el modelo Usuario que contiene las consultas SQL a la base de datos
require_once "../config/Conexion.php";
require_once "../modelos/Usuario.php";

// Instancia el objeto de la clase Usuario
$usuario=new Usuario($conexion);

// Recepción y desinfección de variables enviadas por método POST.
// Si existen en $_POST, se limpian los caracteres especiales con la función limpiarCadena(); de lo contrario, quedan vacías.
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

// Evalúa la acción a realizar según el parámetro 'op' enviado por URL (método GET)
switch ($_GET["op"]){
	case 'guardaryeditar':
		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
			}
		}
		$clavehash=hash("SHA256",$clave);

		if (empty($idusuario)){
			$rspta=$usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
			echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
			echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
 		echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
 		echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
	break;

	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$usuario->listar();
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"idusuario"=>$reg->idusuario,
 				"nombre"=>$reg->nombre,
 				"tipo_documento"=>$reg->tipo_documento,
 				"num_documento"=>$reg->num_documento,
 				"telefono"=>$reg->telefono,
 				"email"=>$reg->email,
 				"login"=>$reg->login,
 				"imagen"=>$reg->imagen,
 				"condicion"=>$reg->condicion
 				);
 		}
 		$results = array(
 			"sEcho"=>1, 
 			"iTotalRecords"=>count($data), 
 			"iTotalDisplayRecords"=>count($data), 
 			"aaData"=>$data);
 		echo json_encode($results);
	break;

	case 'permisos':
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();

		$id=$_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		$valores=array();

		while ($per = $marcados->fetch_object())
		{
			array_push($valores, $per->idpermiso);
		}

        $permisosData = array();
		while ($reg = $rspta->fetch_object())
		{
            $permisosData[] = array(
                "idpermiso" => $reg->idpermiso,
                "nombre" => $reg->nombre,
                "marcado" => in_array($reg->idpermiso,$valores)
            );
		}
        echo json_encode($permisosData);
	break;

	case 'verificar':
		$logina=$_POST['logina'];
	    $clavea=$_POST['clavea'];

		$clavehash=hash("SHA256",$clavea);

		$rspta=$usuario->verificar($logina, $clavehash);
		$fetch=$rspta->fetch_object();

		if (isset($fetch))
	    {
	        $_SESSION['idusuario']=$fetch->idusuario;
	        $_SESSION['nombre']=$fetch->nombre;
	        $_SESSION['imagen']=$fetch->imagen;
	        $_SESSION['login']=$fetch->login;

	    	$marcados = $usuario->listarmarcados($fetch->idusuario);
			$valores=array();

			while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->idpermiso);
			}

			in_array(1,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
			in_array(2,$valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
			in_array(3,$valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
			in_array(4,$valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
			in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
			in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
			in_array(7,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
	    }
	    echo json_encode($fetch);
	break;

	case 'salir':
        session_unset();
        session_destroy();
        header("Location: ../index.php");
	break;
}

// header('X-XSS-Protection: 1; mode=block');

?>
