@php
    $name = $row->field;
    $id = $row->field;
    $inputKey = $row->field;
    if($via) {
        $name = $via->getNamePrefix() . '[' . $row->field . ']';
        $id = $via->getIdPrefix() .'_' . $row->field;
        $inputKey = $via->getInputKeyPrefix() . $row->field . '';
    }
@endphp
<textarea @if($row->required == 1) required @endif class="form-control" name="{{ $name }}" rows="{{ $options->display->rows ?? 5 }}">{{ old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
