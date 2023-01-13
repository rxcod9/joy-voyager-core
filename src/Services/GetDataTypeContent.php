<?php

declare(strict_types=1);

namespace Joy\VoyagerCore\Services;

use TCG\Voyager\Models\DataType;

/**
 * Class VoyagerCoreServiceProvider
 *
 * @category  Package
 * @package   JoyVoyagerCore
 * @author    Ramakant Gangwar <gangwar.ramakant@gmail.com>
 * @copyright 2021 Copyright (c) Ramakant Gangwar (https://github.com/rxcod9)
 * @license   http://github.com/rxcod9/joy-voyager-core/blob/main/LICENSE New BSD License
 * @link      https://github.com/rxcod9/joy-voyager-core
 */
class GetDataTypeContent
{
    /**
     * Boot
     *
     * @return void
     */
    public function handle(DataType $dataType, $id)
    {
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        return $dataTypeContent;
    }
}
