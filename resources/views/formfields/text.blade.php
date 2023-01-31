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
<input @if($row->required == 1) required @endif type="text" class="form-control" name="{{ $name }}"
        placeholder="{{ old($inputKey, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
