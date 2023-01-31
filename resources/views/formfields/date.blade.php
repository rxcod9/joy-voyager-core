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
<input type="date" class="form-control" name="{{ $name }}"
       placeholder="{{ $row->getTranslatedAttribute('display_name') }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($inputKey, $dataTypeContent->{$row->field}))->format('Y-m-d') }}@else{{old($inputKey)}}@endif">
