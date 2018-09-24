@if(property_exists($options, 'relationship'))

    @foreach($data->{$row->field} as $item)
        @if($item->{$row->field . '_page_slug'})
            <a href="{{ $item->{$row->field . '_page_slug'} }}">{{ $item->{$row->field} }}</a>@if(!$loop->last), @endif
        @else
            {{ $item->{$row->field} }}
        @endif
    @endforeach

@elseif(property_exists($options, 'options'))
    @foreach($data->{$row->field} as $item)
     {{ $options->options->{$item} . (!$loop->last ? ', ' : '') }}
    @endforeach
@endif