@if($rowDetails && property_exists($rowDetails, 'on') && property_exists($rowDetails, 'off'))
    @if($dataTypeContent->{$row->field})
    <span class="label label-info">{{ $rowDetails->on }}</span>
    @else
    <span class="label label-primary">{{ $rowDetails->off }}</span>
    @endif
@else
{{ $dataTypeContent->{$row->field} }}
@endif