@if($options && property_exists($options, 'on') && property_exists($options, 'off'))
    @if($data->{$row->field})
        <span class="label label-info">{{ $options->on }}</span>
    @else
        <span class="label label-primary">{{ $options->off }}</span>
    @endif
@else
{{ $data->{$row->field} }}
@endif