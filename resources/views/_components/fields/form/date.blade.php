<?php
    $outputFormat = '%Y-%m-%d';
    if (property_exists($options, 'format_edit')) {
        $outputFormat = $options->format_edit;
    }
?>

<input 
	 @if($row->required == 1) 
	 required @endif
	 type="date"
	 class="form-control"
	 name="{{ $row->field }}"
     placeholder="{{ $row->display_name }}"

     @if(isset($options->datetimepicker))
     data-datetimepicker="{{ json_encode($options->datetimepicker) }}"
     @endif

     value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->formatLocalized($outputFormat) }}@else{{old($row->field)}}@endif">
