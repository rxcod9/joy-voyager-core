<?php

namespace Joy\VoyagerCore\FormFields;

interface HandlerInterface
{
    public function handle($row, $dataType, $dataTypeContent, $via = null);

    public function createContent($row, $dataType, $dataTypeContent, $options, $via = null);

    public function supports($driver);

    public function getCodename();

    public function getName();
}
