<fieldset class="{{ $relationType }}-row {{ $relationType }}-row-{{ $via->getRow()->field }}" style="border: 1px solid #c0c0c0;width: 100%;" data-index="{{ $via->getViaIndex() }}">
    @if($withDelete ?? null)
    {{-- <div class="col-md-3 pull-right"> --}}
        <a href="javascript:;" class="btn-delete-{{ $relationType }} btn-delete-{{ $relationType }}-{{ $via->getRow()->field }} btn-delete pull-right" style="padding: 5px 5px 0px 0px;position: relative">
            <i class="voyager-trash"></i> <span></span>
        </a>
    {{-- </div> --}}
    @endif
@php
    $chunks = $dataTypeRows->chunk(4);
    $keyRow = $dataType->rows()->where('field', $dataTypeContent->getKeyName())->first();
@endphp
@foreach($chunks as $chunk)
    <div class="col-md-12">
        @if($keyRow)
            @include('joy-voyager::formfields.hidden-key', ['row' => $keyRow, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$keyRow->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $keyRow->details])
        @endif
        @foreach($chunk as $row)
        <!-- GET THE DISPLAY OPTIONS -->
        @php
            $display_options = $row->details->display ?? NULL;
            if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
            }
        @endphp
        @if (isset($row->details->legend) && isset($row->details->legend->text))
            <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
        @endif

        <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
            {{ $row->slugify }}
            <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
            @include('joy-voyager::multilingual.input-hidden-bread-edit-add', ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
            @if (isset($row->details->view))
                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
            @elseif ($row->type == 'relationship')
                @include('joy-voyager::formfields.relationship', ['options' => $row->details, $via])
            @else
                {!! app('joy-voyager')->formField($row, $dataType, $dataTypeContent, $via) !!}
            @endif

            @foreach (app('joy-voyager')->afterFormFields($row, $dataType, $dataTypeContent, $via) as $after)
                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
            @endforeach
            @if ($errors->has($row->field))
                @foreach ($errors->get($row->field) as $error)
                    <span class="help-block">{{ $error }}</span>
                @endforeach
            @endif
        </div>
        @endforeach
    </div>
@endforeach
</fieldset>