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
<input type="number"
       class="form-control"
       name="{{ $name }}"
       type="number"
       @if($row->required == 1) required @endif
       @if(isset($options->min)) min="{{ $options->min }}" @endif
       @if(isset($options->max)) max="{{ $options->max }}" @endif
       step="{{ $options->step ?? 'any' }}"
       placeholder="{{ old($inputKey, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       value="{{ old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
