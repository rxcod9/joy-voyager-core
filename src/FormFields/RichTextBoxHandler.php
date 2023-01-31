<?php

namespace Joy\VoyagerCore\FormFields;

class RichTextBoxHandler extends AbstractHandler
{
    protected $codename = 'rich_text_box';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.rich_text_box', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
