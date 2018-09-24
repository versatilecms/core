<input
       @if($row->required == 1) required @endif
       type="text"
       class="form-control"
       name="{{ $row->field }}"
       placeholder="{{ isset($options->placeholder)? old($row->field, $options->placeholder): $row->display_name }}"
       {!! is_bread_slug_auto_generator($options) !!}
       value="@if(isset($dataTypeContent->{$row->field})){{ old($row->field, $dataTypeContent->{$row->field}) }}@elseif(isset($options->default)){{ old($row->field, $options->default) }}@else{{ old($row->field) }}@endif">
