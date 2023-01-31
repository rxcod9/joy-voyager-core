<?php

namespace Joy\VoyagerCore\FormFields;

class DateHandler extends AbstractHandler
{
    protected $codename = 'date';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.date', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
