<?php
namespace www\App\Utilities;

class Str
{

    /**
     * Convierte el $string en formato snake_case a camelCase.
     *
     * @param string $string
     * @return string
     */
    public static function snakeToCamel(string $string): string
    {
        $partes = explode("_", $string);
        foreach ($partes as $key => $parte) {
            if ($key !== 0) {
                $partes[$key] = ucfirst($parte);
            }
        }
        return implode('', $partes);
    }
}
