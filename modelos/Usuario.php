<?php 
require_once "../config/Conexion.php";

/**
 * Clase Usuario
 * Gestiona los accesos, roles y datos de los usuarios del sistema
 */
Class Usuario
{
	private $conexion;

	public function __construct($conexionDB = null)
	{
		global $conexion;
		$this->conexion = $conexionDB ?? $conexion;
	}

	public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion) VALUES (?,?,?,?,?,?,?,?,?,?,'1')";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("ssssssssss", $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen);
		$stmt->execute();
		$idusuarionew = $stmt->insert_id;

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES(?, ?)";
			$stmt_detalle = $this->conexion->prepare($sql_detalle);
			$stmt_detalle->bind_param("ii", $idusuarionew, $permisos[$num_elementos]);
			$stmt_detalle->execute() or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		return $sw;
	}

	public function editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
	{
		$sql="UPDATE usuario SET nombre=?,tipo_documento=?,num_documento=?,direccion=?,telefono=?,email=?,cargo=?,login=?,clave=?,imagen=? WHERE idusuario=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("ssssssssssi", $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $idusuario);
		$stmt->execute();

		$sqldel="DELETE FROM usuario_permiso WHERE idusuario=?";
		$stmtdel = $this->conexion->prepare($sqldel);
		$stmtdel->bind_param("i", $idusuario);
		$stmtdel->execute();

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES(?, ?)";
			$stmt_detalle = $this->conexion->prepare($sql_detalle);
			$stmt_detalle->bind_param("ii", $idusuario, $permisos[$num_elementos]);
			$stmt_detalle->execute() or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		return $sw;
	}

	public function desactivar($idusuario)
	{
		$sql="UPDATE usuario SET condicion='0' WHERE idusuario=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idusuario);
		return $stmt->execute();
	}

	public function activar($idusuario)
	{
		$sql="UPDATE usuario SET condicion='1' WHERE idusuario=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idusuario);
		return $stmt->execute();
	}

	public function mostrar($idusuario)
	{
		$sql="SELECT * FROM usuario WHERE idusuario=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idusuario);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function listar()
	{
		$sql="SELECT * FROM usuario";
		return $this->conexion->query($sql);		
	}

	public function listarmarcados($idusuario)
	{
		$sql="SELECT * FROM usuario_permiso WHERE idusuario=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idusuario);
		$stmt->execute();
		return $stmt->get_result();
	}

	public function verificar($login,$clave)
    {
    	$sql="SELECT idusuario,nombre,tipo_documento,num_documento,telefono,email,cargo,imagen,login FROM usuario WHERE login=? AND clave=? AND condicion='1'"; 
    	$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("ss", $login, $clave);
		$stmt->execute();
		return $stmt->get_result();
    }
}

// TODO: Validacion de fuerza de contraseñas

?>
