<?php

namespace App\Utils;

class ArrayUtils
{
    /**
     * Déduplication des tableaux multidimensionnels en utilisant une clé spécifiée
     *
     * @param string $key La clé à utiliser pour la déduplication
     * @param array ...$arrays Les tableaux à fusionner et à dédupliquer
     * @return array Le tableau fusionné et dédupliqué
     */
    public static function deduplicateMultidimensionalArraysByKey(string $key, array ...$arrays): array
    {
        $mergedArray = array_merge(...$arrays);

        $uniqueArray = [];
        foreach ($mergedArray as $subArray) {
            if (isset($subArray[$key])) {
                $value = $subArray[$key];
                $uniqueArray[$value] = $subArray;
            }
        }

        return array_values($uniqueArray);
    }
}