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
<div id="{{ $id }}" data-theme="{{ @$options->theme }}" data-language="{{ @$options->language }}" class="ace_editor min_height_200" name="{{ $name }}">{{ old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</div>
<textarea name="{{ $name }}" id="{{ $id }}_textarea" class="hidden">{{ old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
