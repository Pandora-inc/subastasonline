<?php
namespace www\App\Modelo;

use Exception;
use JsonSerializable;

/**
 *
 * @author IVANB
 *
 */
interface iModeloStandar extends JsonSerializable
{

    const BASE_DB = "admarsar_sarachaga.";

    const BASE_DB_SUBASTAS = "admarsar_subastas_online.";

    /**
     * Carga en la base de datos el registro con todos los datos asociados en la clase.
     *
     * @return bool
     */
    function nuevo(): bool;

    /**
     * Busca un elemento en la base usando en identificador pasado como parametro
     * y lo retorna como un objeto.
     *
     * @param int $id
     * @throws Exception
     * @return self
     */
    function getById(int $id): object;

    /**
     * Actualiza el registro en la base usando como identificador el id pasado como parametro y extrayendo los datos del array parametros.
     *
     * @param int $id
     * @param array $parametros
     * @return bool
     */
    function updateById(int $id, array $parametros): bool;

    /**
     * Realiza el borrado en la base del elemento usando el parametro id como identificador de la consulta.
     *
     * @param int $id
     * @return bool
     */
    function deleteById(int $id): bool;

    /**
     * Busca un elemento en la base usando en identificador establecido como atributo de la clase
     * y lo retorna como un objeto.
     *
     * @throws Exception
     * @return self
     */
    function getByThis(): object;

    /**
     * Actualiza el registro en base a los datos de la clase.
     *
     * @return bool
     */
    function updateThis(): bool;

    /**
     * Elimina el registro en base a los datos de la clase.
     *
     * @return bool
     */
    function deleteThis(): bool;
}

