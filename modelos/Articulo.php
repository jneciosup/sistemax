<?php 
require_once "../config/Conexion.php";

Class Articulo
{
	private $conexion;

	public function __construct($conexionDB = null)
	{
		global $conexion;
		$this->conexion = $conexionDB ?? $conexion;
	}

	public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
	{
		$sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,condicion) VALUES (?,?,?,?,?,?,'1')";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("isssss", $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
		return $stmt->execute();
	}

	public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
	{
		$sql="UPDATE articulo SET idcategoria=?,codigo=?,nombre=?,stock=?,descripcion=?,imagen=? WHERE idarticulo=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("isssssi", $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $idarticulo);
		return $stmt->execute();
	}

	public function desactivar($idarticulo)
	{
		$sql="UPDATE articulo SET condicion='0' WHERE idarticulo=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idarticulo);
		return $stmt->execute();
	}

	public function activar($idarticulo)
	{
		$sql="UPDATE articulo SET condicion='1' WHERE idarticulo=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idarticulo);
		return $stmt->execute();
	}

	public function mostrar($idarticulo)
	{
		$sql="SELECT * FROM articulo WHERE idarticulo=?";
		$stmt = $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idarticulo);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}

	public function listar()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
		return $this->conexion->query($sql);		
	}

	public function listarActivos()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return $this->conexion->query($sql);		
	}

	public function listarActivosVenta()
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return $this->conexion->query($sql);		
	}
}
?>