<?php

namespace Joy\VoyagerCore\FormFields;

class TextAreaHandler extends AbstractHandler
{
    protected $codename = 'text_area';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.text_area', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
