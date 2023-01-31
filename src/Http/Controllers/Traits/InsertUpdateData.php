<?php

namespace Joy\VoyagerCore\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Http\Controllers\ContentTypes\File;
use TCG\Voyager\Http\Controllers\ContentTypes\Relationship;

trait InsertUpdateData
{
    public function insertUpdateData($request, $slug, $rows, $data, $via = null, $viaCallback = null)
    {
        $multi_select = [];
        $has_many     = [];
        $has_one      = null;

        // Pass $rows so that we avoid checking unused fields
        $request->attributes->add(['breadRows' => $rows->pluck('field')->toArray()]);

        /*
         * Prepare Translations and Transform data
         */
        $translations = is_bread_translatable($data)
                        ? $data->prepareTranslations($request)
                        : [];

        foreach ($rows as $row) {
            // if the field for this row is absent from the request, continue
            // checkboxes will be absent when unchecked, thus they are the exception
            if (!$request->hasFile($row->field) && !$request->has($row->field) && $row->type !== 'checkbox') {
                // if the field is a belongsToMany relationship, don't remove it
                // if no content is provided, that means the relationships need to be removed
                if (isset($row->details->type) && $row->details->type !== 'belongsToMany' && $row->details->type !== 'hasMany' && $row->details->type !== 'hasOne') {
                    continue;
                }
            }

            // Value is saved from $row->details->column row
            if ($row->type == 'relationship' && $row->details->type == 'belongsTo') {
                continue;
            }

            $content = $this->getContentBasedOnType($request, $slug, $row, $row->details);

            if ($row->type == 'relationship' && $row->details->type != 'belongsToMany' && $row->details->type != 'hasMany' && $row->details->type != 'hasOne') {
                $row->field = @$row->details->column;
            }

            /*
             * merge ex_images/files and upload images/files
             */
            if (in_array($row->type, ['multiple_images', 'file']) && !is_null($content)) {
                if (isset($data->{$row->field})) {
                    $ex_files = json_decode($data->{$row->field}, true);
                    if (!is_null($ex_files)) {
                        $content = json_encode(array_merge($ex_files, json_decode($content)));
                    }
                }
            }

            if (is_null($content)) {
                // If the image upload is null and it has a current image keep the current image
                if ($row->type == 'image' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                    $content = $data->{$row->field};
                }

                // If the multiple_images upload is null and it has a current image keep the current image
                if ($row->type == 'multiple_images' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                    $content = $data->{$row->field};
                }

                // If the file upload is null and it has a current file keep the current file
                if ($row->type == 'file') {
                    $content = $data->{$row->field};
                    if (!$content) {
                        $content = json_encode([]);
                    }
                }

                if ($row->type == 'password') {
                    $content = $data->{$row->field};
                }
            }

            if ($row->type == 'relationship' && $row->details->type == 'belongsToMany') {
                // Only if select_multiple is working with a relationship
                $multi_select[] = [
                    'model'           => $row->details->model,
                    'content'         => $content,
                    'table'           => $row->details->pivot_table,
                    'foreignPivotKey' => $row->details->foreign_pivot_key ?? null,
                    'relatedPivotKey' => $row->details->related_pivot_key ?? null,
                    'parentKey'       => $row->details->parent_key ?? null,
                    'relatedKey'      => $row->details->key,
                ];
            } elseif ($row->type == 'relationship' && $row->details->type == 'hasMany') {
                // Only if select_multiple is working with a relationship
                $has_many[] = [
                    'model'   => $row->details->model,
                    'row'     => $row,
                    'content' => $content,
                    'column'  => $row->details->column ?? null,
                    'key'     => $row->details->key,
                ];
            } elseif ($row->type == 'relationship' && $row->details->type == 'hasOne') {
                // Only if select_multiple is working with a relationship
                $has_one = [
                    'model'   => $row->details->model,
                    'row'     => $row,
                    'content' => $content,
                    'column'  => $row->details->column ?? null,
                    'key'     => $row->details->key,
                ];
            } else {
                $data->{$row->field} = $content;
            }
        }

        if (isset($data->additional_attributes)) {
            foreach ($data->additional_attributes as $attr) {
                if ($request->has($attr)) {
                    $data->{$attr} = $request->{$attr};
                }
            }
        }

        if ($via && $viaCallback) {
            $data = $viaCallback($via, $data);
        } else {
            $data->save();
        }

        // Save translations
        if (count($translations) > 0) {
            $data->saveTranslations($translations);
        }

        foreach ($multi_select as $sync_data) {
            $data->belongsToMany(
                $sync_data['model'],
                $sync_data['table'],
                $sync_data['foreignPivotKey'],
                $sync_data['relatedPivotKey'],
                $sync_data['parentKey'],
                $sync_data['relatedKey']
            )->sync($sync_data['content']);
        }

        foreach ($has_many as $has_many_data) {
            $existing = $data->hasMany(
                $has_many_data['model'],
                $has_many_data['column']
            )->get()->pluck('id')->toArray();

            $toBeRemoved = array_diff($existing, collect($has_many_data['content'])->pluck('id')->toArray());

            $this->saveHasMany(
                $request,
                $has_many_data['row'],
                $has_many_data['content'],
                $data,
                $data->hasMany(
                    $has_many_data['model'],
                    $has_many_data['column']
                )
            );

            if ($toBeRemoved) {
                $data->hasMany(
                    $has_many_data['model'],
                    $has_many_data['column']
                )->whereKey($toBeRemoved)->delete();
            }
        }

        if ($has_one) {
            $toBeRemoved = null;
            $existing    = optional($data->hasMany(
                $has_many_data['model'],
                $has_many_data['column']
            )->first())->id;

            if ($existing !== optional($has_one['content'])->id) {
                $toBeRemoved = $existing;
            }

            $this->saveHasOne(
                $request,
                $has_one['row'],
                $has_one['content'],
                $data,
                $data->hasOne(
                    $has_one['model'],
                    $has_one['column']
                )
            );

            if ($toBeRemoved) {
                $data->hasOne(
                    $has_one['model'],
                    $has_one['column']
                )->whereKey($toBeRemoved)->delete();
            }
        }

        // FIX: Session store not set on request issue - voyager api issue
        // Rename folders for newly created data through media-picker
        if (Session::has($slug . '_path') || Session::has($slug . '_uuid')) {
            $old_path    = Session::get($slug . '_path');
            $uuid        = Session::get($slug . '_uuid');
            $new_path    = str_replace($uuid, $data->getKey(), $old_path);
            $folder_path = substr($old_path, 0, strpos($old_path, $uuid)) . $uuid;

            $rows->where('type', 'media_picker')->each(function ($row) use ($data, $uuid) {
                $data->{$row->field} = str_replace($uuid, $data->getKey(), $data->{$row->field});
            });
            $data->save();
            if ($old_path != $new_path &&
                !Storage::disk(config('voyager.storage.disk'))->exists($new_path) &&
                Storage::disk(config('voyager.storage.disk'))->exists($old_path)
            ) {
                Session::forget([$slug . '_path', $slug . '_uuid']);
                Storage::disk(config('voyager.storage.disk'))->move($old_path, $new_path);
                Storage::disk(config('voyager.storage.disk'))->deleteDirectory($folder_path);
            }
        }

        return $data;
    }

    public function saveHasMany($request, $row, $content, $dataTypeContent, $via)
    {
        $original = $request->all();

        $options          = $row->details;
        $relationDataType = dataTypeByModel($options->model);
        $relationModel    = app($options->model);

        removeRelationshipField($relationDataType, !is_null($dataTypeContent->getKey()) ? 'edit' : 'add');
        $dataTypeRows = $relationDataType->{(!is_null($dataTypeContent->getKey()) ? 'edit' : 'add') . 'Rows'}->filter(function ($row) use ($options) {
            return $row->field !== $options->column && optional($row->details)->column !== $options->column;
        });

        if ($content) {
            foreach ($content as $contentEach) {
                $request->replace($contentEach);
                $this->insertUpdateData($request, $relationDataType->slug, $dataTypeRows, $relationModel, $via, function ($via, $data) use ($contentEach) {
                    $viaClone = clone $via;
                    $key      = Arr::get($contentEach, $data->getKeyName());
                    if ($key) {
                        Log::debug('updateOrCreate saveHasMany ' . json_encode([
                            $data->getKeyName() => $key
                        ], JSON_PRETTY_PRINT));
                        return $viaClone->updateOrCreate([
                            $data->getKeyName() => $key
                        ], $data->toArray());
                    }
                    Log::debug('create saveHasMany');
                    return $viaClone->create($data->toArray());
                });
            }
        }

        $request->replace($original);
    }

    public function saveHasOne($request, $row, $content, $dataTypeContent, $via)
    {
        $original = $request->all();

        $options          = $row->details;
        $relationDataType = dataTypeByModel($options->model);
        $relationModel    = app($options->model);

        removeRelationshipField($relationDataType, !is_null($dataTypeContent->getKey()) ? 'edit' : 'add');
        $dataTypeRows = $relationDataType->{(!is_null($dataTypeContent->getKey()) ? 'edit' : 'add') . 'Rows'}->filter(function ($row) use ($options) {
            return $row->field !== $options->column && optional($row->details)->column !== $options->column;
        });

        $request->replace($content);
        $this->insertUpdateData($request, $relationDataType->slug, $dataTypeRows, $relationModel, $via, function ($via, $data) use ($content) {
            $key = Arr::get($content, $data->getKeyName());
            if ($key) {
                return $via->updateOrCreate([
                    $data->getKeyName() => $key
                ], $data->toArray());
            }
            return $via->create($data->toArray());
        });

        $request->replace($original);
    }
}
