@php
    $name = $_field_name . '_i18n';
    $id = $_field_name . '_i18n';
    if($via) {
        $name = $via->getNamePrefix() . '[' . $_field_name . '_i18n' . ']';
        $id = $via->getIdPrefix() .'_' . $_field_name . '_i18n' . '_';
    }
@endphp
@if ($isModelTranslatable)
    <input type="hidden"
           data-i18n="true"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ $_field_trans }}">
@endif
