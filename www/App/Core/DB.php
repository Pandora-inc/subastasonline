<?php
namespace www\App\Core;

use Exception;
use PDO;
use PDOException;
use PDOStatement;
use function www\App\Core\DB\fetch_array;
use function www\App\Core\DB\fetch_assoc;
use function www\App\Core\DB\num_rows;
use function www\App\Core\DB\query;
use function www\App\Core\DB\real_escape_string;

class DB extends \PDO
{

    /**
     * Muestra por pantalla diferentes codigos para facilitar el debug
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Graba log con los errores de BD *
     */
    public $grabarArchivoLogError = false;

    /**
     * Graba log con todas las consultas realizadas *
     */
    public $grabarArchivoLogQuery = false;

    /**
     * Imprime cuando hay errores sql *
     */
    public $mostrarErrores = true;

    /**
     * Usar die() si hay un error de sql.
     * Esto es util para etapa de desarrollo *
     *
     * @var boolean
     */
    public $dieOnError = false;

    /**
     * Setear un email para enviar email cuando hay errores sql *
     */
    public $emailAvisoErrorSql;

    /**
     * Tipo de base a la que se conectara.
     * Los tipos permitidos son: mysql, oracle, mssql
     *
     * @var string
     */
    private static $dbtype = 'mysql';

    /**
     * Nombre sel servidor de base de datos.
     *
     * @var string
     */
    private static $dbHost;

    /**
     * Usuario que se conectara a la base de datos.
     *
     * @var string
     */
    private static $dbUser;

    /**
     * Contrasena del usuario de coeccion.
     *
     * @var string
     */
    private static $dbPass;

    /**
     * Nombre de la base de datos a la que conetarse.
     *
     * @var string
     */
    private static $dbName;

    /**
     * Juego de caracteres por defecto de la base.
     *
     * @var string
     */
    private static $charset = 'utf8';

    /**
     * Establece si se va a realizar o no el commit automatico de las consutas.
     *
     * @var boolean
     */
    private static $commit = false;

    /**
     * Propiedad estática para almacenar la conexión.
     *
     * @var PDO
     */
    private static $db = null;

    /**
     *
     * {@inheritdoc}
     * @see PDO::__construct()
     */
    public function __construct(string $dbHost, string $dbName, string $dbUsr, string $dbPass, string $dbType = 'mysql', string $charset = 'utf-8')
    {
        if ($dbType == 'mysql') {
            $typo_conector = "mysql";
        } elseif ($dbType == 'oracle') {
            $typo_conector = "oci";
        } elseif ($dbType == 'mssql') {
            $typo_conector = "sqlsrv";
        } elseif ($dbType == 'pgsql') {
            $typo_conector = "pgsql";
        }

        $dsn = $typo_conector . ":host=" . $dbHost . ";dbname=" . $dbName;

        parent::__construct($dsn, $dbUsr, $dbPass);

        try {
            self::$db = $this;
        } catch (PDOException $e) {

            echo "Conexion fallida" . $e->gettMessage();
            exit();
        }
    }

    /**
     * Retorna el PDOStatement para el $query proporcionado.
     *
     * @param string $query
     * @return PDOStatement
     */
    public static function getStatement($query)
    {
        return self::getConnection()->prepare($query);
    }

    /**
     * Funcion que devuelve el codigo de error de la consulta
     *
     * @param object $result
     *            - Si el objeto del que lebanter el error no es el defoult.
     *
     * @return string Con el codigo del error
     */
    public function errorNro()
    {
        return self::$db->errorCode();
    }

    /**
     * Funcion que devuelve el texto del error de la consulta
     *
     * @param object $result
     *            - Si el objeto del que lebanter el error no es el defoult.
     *
     * @return string Con el texto del error
     */
    public function error()
    {
        return self::$db->errorInfo();
    }

    /**
     *
     * Funcion que se encarga de ejecutar las cunsultas SELECT
     *
     * A tener en cuenta, por el momento se recomienda no usar texto entre comillas
     * con el simbolo dos puntos ( : ) dentro de la consulta, por lo menos dentro de las consultas parametrizadas.
     *
     * @version 1.0.2 Se corrigio la funcion para que se pudieran usar consultas parametrizadas en mysql.
     *
     * @param string $str_query
     *            codigo de la query a ejecutar
     * @param bool $esParam
     *            Define si la consulta va a ser parametrizada o no. (por defecto false)
     * @param array $parametros
     *            Array con los parametros a pasar.
     *
     * @return array
     */
    public function query($str_query, $esParam = false, $parametros = array())
    {
        $str_query = $this->format_query_usar($str_query);

        $statement = self::$db->prepare($str_query);

        if ($esParam == true) {

            if (($cantidad = substr_count($str_query, ':')) > 0) {
                $para = explode(':', $str_query);

                for ($i = 0; $i < $cantidad; $i ++) {
                    $e = $i + 1;

                    $paraY = explode(' ', $para[$e]);
                    $paraY[0] = str_replace(")", "", $paraY[0]);
                    $paraY[0] = str_replace(";", "", $paraY[0]);

                    $paraY[0] = trim(str_replace(",", "", $paraY[0]));

                    $parametros[$i] = (string) ($parametros[$i]);

                    $statement->bindParam(":$paraY[0]", $parametros[$i]);
                }
            } else if (($cantidad = substr_count($str_query, '?')) > 0) {
                for ($i = 0; $i < $cantidad; $i ++) {
                    $statement->bindParam($i, $parametros[$i]);
                }
            }
        }

        if (self::$commit == false) {
            $statement->execute();
        } else {
            $statement->execute();
            self::$db->commit();
        }

        // Empezamos el debug de la consulta
        if ($this->debug) {
            echo "<div style='background-color:#E8E8FF; padding:10px; margin:10px; font-family:Arial; font-size:11px; border:1px solid blue'>";
            echo $this->format_query_imprimir($str_query);

            if ($esParam == true) {
                $this->imprimirParam($str_query, $parametros);
            }

            echo "</div>";
        }

        if (isset($this->debugsql)) {
            consola($str_query);
        }

        if ($this->grabarArchivoLogQuery) {
            $str_log = date("d/m/Y H:i:s") . " " . getenv("REQUEST_URI") . "\n";
            $str_log .= $str_query;
            $str_log .= "\n------------------------------------------------------\n";
            error_log($str_log);
        }

        $errorNo = $this->errorNro();

        if ($errorNo != 0 and $errorNo != 1062 and $errorNo != 666) { // el error 1062 es "Duplicate entry"

            if ($this->mostrarErrores == TRUE) {
                echo "<div style='background-color:#FFECEC; padding:10px; margin:10px; font-family:Arial; font-size:11px; border:1px solid red'>";
                echo "<B>Error: </B> " . $this->error() . "<br><br>";
                echo "<B>P&aacute;gina:</B> " . getenv("REQUEST_URI") . "<br>";
                echo "<br>" . $this->format_query_imprimir($str_query);

                if ($esParam == true) {
                    $this->imprimirParam($str_query, $parametros);
                }

                echo "</div>";
            } else {
                echo "DB Error";
            }

            if ($this->dieOnError == true) {
                die("self die()");
            }

            if ($this->grabarArchivoLogError) {
                $str_log = "******************* ERROR ****************************\n";
                $str_log .= date("d/m/Y H:i:s") . " " . getenv("REQUEST_URI") . "\n";
                $str_log .= "IP del visitante: " . getenv("REMOTE_ADDR") . "\n";
                $str_log .= "Error: " . $this->error() . "\n";
                $str_log .= $str_query;
                $str_log .= "\n------------------------------------------------------\n";
                error_log($str_log);
            }

            // envio de aviso de error
            if ($this->emailAvisoErrorSql != "") {
                @mail($this->emailAvisoErrorSql, "Error SQL", "Error: " . $this->error() . "\n\nP&aacute;gina:" . getenv("REQUEST_URI") . "\n\nIP del visitante:" . getenv("REMOTE_ADDR") . "\n\nQuery:" . $str_query);
            }

            throw new Exception($this->error());
        }

        return $statement;
    }

    /**
     * Devuelve el fetch_assoc de una consulta dada
     *
     * @param mixed $result
     *            consulta de la cual devolver el fetch_assoc
     * @param string $limpiarEntidadesHTML
     *            true/false
     * @return array - Devuelve el fetch_assoc de $result
     */
    public function fetch_assoc($result, $limpiarEntidadesHTML = false)
    {
        if ($limpiarEntidadesHTML) {
            return limpiarEntidadesHTML($result->fetch(PDO::FETCH_ASSOC));
        } else {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Devuelve el fetch_row de una consulta dada
     *
     * @name fetch_row
     * @param string $result
     *            consulta de la cual devolver el fetch_assoc
     * @param bool $limpiarEntidadesHTML
     *            true/false
     * @return array - Obtiene una fila de datos del conjunto de resultados y la devuelve como un array enumerado, donde cada columna es almacenada en un indice del array comenzando por 0 (cero). Cada llamada subsiguiente a esta funcion devolvera la siguiente fila del conjunto de resultados, o NULL si no hay mas filas.
     *
     */
    public function fetch_row($result, $limpiarEntidadesHTML = false)
    {
        if ($limpiarEntidadesHTML) {
            return limpiarEntidadesHTML($result->fetch());
        } else {
            return $result->fetch();
        }
    }

    /**
     * Devuelve el fetch_array de una consulta dada
     *
     * @param mixed $result
     *            consulta de la cual devolver el fetch_array
     * @param string $limpiarEntidadesHTML
     *            true/false
     * @return resource Devuelve el fetch_array de $result
     */
    public function fetch_array($result, $limpiarEntidadesHTML = false)
    {
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve el fetch_all de una consulta dada
     *
     * @param mixed $result
     *            consulta de la cual devolver el fetch_array
     * @param string $limpiarEntidadesHTML
     *            true/false
     * @return resource Devuelve el fetch_all de $result
     */
    public function fetch_all($result, $limpiarEntidadesHTML = false)
    {
        return $result->fetchAll();
    }

    /**
     * Retorna un array organizado donde agrupa los elemento juntos.
     *
     * @param mixed $array
     * @return array[]
     */
    public function reacomodarFetchAll($array)
    {
        if (!is_array($array)) {
            throw new Exception("El dato a pasar debe ser un array,");
        }
        $retorno = array();

        foreach ($array as $key => $value) {
            $i = 0;
            foreach ($value as $key2 => $value2) {
                if (!is_array($retorno[$i])) {
                    $retorno[$i] = array();
                }

                $retorno[$i][$key] = $value2;
                $i ++;
                $key2;
            }
        }

        return $retorno;
    }

    /**
     * Devuelve el fetch_object de una consulta dada
     *
     * @param mixed $result
     *            consulta de la cual devolver el fetch_object
     *
     * @return object el fetch_object de $result
     */
    public function fetch_object($result, string $class_name = "stdClass")
    {
        return $result->fetchObject($class_name);
    }

    /**
     * Devuelve la cantidad de filas de la consulta
     *
     * @param mixed $result
     *            consulta de la cual devolver el num_rows
     */
    public function num_rows($result)
    {
        // FIXME habria que implementar una solucion mas artesanal ya que esta con los select suele generar errores.
        return $result->rowCount();
    }

    /**
     * Devuelve la cantidad de campos de la consulta
     *
     * @param mixed $result
     *            consulta de la cual devolver el num_fields
     */
    public function num_fields($result)
    {
        return $result->columnCount();
    }

    /**
     * Devuelve el numero de registros afectado por la ultima sentencia SQL de escritura
     *
     * @param mixed $stid
     *            Obligatorio para oracle es la consulta sobre la que se trabaja.$this
     *
     * @return mixed la cantidad de filas afectadas
     *
     */
    public function affected_rows($stid)
    {
        return $stid->rowCount();
    }

    /**
     * Obtiene el ultimo (o mayor) valor de id de una tabla determinada
     * en caso de tratarse de MySQL la ultima tabla con campo autoIncremental
     *
     * @param string $campoId
     *            Nombre del campo id a utilizar
     * @param string $tabla
     *            Tabla de la que obtener el id
     * @return int Valor maximo del campo id
     */
    public function insert_id($campoId, $tabla)
    {
        $sql = 'SELECT MAX(' . $campoId . ') ID FROM ' . $tabla;

        $result = $this->query($sql);

        $id = $this->fetch_array($result);

        return $id['ID'];
        // }
    }

    /**
     * Cierra las conexiones a la base de datos
     */
    public function close()
    {
        if (self::$dbtype == 'mysql') {
            return mysqli_close($this->con);
        } elseif (self::$dbtype == 'oracle') {
            return oci_close($this->con);
        } elseif (self::$dbtype == 'mssql') {
            // return mssql_close ($this->con);
            // return odbc_close ($this->con);
            return sqlsrv_close($this->con);
        }
    }

    /**
     * Escapa los caracteres especiales de una cadena para usarla en una sentencia SQL,
     * tomando en cuenta el conjunto de caracteres actual de la conexion
     *
     * @param string $string
     *            Cadena a ecapar
     */
    public function real_escape_string($string)
    {
        return addslashes($string);
    }

    /**
     * Formatea una query para su visualizacion por pantalla
     *
     * @param mixed $str_query
     *            La query a tratar
     * @return mixed La query formateada para su vista en la web
     */
    private function format_query_imprimir($str_query)
    {
        $str_query_debug = nl2br(htmlentities($str_query));

        $str_query_debug = strtolower($str_query_debug);

        $str_query_debug = str_ireplace("SELECT", "<span style='color:green;font-weight:bold;'>SELECT</span>", $str_query_debug);
        $str_query_debug = str_ireplace("INSERT", "<span style='color:#660000;font-weight:bold;'>INSERT</span>", $str_query_debug);
        $str_query_debug = str_ireplace("UPDATE", "<span style='color:#FF6600;font-weight:bold;'>UPDATE</span>", $str_query_debug);
        $str_query_debug = str_ireplace("REPLACE", "<span style='color:#FF6600;font-weight:bold;'>UPDATE</span>", $str_query_debug);
        $str_query_debug = str_ireplace("DELETE", "<span style='color:#CC0000;font-weight:bold;'>DELETE</span>", $str_query_debug);
        $str_query_debug = str_ireplace("FROM", "<br/><span style='color:green;font-weight:bold;'>FROM</span>", $str_query_debug);
        $str_query_debug = str_ireplace("WHERE", "<br/><span style='color:green;font-weight:bold;'>WHERE</span>", $str_query_debug);
        $str_query_debug = str_ireplace("ORDER BY", "<br/><span style='color:green;font-weight:bold;'>ORDER BY</span>", $str_query_debug);
        $str_query_debug = str_ireplace("GROUP BY", "<br/><span style='color:green;font-weight:bold;'>GROUP BY</span>", $str_query_debug);
        $str_query_debug = str_ireplace("INTO", "<br/><B>INTO</B>", $str_query_debug);
        $str_query_debug = str_ireplace("VALUES", "<br/><B>VALUES</B>", $str_query_debug);
        $str_query_debug = str_ireplace(" AND ", "<B> AND </B>", $str_query_debug);
        $str_query_debug = str_ireplace(" OR ", "<B> OR </B>", $str_query_debug);
        $str_query_debug = str_ireplace(" IS ", "<B> IS </B>", $str_query_debug);
        $str_query_debug = str_ireplace(" NULL ", "<B> NULL </B>", $str_query_debug);

        $str_query_debug = str_ireplace(" AS ", "<span style='color:magenta;font-weight:bold;'> AS </span>", $str_query_debug);
        $str_query_debug = str_ireplace("INNER", "<br/><span style='color:magenta;font-weight:bold;'>INNER</span>", $str_query_debug);
        $str_query_debug = str_ireplace("LEFT", "<br/><span style='color:magenta;font-weight:bold;'>LEFT</span>", $str_query_debug);
        $str_query_debug = str_ireplace("RIGHT", "<br/><span style='color:magenta;font-weight:bold;'>RIGHT</span>", $str_query_debug);
        $str_query_debug = str_ireplace("FULL", "<br/><span style='color:magenta;font-weight:bold;'>FULL</span>", $str_query_debug);
        $str_query_debug = str_ireplace("JOIN", "<span style='color:magenta;font-weight:bold;'>JOIN</span>", $str_query_debug);
        $str_query_debug = str_ireplace(" ON ", "<span style='color:magenta;font-weight:bold;'> ON </span>", $str_query_debug);

        $str_query_debug = str_ireplace("TO_CHAR", "<span style='color:pink;font-weight:bold;'>TO_CHAR</span>", $str_query_debug);
        $str_query_debug = str_ireplace("TO_DATE", "<span style='color:pink;font-weight:bold;'>TO_DATE</span>", $str_query_debug);

        $str_query_debug = str_ireplace("STR_TO_DATE", "STR_TO_DATE", $str_query_debug);
        $str_query_debug = str_ireplace("%y-%m-%d", "%Y-%m-%d", $str_query_debug);

        return $str_query_debug;
    }

    /**
     * Formatea una query a utilizar
     *
     * @param mixed $str_query
     *            La query a tratar
     * @return mixed La query formateada
     */
    private function format_query_usar($str_query)
    {
        if (self::$dbtype != "mysql") {
            $str_query_debug = strtolower($str_query);
        } else {
            $str_query_debug = $str_query;
        }

        $str_query_debug = str_ireplace("SELECT", "SELECT", $str_query_debug);
        $str_query_debug = str_ireplace("INSERT", "INSERT", $str_query_debug);
        $str_query_debug = str_ireplace("UPDATE", "UPDATE", $str_query_debug);
        $str_query_debug = str_ireplace("REPLACE", "UPDATE", $str_query_debug);
        $str_query_debug = str_ireplace("DELETE", "DELETE", $str_query_debug);
        $str_query_debug = str_ireplace("FROM", "FROM", $str_query_debug);
        $str_query_debug = str_ireplace("WHERE", "WHERE", $str_query_debug);
        $str_query_debug = str_ireplace("ORDER BY", "ORDER BY", $str_query_debug);
        $str_query_debug = str_ireplace("GROUP BY", "GROUP BY", $str_query_debug);
        $str_query_debug = str_ireplace("INTO", "INTO", $str_query_debug);
        $str_query_debug = str_ireplace("VALUES", "VALUES", $str_query_debug);
        $str_query_debug = str_ireplace(" AND ", " AND ", $str_query_debug);

        $str_query_debug = str_ireplace(" AS ", " AS ", $str_query_debug);
        $str_query_debug = str_ireplace("INNER", "INNER", $str_query_debug);
        $str_query_debug = str_ireplace("LEFT", "LEFT", $str_query_debug);
        $str_query_debug = str_ireplace("RIGHT", "RIGHT", $str_query_debug);
        $str_query_debug = str_ireplace("FULL", "FULL", $str_query_debug);
        $str_query_debug = str_ireplace("JOIN", "JOIN", $str_query_debug);
        $str_query_debug = str_ireplace(" ON ", " ON ", $str_query_debug);

        $str_query_debug = str_ireplace("TO_CHAR", "TO_CHAR", $str_query_debug);
        $str_query_debug = str_ireplace("TO_DATE", "TO_DATE", $str_query_debug);

        $str_query_debug = str_ireplace("STR_TO_DATE", "STR_TO_DATE", $str_query_debug);
        $str_query_debug = str_ireplace("%y-%m-%d", "%Y-%m-%d", $str_query_debug);

        return $str_query_debug;
    }

    /**
     * Obtiene el valor de un campo de una tabla.
     * Si no obtiene una sola fila retorna FALSE
     *
     * @param string $table
     *            Tabla
     * @param string $field
     *            Campo
     * @param string $id
     *            Valor para seleccionar con el campo clave
     * @param string $fieldId
     *            Campo clave de la tabla
     * @return string o false
     */
    public function getValue($table, $field, $id, $fieldId = "id")
    {
        $sql = "SELECT $field FROM $table WHERE $fieldId='$id'";
        $result = query($sql);

        if ($result and num_rows($result) == 1) {
            if ($fila = fetch_assoc($result)) {
                if (self::$dbtype == 'oracle') {
                    return $fila[strtoupper($field)];
                } else {
                    return $fila[$field];
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Obtiene una fila de una tabla.
     * Si no obtiene una sola fila retorna FALSE
     *
     * @param string $table
     *            Tabla
     * @param string $id
     *            Valor para seleccionar con el campo clave
     * @param string $fieldId
     *            Campo clave de la tabla
     * @param boolean $limpiarEntidadesHTML
     *            En caso de ser true realiza la limpiesa de las entidades.
     * @return array mysqli_fetch_assoc o false
     */
    public function getRow($table, $id, $fieldId = "id", $limpiarEntidadesHTML = false)
    {
        $sql = "SELECT * FROM $table WHERE $fieldId='$id'";
        $result = query($sql);

        if ($result and num_rows($result) == 1) {
            if ($limpiarEntidadesHTML) {
                return limpiarEntidadesHTML(fetch_array($result));
            } else {
                return fetch_array($result);
            }
        } else {
            return false;
        }
    }

    /**
     * Retorna un array con el arbol jerarquico a partir del nodo indicado (0 si es el root)
     * Esta funcion es para ser usada en tablas con este formato de campos: id, valor, idPadre
     *
     * @param string $tabla
     *            Nombre de la tabla
     * @param string $campoId
     *            Nombre del campo que es id de la tabla
     * @param string $campoPadreId
     *            Nombre del campo que es el FK sobre la misma tabla
     * @param string $campoDato
     *            Nombre del campo que tiene el dato
     * @param string $orderBy
     *            Para usar en ORDER BY $orderBy
     * @param int $padreId
     *            El id del nodo del cual comienza a generar el arbol, o 0 si es el root
     * @param int $nivel
     *            No enviar (es unicamente para recursividad)
     * @return array Formato: array("nivel" => X, "dato" => X, "id" => X, "padreId" => X);
     *
     *         Un codigo de ejemplo para hacer un arbol de categorias con links:
     *
     *         for ($i=0; $i<count($arbol); $i++){
     *         echo str_repeat("&nbsp;&nbsp;&nbsp;", $arbol[$i][nivel])."<a href='admin_categorias.php?c=".$arbol[$i][id]."'>".$arbol[$i][dato]."</a><br/>";
     *         }
     */
    public function getArbol($tabla, $campoId, $campoPadreId, $campoDato, $orderBy, $padreId = 0, $nivel = 0)
    {
        $tabla = real_escape_string($tabla);
        $campoId = real_escape_string($campoId);
        $campoPadreId = real_escape_string($campoPadreId);
        $campoDato = real_escape_string($campoDato);
        $orderBy = real_escape_string($orderBy);
        $padreId = real_escape_string($padreId);

        $result = $this->query("SELECT * FROM $tabla WHERE $campoPadreId='$padreId' ORDER BY $orderBy");

        $arrayRuta = array();

        while ($fila = $this->fetch_array($result)) {
            $arrayRuta[] = array(
                "nivel" => $nivel,
                "dato" => $fila[$campoDato],
                "id" => $fila[$campoId],
                "padreId" => $fila[$campoPadreId]
            );
            $retArrayFunc = $this->getArbol($tabla, $campoId, $campoPadreId, $campoDato, $orderBy, $fila[$campoId], $nivel + 1);
            $arrayRuta = array_merge($arrayRuta, $retArrayFunc);
        }

        return $arrayRuta;
    }

    /**
     * Retorna un array con la ruta tomada de un arbol jerarquico a partir del nodo indicado en $id.
     * Ej: array("33"=>"Autos", "74"=>"Ford", "85"=>"Falcon")
     * Esta funcion es para ser usada en tablas con este formato de campos: id, valor, idPadre
     *
     * @param string $tabla
     *            Nombre de la tabla
     * @param string $campoId
     *            Nombre del campo que es id de la tabla
     * @param string $campoPadreId
     *            Nombre del campo que es el FK sobre la misma tabla
     * @param string $campoDato
     *            Nombre del campo que tiene el dato
     * @param
     *            int El id del nodo del cual comienza a generar el path
     * @return array Formato: array("33"=>"Autos", "74"=>"Ford", "85"=>"Falcon")
     */
    public function getArbolRuta($tabla, $campoId, $campoPadreId, $campoDato, $id)
    {
        $fila = array();

        $tabla = real_escape_string($tabla);
        $campoId = real_escape_string($campoId);
        $campoPadreId = real_escape_string($campoPadreId);
        $campoDato = real_escape_string($campoDato);
        $id = real_escape_string($id);

        if ($id == 0)
            return;

        $arrayRuta = array();

        $result = $this->query("SELECT $campoId, $campoDato, $campoPadreId FROM $tabla WHERE $campoId='$id'");

        while ($this->num_rows($result) == 1 or $fila[$campoId] == '0') {
            $fila = $this->fetch_assoc($result);
            $arrayRuta[$fila[$campoId]] = $fila[$campoDato];
            $result = $this->query("SELECT $campoId, $campoDato, $campoPadreId FROM $tabla WHERE $campoId='" . $fila[$campoPadreId] . "'");
        }

        $arrayRuta = array_reverse($arrayRuta, true);

        return $arrayRuta;
    }

    /**
     * Realiza un INSERT en una tabla usando los datos que vienen por POST, donde el nombre de cada campo es igual al nombre en la tabla.
     * Esto es especialmente util para backends, donde con solo agregar un campo al <form> ya estamos agregandolo al query automaticamente
     *
     * Ejemplos:
     *
     * Para casos como backend donde no hay que preocuparse por que el usuario altere los campos del POST se puede omitir el parametro $campos
     * $db->insertFromPost("usuarios");
     *
     * Si ademas queremos agregar algo al insert
     * $db->insertFromPost("usuarios", "", "fechaAlta=NOW()");
     *
     * Este es el caso mas seguro, se indican cuales son los campos que se tienen que insertar
     * $db->insertFromPost("usuarios", array("nombre", "email"));
     *
     * @param string $tabla
     *            Nombre de la tabla en BD
     * @param array $campos
     *            Campos que vienen por $_POST que queremos insertar, ej: array("nombre", "email")
     * @param string $adicionales
     *            Si queremos agregar algo al insert, ej: fechaAlta=NOW()
     * @return boolean El resultado de la funcion query
     */
    public function insertFromPost(string $tabla, $campos = array(), string $adicionales = "")
    {
        $camposInsert = "";

        foreach ($_POST as $campo => $valor) {
            if (is_array($campos) and count($campos) > 0) {
                // solo los campos indicados
                if (in_array($campo, $campos)) {
                    if ($camposInsert != "") {
                        $camposInsert .= ", ";
                    }
                    $camposInsert .= "`$campo`='" . real_escape_string($valor) . "'";
                }
            } else {
                // van todos los campos que vengan en $_POST
                if ($camposInsert != "") {
                    $camposInsert .= ", ";
                }
                $camposInsert .= "`$campo`='" . real_escape_string($valor) . "'";
            }
        }

        // campos adicionales
        if ($adicionales != "") {
            if ($camposInsert != "") {
                $camposInsert .= ", ";
            }
            $camposInsert .= $adicionales;
        }

        return $this->query("INSERT INTO $tabla SET $camposInsert");
    }

    /**
     * Realiza un UPDATE en una tabla usando los datos que vienen por POST, donde el nombre de cada campo es igual al nombre en la tabla.
     * Esto es especialmente util para backends, donde con solo agregar un campo al <form> ya estamos agregandolo al query automaticamente
     *
     * Ejemplos:
     *
     * Para casos como backend donde no hay que preocuparse por que el usuario altere los campos del POST se puede omitir el parametro $campos
     * $db->updateFromPost("usuarios");
     *
     * Si ademas queremos agregar algo al update
     * $db->updateFromPost("usuarios", "", "fechaModificacion=NOW()");
     *
     * Este es el caso mas seguro, se indican cuales son los campos que se tienen que insertar
     * $db->updateFromPost("usuarios", array("nombre", "email"));
     *
     * @param string $tabla
     *            Nombre de la tabla en BD
     * @param string $where
     *            Condiciones para el WHERE. Ej: id=2. Tambien puede agregarse un LIMIT para los casos donde solo se necesita actualizar un solo registro. Ej: id=3 LIMIT 1. El limit en este caso es por seguridad
     * @param array $campos
     *            Campos que vienen por $_POST que queremos insertar, ej: array("nombre", "email")
     * @param string $adicionales
     *            Si queremos agregar algo al insert, ej: fechaAlta=NOW()
     * @return boolean El resultado de la funcion query
     */
    public function updateFromPost(string $tabla, string $where, $campos = array(), string $adicionales = "")
    {
        $camposInsert = "";
        // campos de $_POST
        foreach ($_POST as $campo => $valor) {
            if (is_array($campos) and count($campos) > 0) {
                // solo los campos indicados
                if (in_array($campo, $campos)) {
                    if ($camposInsert != "")
                        $camposInsert .= ", ";
                    $camposInsert .= "`$campo`='" . real_escape_string($valor) . "'";
                }
            } else {
                // van todos los campos que vengan en $_POST
                if ($camposInsert != "")
                    $camposInsert .= ", ";
                $camposInsert .= "`$campo`='" . real_escape_string($valor) . "'";
            }
        }

        // campos adicionales
        if ($adicionales != "") {
            if ($camposInsert != "")
                $camposInsert .= ", ";
            $camposInsert .= $adicionales;
        }

        return $this->query("UPDATE $tabla SET $camposInsert WHERE $where");
    }

    /**
     * Imprime los parametros pasados a la consulta
     *
     * @param string $str_query
     *            - Consulta
     * @param array $parametros
     *            - Parametros pasados
     */
    private function imprimirParam($str_query, $parametros)
    {
        echo "<Br /><Br />";

        if (self::$dbtype == 'mysql') {} elseif (self::$dbtype == 'oracle') {
            $cantidad = substr_count($str_query, ':');

            $para = explode(':', $str_query);

            for ($i = 0; $i < $cantidad; $i ++) {
                $e = $i + 1;

                $paraY = explode(' ', $para[$e]);

                $paraY[0] = trim(str_replace(",", "", $paraY[0]));

                $paraY[0] = str_replace(")", "", $paraY[0]);

                echo "-- :" . $paraY[0] . " = " . $parametros[$i] . "<Br />";
            }
        } elseif (self::$dbtype == 'mssql') {
            $cantidad = substr_count($str_query, '?');

            for ($i = 0; $i < $cantidad; $i ++) {
                echo "-- ?" . $i . " = " . $parametros[$i] . "<Br />";
            }
        }
    }

    /**
     * Devuelve el valor de un campo de la fila obtenida
     *
     * @param mixed $result
     * @param mixed $row
     * @param string $field.
     *
     * @deprecated Ya no se utilizaria en la nueva version.
     */
    public function result($result, $row, $field = null)
    {
        if (self::$dbtype == 'mysql') {
            return $this->mysqli_result($result, $row, $field);
        } elseif (self::$dbtype == 'oracle') {
            return oci_result($result, $field);
        } elseif (self::$dbtype == 'mssql') {
            // return mssql_result ($result, $row, $field);
            // return odbc_result ($result, $field);
            return sqlsrv_get_field($result, $field);
        }
    }

    /**
     * Ajustar el puntero de resultado a una fila arbitraria del resultado
     *
     * @param object $result
     *            - Resultado de la consulta con el cual trabajar.
     * @param int $row_number
     *            - Numero de fila a la cual apuntar.
     * @return object El resultado con el puntero modificado.
     *
     * @deprecated Ya no se utilizara en proximas versiones. Siempre hay una solución mejor, más clara y más fácil que buscar.
     */
    public function data_seek($result, $row_number)
    {
        if (self::$dbtype == 'mysql') {
            return mysqli_data_seek($result, $row_number);
        }
        // elseif (self::$dbtype == 'mssql')
        // {
        // return mssql_data_seek ($result, $row_number);
        // }
    }

    /**
     * Realiza el commit en caso de que el autocommit este off
     */
    public function commit()
    {
        self::$db->commit();
    }

    /**
     * Realiza el rollback en caso de que el autocommit este off
     */
    public function rollback()
    {
        self::$db->rollBack();
    }

    /**
     * Genera un string para agregar a la consulta convirtiendo una fecha en string
     * Usa el tipo correspondiente para cada motor.
     *
     * @author iberlot <@> iberlot@usal.edu.ar
     * @name toChar
     *
     * @param string $campo
     *            - Nombre del campo del que se extrae la fecha
     * @param string $nombre
     *            - Nombre que eremos que tenga luego AS ......
     * @param string $mascara
     *            - Si queremos que use alguna mascara personalizada
     * @throws Exception
     * @return string
     */
    public function toChar($campo, $nombre = "", $mascara = "")
    {
        if ($nombre != "") {
            $nombre = " AS " . $nombre;
        } else {
            $nombre = "";
        }

        if (self::$dbtype == 'mysql') {
            if ($mascara == "") {
                $mascara = "%Y-%m-%d";
            }

            $retorno = "DATE_FORMAT(" . $campo . ",'" . $mascara . "') " . $nombre;
        } elseif (self::$dbtype == 'oracle') {
            if ($mascara == "") {
                $mascara = "RRRR-MM-DD";
            }

            $retorno = "TO_CHAR(" . $campo . ", '" . $mascara . "') " . $nombre;
        } elseif (self::$dbtype == 'mssql') {
            $retorno = "CONVERT(VARCHAR(10), " . $campo . ", 120) " . $nombre;
        } else {
            throw new Exception('ERROR: No hay definido un tipo de base de datos');
        }

        return $retorno;
    }

    /**
     * Genera un string para agregar a la consulta convirtiendo un string en una fecha
     * Usa el tipo correspondiente para cada motor.
     *
     * @author iberlot <@> iberlot@usal.edu.ar
     * @name toDate
     *
     * @param string $valor
     *            - Dato a convertir en fecha
     * @param string $mascara
     *            - Si queremos que use alguna mascara personalizada
     * @throws Exception
     * @return string
     */
    public function toDate($valor, $mascara = "")
    {
        if (self::$dbtype == 'mysql') {
            if ($mascara == "") {
                $mascara = "%Y-%m-%d";
            } else {
                $mascara = str_replace('YYYY', '%Y', $mascara);
                $mascara = str_replace('RRRR', '%Y', $mascara);
                $mascara = str_replace('yyyy', '%Y', $mascara);
                $mascara = str_replace('yy', '%y', $mascara);
                $mascara = str_replace('YY', '%y', $mascara);
                $mascara = str_replace('mm', '%m', $mascara);
                $mascara = str_replace('MM', '%m', $mascara);
                $mascara = str_replace('dd', '%d', $mascara);
                $mascara = str_replace('DD', '%d', $mascara);
            }

            $retorno = " STR_TO_DATE('" . $valor . "','" . $mascara . "') ";
        } elseif (self::$dbtype == 'oracle') {
            if ($mascara == "") {
                $mascara = "RRRR-MM-DD";
            }

            $retorno = "TO_DATE('" . $valor . "', '" . $mascara . "') ";
        } elseif (self::$dbtype == 'mssql') {
            $retorno = " CONVERT(DATETIME, " . $valor . ", 120) ";
        } else {
            throw new Exception('ERROR: No hay definido un tipo de base de datos');
        }

        return $retorno;
    }

    /**
     * es el equivalente a mysql_result.
     *
     * @param string $res
     *            - result
     * @param number $row
     * @param number $col
     * @return mixed
     */
    public function mysqli_result($res, $row = 0, $col = 0)
    {
        $numrows = mysqli_num_rows($res);
        if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
            mysqli_data_seek($res, $row);
            $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
            if (isset($resrow[$col])) {
                return $resrow[$col];
            }
        }
        return false;
    }

    /**
     * Recive un array con los campos a insertar en una tabla y el nombre de la tabla y en base a eso arma la consulta de insert y carga el array de parametros.
     *
     * @param String[] $array
     *            - Los valores del array van a ser el valor a insertar en la tabla y los indices el nombre del campo.
     * @param String $tabla
     *            - Nombre de la tabla en la que se va a insertar.
     * @param mixed[] $parametros
     *            - Array de parametros, se pasa por parametro y se borra antes de usar.
     * @return string - Retorna el string de la consulta de insercion preparada, adicionalmente el array parametros queda cargado con los parametros a utilizar.
     */
    public function prepararConsultaInsert($array, $tabla, &$parametros)
    {
        $parametros = array();
        $campos = array();
        $valores = array();

        foreach ($array as $clave => $valor) {
            if ((strpos($valor, "TO_DATE") === false) and (strpos(strtoupper($valor), "NEXTVAL") === false) and (strpos(strtoupper($valor), "SYSDATE") === false)) {
                $campos[] = " " . $clave . " ";
                $valores[] = " :" . $clave . " ";
                $parametros[] = $valor;
            } else {
                $campos[] = " " . $clave . " ";
                $valores[] = $valor;
                // $parametros[] = $valor;
            }
        }

        $campos = implode(", ", $campos);
        $valores = implode(", ", $valores);

        return "INSERT INTO $tabla (" . $campos . ") VALUES (" . $valores . ")";
    }

    /**
     * Recive un array con los campos a modificar en una tabla y el nombre de la tabla y en base a eso arma la consulta de Update y carga el array de parametros.
     *
     * @param String[] $array
     *            - Los valores del array van a ser el valor a modificar en la tabla y los indices el nombre del campo.
     * @param String $tabla
     *            - Nombre de la tabla en la que se va a modificar.
     * @param mixed[] $parametros
     *            - Array de parametros, se pasa por parametro y se borra antes de usar.
     * @param String[] $where
     *            - Los valores del array van a ser el valor a usar en el where y los indices el nombre del campo.
     *
     * @return string - Retorna el string de la consulta de modificacion preparada, adicionalmente el array parametros queda cargado con los parametros a utilizar.
     */
    public function prepararConsultaUpdate($array, $tabla, &$parametros, $where)
    {
        $parametros = array();
        $campos = array();

        foreach ($array as $clave => $valor) {
            if ((strpos($valor, "TO_DATE") === false) and (strpos(strtoupper($valor), "NEXTVAL") === false) and (strpos(strtoupper($valor), "SYSDATE") === false)) {
                if (strpos($valor, "!=") === false) {
                    $campos[] = " " . $clave . " = :" . $clave . " ";
                    $parametros[] = $valor;
                } else {
                    $campos[] = " " . $clave . " != :" . $clave . " ";
                    $parametros[] = substr($valor, (stripos($valor, "!=") + 2), -1);
                }
            } else {
                $campos[] = " " . $clave . " = " . $valor;
            }
        }
        $campos = implode(", ", $campos);

        foreach ($where as $clave => $valor) {
            if (strpos($valor, "TO_DATE") === false) {
                if (strpos($valor, "!=") === false) {
                    $wheres[] = " " . $clave . " = :" . $clave . " ";
                    $parametros[] = $valor;
                } else {
                    $wheres[] = " " . $clave . " != :" . $clave . " ";
                    $parametros[] = substr($valor, (stripos($valor, "!=") + 2), -1);
                }
            } else {
                $wheres[] = " " . $clave . " = " . $valor;
            }
        }
        $wheres = implode(" AND ", $wheres);
        if ($wheres != "") {
            $wheres = " AND " . $wheres;
        }

        return "UPDATE $tabla SET " . $campos . " WHERE 1=1 " . $wheres;
    }

    /**
     * Recive un array con los campos a buscar en una tabla y el nombre de la tabla y en base a eso arma la consulta de Select y carga el array de parametros.
     *
     * @param String $tabla
     *            - Nombre de la tabla en la que se va a modificar.
     * @param mixed[] $parametros
     *            - Array de parametros, se pasa por parametro y se borra antes de usar.
     * @param String $where
     *            - Los valores del array van a ser el valor a usar en el where y los indices el nombre del campo.
     * @param String[] $array
     *            - Los valores del array van a ser el valor a modificar en la tabla y los indices el nombre del campo.
     *
     * @return string - Retorna el string de la consulta de Select preparada, adicionalmente el array parametros queda cargado con los parametros a utilizar.
     */
    public function prepararConsultaSelect($tabla, &$parametros, $where = "1=1", $array = "*")
    {
        $parametros = array();
        $campos = $array;
        // $valores = array ();

        if (is_array($array)) {
            $campos = implode(", ", $array);
        } else {
            $campos = $array;
        }

        foreach ($where as $clave => $valor) {
            if (strpos($valor, "TO_DATE") === false) {
                if (strpos($valor, "!=") === false) {
                    $wheres[] = " " . $clave . " = :" . $clave . " ";
                    $parametros[] = $valor;
                } else {
                    $wheres[] = " " . $clave . " != :" . $clave . " ";
                    $parametros[] = substr($valor, (stripos($valor, "!=") + 2), -1);
                }
            } else {

                $wheres[] = " " . $clave . " = " . $valor;
            }
        }

        if ($wheres != "1=1" and $wheres != "" and !empty($wheres)) {
            $wheres = implode(" AND ", $wheres);
        } else {
            $wheres = "1=1";
        }

        return "SELECT " . $campos . " FROM " . $tabla . " WHERE " . $wheres;
    }

    /**
     * Prepara y ejecuta la consulta de Select.
     *
     * @param String $tabla
     *            - Nombre de la tabla donde se va a realizar el Select.
     * @param String[] $where
     *            - Los valores del array van a ser el valor a usar en el where y los indices el nombre del campo.
     * @param String[] $campos
     *            - Array con los campos que se quieren buscar.
     *
     * @throws Exception - Retorno de errores.
     * @return boolean true en caso de estar todo OK o el error en caso de que no.
     */
    function realizarSelect($tabla, $where = "1=1", $campos = "*")
    {
        $parametros = array();

        $sql = $this->prepararConsultaSelect($tabla, $parametros, $where, $campos);

        $result = $this->query($sql, true, $parametros);

        if ($result) {
            return $this->fetch_array($result);
        } else {
            throw new Exception('Error al realizar el select en ' . $tabla . '.', -4);
        }
    }

    /**
     * Prepara y ejecuta la consulta de Select.
     *
     * @param String $tabla
     *            - Nombre de la tabla donde se va a realizar el Select.
     * @param String[] $where
     *            - Los valores del array van a ser el valor a usar en el where y los indices el nombre del campo.
     * @param String[] $campos
     *            - Array con los campos que se quieren buscar.
     *
     * @throws Exception - Retorno de errores.
     * @return boolean true en caso de estar todo OK o el error en caso de que no.
     */
    function realizarSelectAll($tabla, $where = "1=1", $campos = "*")
    {
        $parametros = array();

        $sql = $this->prepararConsultaSelect($tabla, $parametros, $where, $campos);

        $result = $this->query($sql, true, $parametros);

        if ($result) {
            return $this->fetch_all($result);
        } else {
            throw new Exception('Error al realizar el select en ' . $tabla . '.', -4);
        }
    }

    /**
     * Prepara y ejecuta la consulta de Update.
     *
     * @param mixed[] $datos
     *            - Los valores del array van a ser el valor a Update en la tabla y los indices el nombre del campo.
     * @param String $tabla
     *            - Nombre de la tabla donde se va a realizar el Update.
     * @param String $where
     *            - Los valores del array van a ser el valor a usar en el where y los indices el nombre del campo.
     *
     * @throws Exception - Retorno de errores.
     * @return boolean true en caso de estar todo OK o el error en caso de que no.
     */
    function realizarUpdate($datos, $tabla, $where)
    {
        $parametros = array();

        $sql = $this->prepararConsultaUpdate($datos, $tabla, $parametros, $where);

        if ($this->query($sql, true, $parametros)) {
            return true;
        } else {
            throw new Exception('Error al realizar el update en ' . $tabla . '. No se puedo hacer el update.', -5);
        }
    }

    /**
     * Prepara y ejecuta la consulta de Insert.
     *
     * @param mixed[] $datos
     *            - Los valores del array van a ser el valor a insertar en la tabla y los indices el nombre del campo.
     * @param String $tabla
     *            - Nombre de la tabla donde se va a realizar el insert.
     * @throws Exception - Retorno de errores.
     * @return boolean true en caso de estar todo OK o el error en caso de que no.
     */
    function realizarInsert($datos, $tabla)
    {
        $parametros = array();

        $sql = $this->prepararConsultaInsert($datos, $tabla, $parametros);

        if ($this->query($sql, true, $parametros)) {
            return true;
        } else {
            throw new Exception('Error al insertar en ' . $tabla . '. No se puedo hacer el insert.', -6);
        }
    }

    /**
     * Retorna el valor del atributo $debug
     *
     * @return boolean $debug el dato de la variable.
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Retorna el valor del atributo $grabarArchivoLogError
     *
     * @return boolean $grabarArchivoLogError el dato de la variable.
     */
    public function getGrabarArchivoLogError()
    {
        return $this->grabarArchivoLogError;
    }

    /**
     * Retorna el valor del atributo $grabarArchivoLogQuery
     *
     * @return boolean $grabarArchivoLogQuery el dato de la variable.
     */
    public function getGrabarArchivoLogQuery()
    {
        return $this->grabarArchivoLogQuery;
    }

    /**
     * Retorna el valor del atributo $mostrarErrores
     *
     * @return boolean $mostrarErrores el dato de la variable.
     */
    public function getMostrarErrores()
    {
        return $this->mostrarErrores;
    }

    /**
     * Retorna el valor del atributo $dieOnError
     *
     * @return boolean $dieOnError el dato de la variable.
     */
    public function isDieOnError()
    {
        return $this->dieOnError;
    }

    /**
     * Retorna el valor del atributo $emailAvisoErrorSql
     *
     * @return mixed $emailAvisoErrorSql el dato de la variable.
     */
    public function getEmailAvisoErrorSql()
    {
        return $this->emailAvisoErrorSql;
    }

    /**
     * Retorna el valor del atributo $dbtype
     *
     * @return string $dbtype el dato de la variable.
     */
    public function getDbtype()
    {
        return self::$dbtype;
    }

    /**
     * Retorna el valor del atributo $dbHost
     *
     * @return string $dbHost el dato de la variable.
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * Retorna el valor del atributo $dbUser
     *
     * @return string $dbUser el dato de la variable.
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * Retorna el valor del atributo $dbPass
     *
     * @return string $dbPass el dato de la variable.
     */
    public function getDbPass()
    {
        return $this->dbPass;
    }

    /**
     * Retorna el valor del atributo $dbName
     *
     * @return string $dbName el dato de la variable.
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Retorna el valor del atributo $charset
     *
     * @return string $charset el dato de la variable.
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Retorna el valor del atributo $commit
     *
     * @return boolean $commit el dato de la variable.
     */
    public function isCommit()
    {
        return $this->commit;
    }

    /**
     * Setter del parametro $debug de la clase.
     *
     * @param boolean $debug
     *            dato a cargar en la variable.
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Setter del parametro $grabarArchivoLogError de la clase.
     *
     * @param boolean $grabarArchivoLogError
     *            dato a cargar en la variable.
     */
    public function setGrabarArchivoLogError($grabarArchivoLogError)
    {
        $this->grabarArchivoLogError = $grabarArchivoLogError;
    }

    /**
     * Setter del parametro $grabarArchivoLogQuery de la clase.
     *
     * @param boolean $grabarArchivoLogQuery
     *            dato a cargar en la variable.
     */
    public function setGrabarArchivoLogQuery($grabarArchivoLogQuery)
    {
        $this->grabarArchivoLogQuery = $grabarArchivoLogQuery;
    }

    /**
     * Setter del parametro $mostrarErrores de la clase.
     *
     * @param boolean $mostrarErrores
     *            dato a cargar en la variable.
     */
    public function setMostrarErrores($mostrarErrores)
    {
        $this->mostrarErrores = $mostrarErrores;
    }

    /**
     * Setter del parametro $dieOnError de la clase.
     *
     * @param boolean $dieOnError
     *            dato a cargar en la variable.
     */
    public function setDieOnError($dieOnError)
    {
        $this->dieOnError = $dieOnError;
    }

    /**
     * Setter del parametro $emailAvisoErrorSql de la clase.
     *
     * @param mixed $emailAvisoErrorSql
     *            dato a cargar en la variable.
     */
    public function setEmailAvisoErrorSql($emailAvisoErrorSql)
    {
        $this->emailAvisoErrorSql = $emailAvisoErrorSql;
    }

    /**
     * Setter del parametro $dbtype de la clase.
     *
     * @param string $dbtype
     *            dato a cargar en la variable.
     */
    public static function setDbTipo($dbtype)
    {
        if (strtolower($dbtype) == "mysql" or strtolower($dbtype) == "oracle" or strtolower($dbtype) == "mssql" or strtolower($dbtype) == "pgsql") {
            self::$dbtype = strtolower($dbtype);
        } else {
            throw new Exception("Tipo de base de datos incorrecata.");
        }
    }

    /**
     * Setter del parametro $dbHost de la clase.
     *
     * @param string $dbHost
     *            dato a cargar en la variable.
     */
    public static function setDbHost(string $dbHost)
    {
        self::$dbHost = $dbHost;
    }

    /**
     * Setter del parametro $dbUser de la clase.
     *
     * @param string $dbUser
     *            dato a cargar en la variable.
     */
    public static function setDbUser($dbUser)
    {
        self::$dbUser = $dbUser;
    }

    /**
     * Setter del parametro $dbPass de la clase.
     *
     * @param string $dbPass
     *            dato a cargar en la variable.
     */
    public static function setDbPass($dbPass)
    {
        self::$dbPass = $dbPass;
    }

    /**
     * Setter del parametro $dbName de la clase.
     *
     * @param string $dbName
     *            dato a cargar en la variable.
     */
    public static function setDbName($dbName)
    {
        self::$dbName = $dbName;
    }

    /**
     * Setter del parametro $charset de la clase.
     *
     * @param string $charset
     *            dato a cargar en la variable.
     */
    public static function setCharset($charset)
    {
        self::$charset = $charset;
    }

    /**
     * Setter del parametro $commit de la clase.
     *
     * @param boolean $commit
     *            dato a cargar en la variable.
     */
    public function setCommit($commit)
    {
        self::$commit = $commit;
    }

    /**
     * Retorna el valor del campo $db
     *
     * @return PDO
     */
    private static function getDb()
    {
        return self::$db;
    }

    /**
     * Funcion de carga de datos del parametro $db
     *
     * @param PDO $db
     */
    private static function setDb($db)
    {
        self::$db = $db;
    }
}

