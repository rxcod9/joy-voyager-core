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
    $relationDataTypeContent = $relationshipData->hasOne(
            $options->model,
            $options->column
    )->first();
@endphp

<div class="has-one-rows has-one-rows-{{ $row->field }}">
    @if ($relationDataTypeContent)
        @include('joy-voyager::formfields.relationship-has-one-row', ['relationType' => 'has-one', 'dataType' => $relationDataType, 'dataTypeContent' => $relationDataTypeContent, 'via' => via($row, null, $via)])
    @else
        @include('joy-voyager::formfields.relationship-has-one-row', ['relationType' => 'has-one', 'dataType' => $relationDataType, 'dataTypeContent' => $relationModel, 'via' => via($row, null, $via)])
    @endforelse
</div>