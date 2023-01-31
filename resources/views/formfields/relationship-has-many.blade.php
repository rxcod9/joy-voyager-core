@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
    $relationDataType = dataTypeByModel($options->model);
    $relationModel = app($options->model);

    removeRelationshipField($relationDataType, !is_null($dataTypeContent->getKey()) ? 'edit' : 'add');
    $dataTypeRows = $relationDataType->{(!is_null($dataTypeContent->getKey()) ? 'edit' : 'add') . 'Rows'}->filter(function($row) use($options) {
        return $row->field !== $options->column && optional($row->details)->column !== $options->column;
    });

    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
    $dataTypeContents = $relationshipData->hasMany(
            $options->model,
            $options->column
    )->get();

    $indexKey = '__' . \Str::studly($row->field) . '_INDEX__';
@endphp

<div class="has-many-rows has-many-rows-{{ $row->field }}" data-count="{{ $dataTypeContents->count() ? $dataTypeContents->count() - 1 : 0 }}">
    <div>
        <a
            href="javascript:;"
            class="btn-add-new-has-many btn-add-new-has-many-{{ $row->field }} pull-right"
            data-template="{{ $row->field }}Template" style="padding: 5px 5px 0px 0px;margin-top: -25px;">
            <i class="voyager-plus"></i> <span></span>
        </a>
    </div>
    @forelse ($dataTypeContents as $viaIndex => $dataTypeContentEach)
        @include('joy-voyager::formfields.relationship-row', ['relationType' => 'has-many', 'dataType' => $relationDataType, 'dataTypeContent' => $dataTypeContentEach, 'withDelete' => true, 'via' => via($row, $viaIndex, $via)])
    @empty
        @include('joy-voyager::formfields.relationship-row', ['relationType' => 'has-many', 'dataType' => $relationDataType, 'dataTypeContent' => $relationModel, 'withDelete' => true, 'via' => via($row, 0, $via)])
    @endforelse
</div>

@once('inline-edit' . $row->field)
@push('javascript')
    <script>
    const {{ $row->field }}Template = `{!! (string) view('joy-voyager::formfields.relationship-row', [
            'relationType' => 'has-many', 
            'dataType' => $relationDataType,
            'dataTypeRows' => $dataTypeRows,
            'dataTypeContent' => $relationModel,
            'withDelete' => true,
            'via' => via($row, $indexKey, $via),
            'edit' => $edit,
            'add' => $add,
        ])!!}`;

    $('form').on('click', '.btn-delete-has-many-{{ $row->field }}', function(e) {
        $(this).closest('fieldset').remove();
    });
    $('form').on('click', '.btn-add-new-has-many-{{ $row->field }}', function(e) {
        const parent = $(this).closest('.has-many-rows-{{ $row->field }}');
        const count = parent.data('count') + 1;
        $({{ $row->field }}Template.replaceAll(/{{ $indexKey }}/g, count)).appendTo(parent);
        parent.data('count', count);

        const newEl = $('.has-many-row.has-many-row-{{ $row->field }}[data-index='+ count + ']', parent);

        $('.toggleswitch', newEl).bootstrapToggle();

        //Init datepicker for date fields if data-datepicker attribute defined
        //or if browser does not handle date inputs
        $('.form-group input[type=date]', newEl).each(function (idx, elt) {
            if (elt.hasAttribute('data-datepicker')) {
                elt.type = 'text';
                $(elt).datetimepicker($(elt).data('datepicker'));
            } else if (elt.type != 'date') {
                elt.type = 'text';
                $(elt).datetimepicker({
                    format: 'L',
                    extraFormats: [ 'YYYY-MM-DD' ]
                }).datetimepicker($(elt).data('datepicker'));
            }
        });

        $('.form-group .datepicker', newEl).datetimepicker();

        //Init datepicker for date fields if data-datepicker attribute defined
        //or if browser does not handle date inputs
        $('.form-group input[type=date]', newEl).each(function (idx, elt) {
            if (elt.hasAttribute('data-datepicker')) {
                elt.type = 'text';
                $(elt).datetimepicker($(elt).data('datepicker'));
            } else if (elt.type != 'date') {
                elt.type = 'text';
                $(elt).datetimepicker({
                    format: 'L',
                    extraFormats: [ 'YYYY-MM-DD' ]
                }).datetimepicker($(elt).data('datepicker'));
            }
        });

        $('select.select2', newEl).select2({width: '100%'});
        $('select.select2-ajax', newEl).each(function() {
            $(this).select2({
                width: '100%',
                tags: $(this).hasClass('taggable'),
                createTag: function(params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }

                    return {
                        id: term,
                        text: term,
                        newTag: true
                    }
                },
                ajax: {
                    url: $(this).data('get-items-route'),
                    data: function (params) {
                        var query = {
                            search: params.term,
                            type: $(this).data('get-items-field'),
                            method: $(this).data('method'),
                            id: $(this).data('id'),
                            page: params.page || 1
                        }
                        return query;
                    }
                }
            });

            $(this).on('select2:select',function(e){
                var data = e.params.data;
                if (data.id == '') {
                    // "None" was selected. Clear all selected options
                    $(this).val([]).trigger('change');
                } else {
                    $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected','selected');
                }
            });

            $(this).on('select2:unselect',function(e){
                var data = e.params.data;
                $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected',false);
            });

            $(this).on('select2:selecting', function(e) {
                if (!$(this).hasClass('taggable')) {
                    return;
                }
                var $el = $(this);
                var route = $el.data('route');
                var label = $el.data('label');
                var errorMessage = $el.data('error-message');
                var newTag = e.params.args.data.newTag;

                if (!newTag) return;

                $el.select2('close');

                $.post(route, {
                    [label]: e.params.args.data.text,
                    _tagging: true,
                }).done(function(data) {
                    var newOption = new Option(e.params.args.data.text, data.data.id, false, true);
                    $el.append(newOption).trigger('change');
                }).fail(function(error) {
                    toastr.error(errorMessage);
                });

                return false;
            });
        });

        tinymce.remove('textarea.richTextBox');

        var additionalConfig = {
            selector: 'textarea.richTextBox',
        }

        // $.extend(additionalConfig, {!! json_encode($options->tinymceOptions ?? '{}') !!})

        tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));

        $('#slug', newEl).slugify();

        $('input[data-slug-origin]', newEl).each(function(i, el) {
            $(el).slugify();
        });

        if(typeof helpers.initSelect2MorphToType === 'function') {
            helpers.initSelect2MorphToType($('select.select2-morph-to-type', newEl));
        } else {
            console.warn('initSelect2MorphToType is not available yet.');
        }
        if(typeof helpers.initSelect2MorphToAjax === 'function') {
            helpers.initSelect2MorphToAjax($('select.select2-morph-to-ajax', newEl));
        } else {
            console.warn('initSelect2MorphToAjax is not available yet.');
        }
    });
    </script>
@endpush
@endonce