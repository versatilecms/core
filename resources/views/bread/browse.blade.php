@extends('versatile::master')

@section('page_title', __('versatile::generic.viewing').' '.$dataType->display_name_plural)

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ $dataType->display_name_plural }}
    </h1>

    @can('edit', app($dataType->model_name))
        @if(isset($dataType->order_column) && isset($dataType->order_display_column))
            <a href="{{ route('versatile.'.$dataType->slug.'.order') }}" class="btn btn-primary">
                <i class="versatile-list"></i> <span>{{ __('versatile::bread.order') }}</span>
            </a>
        @endif
    @endcan
    @include('versatile::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('versatile::alerts')
        <div class="row">
            <div class="col-md-12">

                @include('versatile::bread.partials.toolbar')

                <div class="panel panel-default panel-bordered">
                    <div class="panel-body p-0">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" id="dataTable">
                            <thead>
                            <tr>
                                @can('delete',app($dataType->model_name))
                                    <th>
                                        <input type="checkbox" class="select_all">
                                    </th>
                                @endcan
                                @foreach($dataType->browseRows as $row)
                                    <th>
                                        <a href="{{ $row->sortByUrl() }}">
                                            {{ $row->display_name }}
                                            @if ($row->isCurrentSortField())
                                                @if ($row->isCurrentSortType() == 'asc')
                                                    <i class="versatile-angle-up pull-right"></i>
                                                @else
                                                    <i class="versatile-angle-down pull-right"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                @endforeach
                                <th class="actions">{{ __('versatile::generic.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataTypeContent as $data)
                                <tr>
                                    @can('delete',app($dataType->model_name))
                                        <td>
                                            <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                        </td>
                                    @endcan
                                    @foreach($dataType->browseRows as $row)
                                        <td>
                                            <?php $options = $row->details; ?>
                                            @if (view()->exists('versatile::_components.fields.browse.' . $row->type))
                                                @include('versatile::_components.fields.browse.' . $row->type)
                                            @else
                                                @include('versatile::multilingual.input-hidden-bread-browse')
                                                <span>{{ $data->{$row->field} }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="no-sort no-click" id="bread-actions">
                                        @include('versatile::bread.partials.actions', [
                                            'actions' => $actions,
                                            'type' => Actions::getType()
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="panel-footer pb-3">
                        <div class="pull-right">
                            {{ $dataTypeContent->appends([
                                'order_by' => $orderBy,
                                'sort_order' => $sortOrder
                            ])->links() }}
                        </div>
                        <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'versatile::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ]) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('versatile::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="versatile-trash"></i> {{ __('versatile::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('versatile::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('versatile::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
<style>
    @media screen and (min-width: 768px) {
        .filters.dropdown {
            position: static !important;
        }
        .filters.dropdown .dropdown-menu {
            min-width: 500px;
        }
    }
</style>
@stop

@section('javascript')
    <script>
        $(function () {
            $('#search-input select').select2({
                minimumResultsForSearch: Infinity
            });

            @if ($isModelTranslatable)
            $('.side-body').multilingual();
            //Reinitialise the multilingual features when they change tab
            $('#dataTable').on('draw.dt', function(){
                $('.side-body').data('multilingual').init();
            })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });

        $('td').on('click', "[data-action='delete']", function (e) {
            $('#delete_form')[0].action = '{{ route('versatile.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
@stop
