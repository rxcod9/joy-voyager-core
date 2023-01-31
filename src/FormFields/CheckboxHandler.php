<?php

namespace Joy\VoyagerCore\FormFields;

class CheckboxHandler extends AbstractHandler
{
    protected $codename = 'checkbox';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.checkbox', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
