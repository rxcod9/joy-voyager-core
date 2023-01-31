@php
    $name = 'i18n_selector';
    if($via) {
        $name = $via->getNamePrefix() . '[' . 'i18n_selector' . ']';
    }
@endphp
@if (isset($isModelTranslatable) && $isModelTranslatable)
    <div class="language-selector">
        <div class="btn-group btn-group-sm" role="group" data-toggle="buttons">
            @foreach(config('voyager.multilingual.locales') as $lang)
            @php
                $id = $lang;
                if($via) {
                    $id = $via->getIdPrefix() .'_' . $lang . '_';
                }
            @endphp
                <label class="btn btn-primary{{ ($lang === config('voyager.multilingual.default')) ? " active" : "" }}">
                    <input type="radio" name="{{ $name }}" id="{{$id}}" autocomplete="off"{{ ($lang === config('voyager.multilingual.default')) ? ' checked="checked"' : '' }}> {{ strtoupper($lang) }}
                </label>
            @endforeach
        </div>
    </div>
@endif
