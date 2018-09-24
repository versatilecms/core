@if(property_exists($options, 'options'))

    @if($data->{$row->field . '_page_slug'})
        <a href="{{ $data->{$row->field . '_page_slug'} }}">{!! $options->options->{$data->{$row->field}} !!}</a>
    @else
        {!! $options->options->{$data->{$row->field}} or '' !!}
    @endif

@else
	@if($data->{$row->field . '_page_slug'})
    	<a href="{{ $data->{$row->field . '_page_slug'} }}">{{ $data->{$row->field} }}</a>
    @endif
@endif