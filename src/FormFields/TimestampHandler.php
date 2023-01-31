<?php

namespace Joy\VoyagerCore\FormFields;

class TimestampHandler extends AbstractHandler
{
    protected $codename = 'timestamp';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.timestamp', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
