<?php

namespace Joy\VoyagerCore\FormFields;

class MultipleCheckboxHandler extends AbstractHandler
{
    protected $codename = 'multiple_checkbox';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.multiple_checkbox', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
