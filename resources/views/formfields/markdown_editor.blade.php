@php
    $name = $row->field;
    $id = 'markdown' . $row->field;
    $inputKey = $row->field;
    if($via) {
        $name = $via->getNamePrefix() . '[' . $row->field . ']';
        $id = $via->getIdPrefix() .'_' . 'markdown' . $row->field;
        $inputKey = $via->getInputKeyPrefix() . $row->field . '';
    }
@endphp
<textarea class="form-control easymde" name="{{ $name }}" id="{{ $id }}">{{ old($inputKey, $dataTypeContent->{$row->field} ?? '') }}</textarea>
