@if(property_exists($options, 'options'))

    @if($data->{$row->field . '_page_slug'})
        <a href="{{ $data->{$row->field . '_page_slug'} }}">{!! $options->options->{$data->{$row->field}} !!}</a>
    @else
        {!! isset($options->options->{$data->{$row->field}}) ?  $options->options->{$data->{$row->field}} : '' !!}
    @endif

@else
	@if($data->{$row->field . '_page_slug'})
    	<a href="{{ $data->{$row->field . '_page_slug'} }}">{{ $data->{$row->field} }}</a>
    @endif
@endif