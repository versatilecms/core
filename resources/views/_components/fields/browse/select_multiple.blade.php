@if(property_exists($options, 'relationship'))

    @foreach($data->{$row->field} as $item)
        @if($item->{$row->field . '_page_slug'})
            <a href="{{ $item->{$row->field . '_page_slug'} }}">{{ $item->{$row->field} }}</a>@if(!$loop->last), @endif
        @else
            {{ $item->{$row->field} }}
        @endif
    @endforeach

@elseif(property_exists($options, 'options'))
    @if (count(json_decode($data->{$row->field})) > 0)
        @foreach(json_decode($data->{$row->field}) as $item)
            @if (@$options->options->{$item})
                {{ $options->options->{$item} . (!$loop->last ? ', ' : '') }}
            @endif
        @endforeach
    @else
        {{ __('versatile::generic.none') }}
    @endif
@endif