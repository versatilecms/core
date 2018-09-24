@if(!empty($data->{$row->field}) )
    @include('versatile::multilingual.input-hidden-bread-browse')
    @if(json_decode($data->{$row->field}))
        @foreach(json_decode($data->{$row->field}) as $file)
            <a href="{{ Storage::disk(config('versatile.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                {{ $file->original_name ?: '' }}
            </a>
            <br/>
        @endforeach
    @else
        <a href="{{ Storage::disk(config('versatile.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
            Download
        </a>
    @endif
@endif