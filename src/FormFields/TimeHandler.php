<?php

namespace Joy\VoyagerCore\FormFields;

class TimeHandler extends AbstractHandler
{
    protected $codename = 'time';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.time', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
