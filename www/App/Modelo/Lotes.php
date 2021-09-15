<?php
namespace www\App\Modelo;

/**
 *
 * @author IVANB
 *
 */
class Lotes implements iModeloStandar
{

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function jsonSerialize()
    {}

    public function nuevo(): bool
    {}

    public function deleteThis(): bool
    {}

    public function getById(int $id): object
    {}

    public function deleteById(int $id): bool
    {}

    public function getByThis(): object
    {}

    public function updateThis(): bool
    {}

    public function updateById(int $id, array $parametros): bool
    {}
}

