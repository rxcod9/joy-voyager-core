<?php

namespace Joy\VoyagerCore\FormFields\After;

interface HandlerInterface
{
    public function visible($row, $dataType, $dataTypeContent, $options, $via = null);

    public function handle($row, $dataType, $dataTypeContent, $via = null);

    public function getCodename();

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null);
}
