<?php
if ($options && property_exists($options, 'format')) {
	$value = \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($options->format);
} else {
	$value = $data->{$row->field};
}
?>

{{ $value }}