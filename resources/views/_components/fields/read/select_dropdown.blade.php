@if(property_exists($rowDetails, 'options') &&
        !empty($rowDetails->options->{$dataTypeContent->{$row->field}})
)
    <?php echo $rowDetails->options->{$dataTypeContent->{$row->field}};?>
    
@elseif($dataTypeContent->{$row->field . '_page_slug'})
    <a href="{{ $dataTypeContent->{$row->field . '_page_slug'} }}">{{ $dataTypeContent->{$row->field}  }}</a>

@endif($row->type == 'select_multiple')
