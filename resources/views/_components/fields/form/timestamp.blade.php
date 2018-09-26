<?php
    $outputFormat = '%Y-%m-%d %H:%M:%S';
    if (property_exists($options, 'format_edit')) {
        $outputFormat = $options->format_edit;
    }
?>

<input
    @if($row->required == 1) required @endif
    type="datetime"
    class="form-control datepicker"
    name="{{ $row->field }}"
    value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->formatLocalized($outputFormat) }}@else{{old($row->field)}}@endif">
