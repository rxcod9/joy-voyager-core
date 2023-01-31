<?php

namespace Joy\VoyagerCore\FormFields;

class CodeEditorHandler extends AbstractHandler
{
    protected $codename = 'code_editor';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.code_editor', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
