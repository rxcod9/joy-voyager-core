<?php

namespace Joy\VoyagerCore\Events;

use Illuminate\Queue\SerializesModels;

class FormFieldsRegistered
{
    use SerializesModels;

    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

        // @deprecate
        //
        event('joy-voyager.form-fields.registered', $fields);
    }
}
