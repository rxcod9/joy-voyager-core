<?php

namespace Joy\VoyagerCore\FormFields\After;

class DescriptionHandler extends AbstractHandler
{
    protected $codename = 'description';

    public function visible($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        if (!isset($options->description)) {
            return false;
        }

        return !empty($options->description);
    }

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return '<span class="glyphicon glyphicon-question-sign"
                                        aria-hidden="true"
                                        data-toggle="tooltip"
                                        data-placement="right"
                                        data-html="true"
                                        title="'.$options->description.'"></span>';
    }
}
