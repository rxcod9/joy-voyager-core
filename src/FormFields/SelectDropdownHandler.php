<?php

namespace Joy\VoyagerCore\FormFields;

class SelectDropdownHandler extends AbstractHandler
{
    protected $codename = 'select_dropdown';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.select_dropdown', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
