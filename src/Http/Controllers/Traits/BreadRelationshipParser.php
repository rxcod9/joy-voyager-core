<?php

namespace Joy\VoyagerCore\Http\Controllers\Traits;

use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser as BaseBreadRelationshipParser;
use TCG\Voyager\Models\DataType;

trait BreadRelationshipParser
{
    use BaseBreadRelationshipParser;

    protected function removeRelationshipField(DataType $dataType, $bread_type = 'browse')
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
