<?php

namespace Joy\VoyagerCore;

use Illuminate\Http\Request;
use TCG\Voyager\Voyager as BaseVoyager;

class Voyager extends BaseVoyager
{
    protected $contentTypes = [];

    public function formField($row, $dataType, $dataTypeContent, $via = null)
    {
        $formField = $this->formFields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent, $via);
    }

    public function afterFormFields($row, $dataType, $dataTypeContent, $via = null)
    {
        return collect($this->afterFormFields)->filter(function ($after) use ($row, $dataType, $dataTypeContent, $via) {
            return $after->visible($row, $dataType, $dataTypeContent, $row->details, $via);
        });
    }

    public function hasContentType($row)
    {
        return !!($this->contentTypes[$row->type] ?? null);
    }

    public function contentType(Request $request, $slug, $row, $options = null)
    {
        if (!($this->contentTypes[$row->type] ?? null)) {
            return;
        }
        $contentType = $this->contentTypes[$row->type];

        return (new $contentType($request, $slug, $row, $options))->handle();
    }

    public function addContentType($type, $handler)
    {
        $this->contentTypes[$type] = $handler;

        return $this;
    }
}
