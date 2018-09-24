<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups"> {{-- style="position: absolute;right: 10px;top: 5px;">--}}
    @can('delete', app($dataType->model_name))
        @include('versatile::partials.bulk-delete')
    @endcan

    @can('add', app($dataType->model_name))
        <a href="{{ route('versatile.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new pull-right">
            <i class="versatile-plus"></i> <span>{{ __('versatile::generic.add_new') }}</span>
        </a>
    @endcan

    @if(request()->has('filter'))
        <a href="{{ url()->current() }}" class="btn btn-secondary pull-right">
            <span>{{ __('versatile::generic.clear') }}</span>
        </a>
    @endif

    @if ($filters->count())
    <div class="btn-group pull-right">
        <div class="filters dropdown">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> <span class="caret"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" role="menu">
                {!! form_bootstrap()->open(['method' => 'get']) !!}
                @foreach($filters as $filter)
                    <div class="col-md-12">
                        {!! app($filter)->display() !!}
                    </div>
                @endforeach

                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ __('versatile::generic.filter') }}</button>
                        @if(request()->has('filter'))
                            <a href="{{ url()->current() }}" class="btn btn-secondary pull-right">{{ __('versatile::generic.clear') }}</a>
                        @endif
                    </div>
                </div>

                {!! form_bootstrap()->close() !!}
            </div>
        </div>
    </div>
    @endif

    @if($dataType->is_searchable)
    <form method="get" class="pull-right hidden-xs" style="width: 300px;margin-top: 5px;">
        <div id="search-input">
            <div class="input-group col-md-12">
                <input type="text" class="form-control" placeholder="{{ __('versatile::generic.search') }}" name="q" value="{{ request('q') }}">
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">
                        <i class="versatile-search"></i>
                    </button>
                </span>
            </div>
        </div>
    </form>
    @endif
</div>