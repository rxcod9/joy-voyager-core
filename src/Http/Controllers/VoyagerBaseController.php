<?php

namespace Joy\VoyagerCore\Http\Controllers;

use Joy\VoyagerCore\Http\Controllers\Traits\BaseTrait;
use Joy\VoyagerCore\Http\Controllers\Traits\BreadRelationshipParser;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerBaseController extends BaseVoyagerBaseController
{
    use BaseTrait;
    use BreadRelationshipParser;
}
