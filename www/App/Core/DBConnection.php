<?php
/**
 * Esta clase se va a encargar de todo lo referente a las bases de datos.
 * Recuperacion e insercion de datos, conexiones ect.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @version 3.1.3
 *          (A partir de la version 3.0 - Se actualizaron las funciones obsoletas y corrigieron algunos errores.)
 *          (A partir de la version 3.1 - Se incluye la opcion de parametrizar las consultas.)
 *          (A partir de la version 3.1.1 - Se Se modifico para que los parametros no contemplaran el parentesis de sierre, ademas de que en el
 *          debug el valor de estos apareciera comentado)
 *          (A partir de la version 3.1.2 - Se corrigio el error que no mostraba los errores generados en oracle.)
 *          (A partir de la version 3.1.3 - Se agragan las funciones creadoras de consultas por medio de arrays de datos.)
 *
 * @category Edicion
 *
 * @todo Hay que acomodar el manejo de errores para que quede mas practico.
 *
 * @link config/includes.php - Archivo con todos los includes del sistema
 *
 */

/*
 * Querido programador:
 *
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 *
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 *
 * totalHorasPerdidasAqui = 177
 *
 */
namespace www\App\Core;

use Exception;

/**
 *
 * @author IVANB
 *        
 */
class DBConnection
{

    /**
     * Muestra por pantalla diferentes codigos para facilitar el debug
     *
     * @var bool
     */
    public static $debug = false;

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
    public static $mostrarErrores = true;

    /**
     * Usar die() si hay un error de sql.
     * Esto es util para etapa de desarrollo *
     *
     * @var boolean
     */
    public static $dieOnError = false;

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
    private static $commit = true;

    /**
     * Propiedad estática para almacenar la conexión.
     *
     * @var DB
     */
    private static $db = null;

    /**
     * Definimos al constructor como privado para evitar que cualquiera,
     * salvo esta misma clase, pueda crear una instancia.
     */
    private function __construct()
    {}

    /**
     * Realiza la conexion a la base de datos
     * cambia la conexion dependiendo de $dbtype
     */
    public static function connect()
    {
        self::$db = new DB(self::$dbHost, self::$dbName, self::$dbUser, self::$dbPass);

        self::$db->setDebug(self::$debug);
        self::$db->setDieOnError(self::$dieOnError);
        self::$db->setMostrarErrores(self::$mostrarErrores);
    }

    /**
     * Retorna una conexión a la base de datos en modo Singleton.
     *
     * @return DB
     */
    public static function getConnection()
    {
        // Si no tenemos conexión, la abrimos.
        if (is_null(self::$db)) {

            self::connect();
        }

        if (self::$db->isDebug() != self::$debug) {
            self::$db->setDebug(self::$debug);
            self::$db->setDieOnError(self::$dieOnError);
            self::$db->setMostrarErrores(self::$mostrarErrores);
        }
        // Retornamos la conexión.
        return self::$db;
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
    public static function setDebug($debu)
    {
        self::$debug = $debu;
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
    public static function setMostrarErrores($mostrarErrores)
    {
        self::$mostrarErrores = $mostrarErrores;
    }

    /**
     * Setter del parametro $dieOnError de la clase.
     *
     * @param boolean $dieOnError
     *            dato a cargar en la variable.
     */
    public static function setDieOnError($dieOnError)
    {
        self::$dieOnError = $dieOnError;
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
     * @return DB
     */
    private static function getDb()
    {
        return self::$db;
    }

    /**
     * Funcion de carga de datos del parametro $db
     *
     * @param DB $db
     */
    private static function setDb($db)
    {
        self::$db = $db;
    }
}
?>