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
<br>
<?php $checked = false; ?>
@if(isset($dataTypeContent->{$row->field}) || old($inputKey))
    <?php $checked = old($inputKey, $dataTypeContent->{$row->field}); ?>
@else
    <?php $checked = isset($options->checked) &&
        filter_var($options->checked, FILTER_VALIDATE_BOOLEAN) ? true: false; ?>
@endif

<?php $class = $options->class ?? "toggleswitch"; ?>

@if(isset($options->on) && isset($options->off))
    <input type="checkbox" name="{{ $name }}" class="{{ $class }}"
        data-on="{{ $options->on }}" {!! $checked ? 'checked="checked"' : '' !!}
        data-off="{{ $options->off }}">
@else
    <input type="checkbox" name="{{ $name }}" class="{{ $class }}"
        @if($checked) checked @endif>
@endif
