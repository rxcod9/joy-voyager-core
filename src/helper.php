<?php

use Illuminate\Support\Str;
use Joy\VoyagerCore\Models\Via;
use Symfony\Component\VarDumper\VarDumper;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;

// if (! function_exists('joyVoyagerCore')) {
//     /**
//      * Helper
//      */
//     function joyVoyagerCore($argument1 = null)
//     {
//         //
//     }
// }

if (!function_exists('isInPatterns')) {
    /**
     * Helper
     */
    function isInPatterns($key, $patterns)
    {
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $key)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isDataRowInPatterns')) {
    /**
     * Helper
     */
    function isDataRowInPatterns($dataRow, $dataRowPatterns)
    {
        foreach ($dataRowPatterns as $pattern) {
            if (
                Str::is($pattern, $dataRow->field) ||
                (optional($dataRow->details)->column && Str::is($pattern, optional($dataRow->details)->column)) ||
                (optional($dataRow->details)->type_column && Str::is($pattern, optional($dataRow->details)->type_column))
            ) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('dataRowByField')) {
    /**
     * DataRow by field
     *
     * @param DataRow
     */
    function dataRowByField($field): DataRow
    {
        return Voyager::model('DataRow')->where('field', $field)->firstOrFail();
    }
}

if (!function_exists('dataTypeByModel')) {
    /**
     * DataType by model
     *
     * @param DataType
     */
    function dataTypeByModel($model): DataType
    {
        return Voyager::model('DataType')->where('model_name', $model)->firstOrFail();
    }
}

if (!function_exists('removeRelationshipField')) {
    function removeRelationshipField(DataType $dataType, $bread_type = 'browse')
    {
        $forgetKeys = [];
        foreach ($dataType->{$bread_type . 'Rows'} as $key => $row) {
            if ($row->type == 'relationship') {
                if ($row->details->type == 'belongsTo') {
                    $relationshipField = @$row->details->column;
                    $keyInCollection   = key($dataType->{$bread_type . 'Rows'}->where('field', '=', $relationshipField)->toArray());
                    array_push($forgetKeys, $keyInCollection);
                } elseif ($row->details->type == 'morphTo') {
                    $relationshipField     = @$row->details->column;
                    $relationshipTypeField = @$row->details->type_column;
                    $keyInCollection       = key($dataType->{$bread_type . 'Rows'}->where('field', '=', $relationshipField)->toArray());
                    $typeKeyInCollection   = key($dataType->{$bread_type . 'Rows'}->where('field', '=', $relationshipTypeField)->toArray());
                    array_push($forgetKeys, $keyInCollection);
                    array_push($forgetKeys, $typeKeyInCollection);
                }
            }
        }

        foreach ($forgetKeys as $forget_key) {
            $dataType->{$bread_type . 'Rows'}->forget($forget_key);
        }

        // Reindex collection
        $dataType->{$bread_type . 'Rows'} = $dataType->{$bread_type . 'Rows'}->values();
    }
}

if (!function_exists('pr')) {
    /**
     * @return never
     */
    function pr(...$vars)
    {
        if (!in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        // exit(1);
    }
}

if (!function_exists('via')) {
    /**
     * @return ?Via
     */
    function via(
        DataRow $row,
        $viaIndex = null,
        ?Via $via = null
    ): ?Via {
        $newVia = Voyager::model('Via');
        $newVia->setOptions(
            $row,
            $viaIndex,
            $via
        );
        return $newVia;
    }
}
