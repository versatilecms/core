@if(property_exists($rowDetails, 'relationship'))

    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
        @if($item->{$row->field . '_page_slug'})
            <a href="{{ $item->{$row->field . '_page_slug'} }}">{{ $item->{$row->field}  }}</a>@if(!$loop->last), @endif
        @else
            {{ $item->{$row->field}  }}
        @endif
    @endforeach

@elseif(property_exists($rowDetails, 'options'))
    @if (count(json_decode($dataTypeContent->{$row->field})) > 0)
        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
            @if (@$rowDetails->options->{$item})
                {{ $rowDetails->options->{$item} . (!$loop->last ? ', ' : '') }}
            @endif
        @endforeach
    @else
        {{ __('versatile::generic.none') }}
    @endif
@endif