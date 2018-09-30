<?php
if(isset($dataTypeContent->{$row->field})) {
        $fieldValue = old($row->field, $dataTypeContent->{$row->field});
} elseif(isset($options->default)) {
        $fieldValue = old($row->field, $options->default);
} else {
        $fieldValue = old($row->field);
}
?>

<textarea
        @if($row->required == 1) required @endif
        class="form-control"
        name="{{ $row->field }}"
        rows="{{ isset($options->display->rows) ? $options->display->rows : 5 }}">{{ $fieldValue }}</textarea>
