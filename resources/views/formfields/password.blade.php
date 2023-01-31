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
@if(isset($dataTypeContent->{$row->field}))
    <br>
    <small>{{ __('voyager::form.field_password_keep') }}</small>
@endif
<input type="password"
       @if($row->required == 1 && !isset($dataTypeContent->{$row->field})) required @endif
       class="form-control"
       name="{{ $name }}"
       value="">
