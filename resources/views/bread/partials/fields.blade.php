@php
    if(!empty($only) && is_array($only)) {
        $dataTypeRows = $dataTypeRows->whereIn('field', $only);
    }

    if(!empty($exclude) && is_array($exclude)) {
        $dataTypeRows = $dataTypeRows->whereNotIn('field', $exclude);
    }
@endphp

@foreach($dataTypeRows as $row)
    @php
        $options = json_decode($row->details);
        $display_options = isset($options->display) ? $options->display : null;
    @endphp
    @if ($options && isset($options->legend) && isset($options->legend->text))
        <legend class="text-{{isset($options->legend->align) ? $options->legend->align : 'center'}}" style="background-color: {{isset($options->legend->bgcolor) ? $options->legend->bgcolor : '#f0f0f0'}};padding: 5px;">{{$options->legend->text}}</legend>
    @endif
    @if ($options && isset($options->formfields_custom))
        @include('versatile::_components.fields.form.custom.' . $options->formfields_custom)
    @else
        <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ isset($display_options->width) ? $display_options->width : 12 }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
            {{ $row->slugify }}
            <label for="{{ $row->field }}">{{ $row->display_name }}</label>
            @include('versatile::multilingual.input-hidden-bread-edit-add')
            @if($row->type == 'relationship')
                @include('versatile::_components.fields.form.relationship')
            @else
                {!! form_field($row, $row->dataType, $dataTypeContent) !!}
            @endif

            @foreach (after_form_fields($row, $row->dataType, $dataTypeContent) as $after)
                {!! $after->handle($row, $row->dataType, $dataTypeContent) !!}
            @endforeach
        </div>
    @endif
@endforeach
