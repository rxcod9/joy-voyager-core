@php
    $name = $row->field . $row->id . '_i18n';
    $id = $row->field . $row->id . '_i18n';
    $inputKey = $row->field . $row->id . '_i18n';
    if($via) {
        $name = $via->getNamePrefix() . '[' . $row->field . $row->id . '_i18n' . ']';
        $id = $via->getNamePrefix() . '_' . $row->field . $row->id . '_i18n' . '';
        $inputKey = $via->getInputKeyPrefix() . $row->field . $row->id . '_i18n' . '';
    }
@endphp
@if (is_field_translatable($data, $row))
    <input type="hidden"
           data-i18n="true"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ get_field_translations($data, $row->field) }}">
@endif
