<?php

namespace Joy\VoyagerCore\FormFields;

class NumberHandler extends AbstractHandler
{
    protected $codename = 'number';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.number', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
