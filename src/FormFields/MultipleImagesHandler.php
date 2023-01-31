<?php

namespace Joy\VoyagerCore\FormFields;

class MultipleImagesHandler extends AbstractHandler
{
    protected $codename = 'multiple_images';

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return view('joy-voyager::formfields.multiple_images', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'via'             => $via,
        ]);
    }
}
