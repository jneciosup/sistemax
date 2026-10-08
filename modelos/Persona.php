<?php 
require_once "../config/Conexion.php";

Class Persona
{
	private $conexion;

	public function __construct($conexionDB = null)
	{
		global $conexion;
		$this->conexion = $conexionDB ?? $conexion;
	}

	public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email)
	{
		$sql="INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email) VALUES (?,?,?,?,?,?,?)";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("sssssss", $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
		return $stmt->execute();
	}

	public function editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email)
	{
		$sql="UPDATE persona SET tipo_persona=?,nombre=?,tipo_documento=?,num_documento=?,direccion=?,telefono=?,email=? WHERE idpersona=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("sssssssi", $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $idpersona);
		return $stmt->execute();
	}

	public function eliminar($idpersona)
	{
		$sql="DELETE FROM persona WHERE idpersona=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idpersona);
		return $stmt->execute();
	}

	public function mostrar($idpersona)
	{
		$sql="SELECT * FROM persona WHERE idpersona=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idpersona);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}

	public function listarp()
	{
		$sql="SELECT * FROM persona WHERE tipo_persona='Proveedor'";
		return $this->conexion->query($sql);		
	}

	public function listarc()
	{
		$sql="SELECT * FROM persona WHERE tipo_persona='Cliente'";
		return $this->conexion->query($sql);		
	}
}
?>