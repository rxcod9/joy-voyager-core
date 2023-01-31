<?php

namespace Joy\VoyagerCore\FormFields\After;

use TCG\Voyager\Traits\Renderable;

abstract class AbstractHandler implements HandlerInterface
{
    use Renderable;

    public function visible($row, $dataType, $dataTypeContent, $options, $via = null)
    {
        return true;
    }

    public function handle($row, $dataType, $dataTypeContent, $via = null)
    {
        $content = $this->createContent(
            $row,
            $dataType,
            $dataTypeContent,
            $row->details,
            $via
        );

        return $this->render($content);
    }

    public function getCodename()
    {
        return $this->codename;
    }
}
