<?php

namespace Joy\VoyagerCore\Http\Controllers\Traits;

use Illuminate\Http\Request;
use InvalidArgumentException;
use TCG\Voyager\Facades\Voyager;

trait MorphToRelationAction
{
    /**
     * Get BREAD relations data.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function morphToRelation(Request $request)
    {
        $slug     = $this->getSlug($request);
        $page     = $request->input('page');
        $on_page  = 50;
        $search   = $request->input('search', false);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        
        $method = $request->input('method', 'add');

        $typeColumnValue = $request->input('type-column-value');
        
        $model = app($dataType->model_name);
        if ($method != 'add') {
            $model = $model->find($request->input('id'));
        }

        $this->authorize($method, $model);

        $rows = $dataType->{$method . 'Rows'};
        foreach ($rows as $key => $row) {
            if ($row->field === $request->input('type')) {
                $options = $row->details;
                if(!collect((array) $options->types)->contains('model', '=', $typeColumnValue)) {
                    throw new InvalidArgumentException('Invalid type-column-value');
                }

                $options = collect((array) $options->types)->first(function($item) use($typeColumnValue) {
                    return $item->model === $typeColumnValue;
                });
                $model   = app($typeColumnValue);
                $skip    = $on_page * ($page - 1);

                $additional_attributes = $model->additional_attributes ?? [];

                // Apply local scope if it is defined in the relationship-options
                if (isset($options->scope) && $options->scope != '' && method_exists($model, 'scope' . ucfirst($options->scope))) {
                    $model = $model->{$options->scope}();
                }

                // If search query, use LIKE to filter results depending on field label
                if ($search) {
                    // If we are using additional_attribute as label
                    if (in_array($options->label, $additional_attributes)) {
                        $relationshipOptions = $model->get();
                        $relationshipOptions = $relationshipOptions->filter(function ($model) use ($search, $options, $type) {
                            return stripos($model->{$options->label}, $search) !== false;
                        });
                        $total_count         = $relationshipOptions->count();
                        $relationshipOptions = $relationshipOptions->forPage($page, $on_page);
                    } else {
                        $total_count         = $model->where($options->label, 'LIKE', '%' . $search . '%')->count();
                        $relationshipOptions = $model->take($on_page)->skip($skip)
                            ->where($options->label, 'LIKE', '%' . $search . '%')
                            ->get();
                    }
                } else {
                    $total_count         = $model->count();
                    $relationshipOptions = $model->take($on_page)->skip($skip)->get();
                }

                $results = [];

                if (!$row->required && !$search && $page == 1) {
                    $results[] = [
                        'id'   => '',
                        'text' => __('voyager::generic.none'),
                    ];
                }

                // Sort results
                if (!empty($options->sort->field)) {
                    if (!empty($options->sort->direction) && strtolower($options->sort->direction) == 'desc') {
                        $relationshipOptions = $relationshipOptions->sortByDesc($options->sort->field);
                    } else {
                        $relationshipOptions = $relationshipOptions->sortBy($options->sort->field);
                    }
                }

                foreach ($relationshipOptions as $relationshipOption) {
                    $results[] = [
                        'id'   => $relationshipOption->{$options->key},
                        'text' => $relationshipOption->{$options->label},
                    ];
                }

                return response()->json([
                    'results'    => $results,
                    'pagination' => [
                        'more' => ($total_count > ($skip + $on_page)),
                    ],
                ]);
            }
        }

        // No result found, return empty array
        return response()->json([], 404);
    }
}
