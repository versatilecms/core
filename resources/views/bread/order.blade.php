@extends('versatile::master')

@section('page_title', $dataType->display_name_plural . ' ' . __('versatile::bread.order'))

@section('page_header')
<h1 class="page-title">
    <i class="versatile-list"></i>{{ $dataType->display_name_plural }} {{ __('versatile::bread.order') }}
</h1>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <p class="panel-title" style="color:#777">{{ __('versatile::generic.drag_drop_info') }}</p>
                </div>

                <div class="panel-body" style="padding:30px;">
                    <div class="dd">
                        <ol class="dd-list">
                            @foreach ($results as $result)
                            <li class="dd-item" data-id="{{ $result->getKey() }}">
                                <div class="dd-handle">
                                    <span>{{ $result->{$display_column} }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')

<script>
$(document).ready(function () {
    $('.dd').nestable({
        maxDepth: 1
    });

    /**
    * Reorder items
    */
    $('.dd').on('change', function (e) {
        $.post('{{ route('versatile.'.$dataType->slug.'.order') }}', {
            order: JSON.stringify($('.dd').nestable('serialize')),
            _token: '{{ csrf_token() }}'
        }, function (data) {
            toastr.success("{{ __('versatile::bread.updated_order') }}");
        });
    });
});
</script>
@stop
