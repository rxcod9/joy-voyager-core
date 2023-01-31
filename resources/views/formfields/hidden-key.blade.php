@php
    $name = $dataTypeContent->getKeyName();
    $id = $dataTypeContent->getKeyName();
    $inputKey = $dataTypeContent->getKeyName();
    if($via) {
        $name = $via->getNamePrefix() . '[' . $dataTypeContent->getKeyName() . ']';
        $id = $via->getIdPrefix() .'_' . $dataTypeContent->getKeyName();
        $inputKey = $via->getInputKeyPrefix() . $dataTypeContent->getKeyName() . '';
    }
@endphp
<input type="hidden" class="form-control" name="{{ $name }}" value="{{ old($inputKey, $dataTypeContent->{$dataTypeContent->getKeyName()} ?? '') }}">
