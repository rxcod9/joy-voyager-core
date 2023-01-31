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
<input @if($row->required == 1) required @endif type="datetime" class="form-control datepicker" name="{{ $name }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($inputKey, $dataTypeContent->{$row->field}))->format('m/d/Y g:i A') }}@else{{old($inputKey)}}@endif">
