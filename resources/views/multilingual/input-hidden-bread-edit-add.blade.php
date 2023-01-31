@php
    $name = $row->field . '_i18n';
    $id = $row->field . '_i18n';
    $inputKey = $row->field . '_i18n';
    if($via) {
        $name = $via->getNamePrefix() . '[' . $row->field . '_i18n' . ']';
        $id = $via->getIdPrefix() . '_' . $row->field . '_i18n' . '';
        $inputKey = $via->getInputKeyPrefix() . $row->field . '_i18n' . '';
    }
@endphp
@if (is_field_translatable($dataTypeContent, $row))
    <span class="language-label js-language-label"></span>
    <input type="hidden"
           data-i18n="true"
           name="{{ $name }}"
           id="{{ $id }}"
           @if(!empty(session()->getOldInput($inputKey) && is_null($dataTypeContent->id)))
             value="{{ session()->getOldInput($inputKey) }}"
           @else
             value="{{ get_field_translations($dataTypeContent, $row->field) }}"
           @endif>
@endif
