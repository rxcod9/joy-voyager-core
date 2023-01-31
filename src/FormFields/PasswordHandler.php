<?php

namespace Joy\VoyagerCore\FormFields;

class PasswordHandler extends AbstractHandler
{
    protected $codename = 'password';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.password', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
