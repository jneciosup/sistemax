<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

/**
 * Clase Categoria
 * Gestiona el mantenimiento de categorías en la base de datos
 */
Class Categoria
{
	private $conexion;

	public function __construct($conexionDB = null)
	{
		global $conexion;
		$this->conexion = $conexionDB ?? $conexion;
	}

	public function insertar($nombre,$descripcion)
	{
		$sql="INSERT INTO categoria (nombre,descripcion,condicion) VALUES (?,?,'1')";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("ss", $nombre, $descripcion);
		return $stmt->execute();
	}

	public function editar($idcategoria,$nombre,$descripcion)
	{
		$sql="UPDATE categoria SET nombre=?, descripcion=? WHERE idcategoria=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("ssi", $nombre, $descripcion, $idcategoria);
		return $stmt->execute();
	}

	public function desactivar($idcategoria)
	{
		$sql="UPDATE categoria SET condicion='0' WHERE idcategoria=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idcategoria);
		return $stmt->execute();
	}

	public function activar($idcategoria)
	{
		$sql="UPDATE categoria SET condicion='1' WHERE idcategoria=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idcategoria);
		return $stmt->execute();
	}

	public function mostrar($idcategoria)
	{
		$sql="SELECT * FROM categoria WHERE idcategoria=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idcategoria);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function listar()
	{
		$sql="SELECT * FROM categoria";
		return $this->conexion->query($sql);		
	}
	
	public function select()
	{
		$sql="SELECT * FROM categoria where condicion=1";
		return $this->conexion->query($sql);		
	}
}


// TODO: Implementar Soft Deletes globales

?>
