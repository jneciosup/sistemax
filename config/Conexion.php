<?php 
require_once "global.php";

class Database {
    private static $instancia = null;
    private $conexion;

    private function __construct() {
        $this->conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        $this->conexion->set_charset(DB_ENCODE);
        if (mysqli_connect_errno()) {
            printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
            exit();
        }
    }

    public static function getConexion() {
        if (self::$instancia == null) {
            self::$instancia = new Database();
        }
        return self::$instancia->conexion;
    }
}

$conexion = Database::getConexion();

if (!function_exists('ejecutarConsulta'))
{
	function ejecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		return $conexion->insert_id;			
	}

	function limpiarCadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}
}

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>
