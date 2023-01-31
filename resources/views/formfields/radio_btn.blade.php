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
<?php $selected_value = old($inputKey, $dataTypeContent->{$row->field} ?? $options->default ?? NULL) ?>
<ul class="radio">
    @if(isset($options->options))
        @foreach($options->options as $key => $option)
            @php
                $id = 'option-' . \Illuminate\Support\Str::slug($row->field, '-') . '-' . \Illuminate\Support\Str::slug($key, '-');
            @endphp
            <li>
                <input type="radio" id="{{ $id }}"
                       name="{{ $name }}"
                       value="{{ $key }}" @if($selected_value == $key) checked @endif>
                <label for="{{ $id }}">{{ $option }}</label>
                <div class="check"></div>
            </li>
        @endforeach
    @endif
</ul>
