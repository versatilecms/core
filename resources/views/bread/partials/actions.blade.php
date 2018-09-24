@if($type == 'button-group')

    @foreach($actions as $action)

    @php
        $action = new $action($dataType, $data);
    @endphp

    @if ($action->shouldActionDisplayOnDataType())
        @can($action->getPolicy(), $data)
            <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
            </a>
        @endcan
    @endif

    @endforeach

@endif

@if($type == 'dropdown')
    <div class="dropdown">
        <button class="btn btn-primary btn-circle dropdown-toggle" type="button" id="dropdown-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="versatile-dot-3"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">

            @foreach($actions as $action)
            @php
                $action = new $action($dataType, $data);
            @endphp

            @if ($action->shouldActionDisplayOnDataType())
                @can($action->getPolicy(), $data)
                    <li>
                        <a href="{{ $action->getRoute($dataType->name) }}" {!! $action->convertAttributesToHtml([], ['class']) !!}>
                            <i class="{{ $action->getIcon() }}"></i> {{ $action->getTitle() }}
                        </a>
                    </li>
                @endcan
            @endif
            @endforeach
        </ul>
    </div>
@endif