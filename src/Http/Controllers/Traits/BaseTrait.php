<?php

namespace Joy\VoyagerCore\Http\Controllers\Traits;

trait BaseTrait
{
    use InsertUpdateData;
    use ValidateBread;
    use GetContentBasedOnType;
    use MorphToRelationAction;
    use RelationAction;
}
