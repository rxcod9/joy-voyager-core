@php
    $name = $row->field . '_i18n';
    $id = $row->field . '_i18n';
    if($via) {
        $name = $via->getNamePrefix() . '[' . $row->field . '_i18n' . ']';
        $id = $via->getNamePrefix() . '_' . $row->field . '_i18n' . '';
    }
@endphp
@if (is_field_translatable($dataTypeContent, $row))
    <input type="hidden"
           data-i18n="true"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ get_field_translations($dataTypeContent, $row->field, $row->type, true) }}">
@endif
