<?php

namespace Joy\VoyagerCore\FormFields;

class MarkdownEditorHandler extends AbstractHandler
{
    protected $codename = 'markdown_editor';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.markdown_editor', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
