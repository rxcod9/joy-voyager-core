<?php

namespace Joy\VoyagerCore\FormFields;

class HiddenHandler extends AbstractHandler
{
    protected $codename = 'hidden';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.hidden', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
