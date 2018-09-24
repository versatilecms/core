@extends('versatile::master')

@section('page_title', __('versatile::generic.viewing').' '.__('versatile::generic.bread'))

@section('page_header')
    <h1 class="page-title">
        <i class="versatile-bread"></i> BREAD
    </h1>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('versatile::alerts')
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                        <table class="table table-striped database-tables">
                            <thead>
                            <tr>
                                <th>{{ __('versatile::database.table_name') }}</th>
                                <th style="text-align:right">{{ __('versatile::bread.bread_crud_actions') }}</th>
                            </tr>
                            </thead>

                            @foreach($tables as $table)
                                @continue(in_array($table->name, config('versatile.database.tables.hidden', [])))
                                <tr>
                                    <td>
                                        <p class="name">
                                            <a href="{{ route('versatile.database.show', $table->name) }}"
                                               data-name="{{ $table->name }}" class="desctable">
                                                {{ $table->name }}
                                            </a>
                                            <i class="versatile-data"
                                               style="font-size:25px; position:absolute; margin-left:10px; margin-top:-3px;"></i>
                                        </p>
                                    </td>

                                    <td class="actions text-right">
                                        @if($table->dataTypeId)
                                            <a href="{{ route('versatile.' . $table->slug . '.index') }}"
                                               class="btn btn-warning btn-sm browse_bread" style="margin-right: 0;">
                                                <i class="versatile-plus"></i> Browse
                                            </a>
                                            <a href="{{ route('versatile.bread.edit', $table->name) }}"
                                               class="btn btn-primary btn-sm edit">
                                                <i class="versatile-edit"></i> {{ __('versatile::generic.edit') }}
                                            </a>
                                            <a href="#delete-bread" data-id="{{ $table->dataTypeId }}" data-name="{{ $table->name }}"
                                               class="btn btn-danger btn-sm delete">
                                                <i class="versatile-trash"></i> {{ __('versatile::generic.delete') }}
                                            </a>
                                        @else
                                            <a href="{{ route('versatile.bread.create', ['name' => $table->name]) }}"
                                               class="_btn btn-default btn-sm pull-right">
                                                <i class="versatile-plus"></i> {{ __('versatile::bread.add_bread') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_builder_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('versatile::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="versatile-trash"></i>  {!! __('versatile::bread.delete_bread_quest', ['table' => '<span id="delete_builder_name"></span>']) !!}</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('versatile.bread.delete', ['id' => null]) }}" id="delete_builder_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('versatile::bread.delete_bread_conf') }}">
                    </form>
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('versatile::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('versatile::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="versatile-data"></i> @{{ table.name }}</h4>
                </div>
                <div class="modal-body" style="overflow:scroll">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('versatile::database.field') }}</th>
                            <th>{{ __('versatile::database.type') }}</th>
                            <th>{{ __('versatile::database.null') }}</th>
                            <th>{{ __('versatile::database.key') }}</th>
                            <th>{{ __('versatile::database.default') }}</th>
                            <th>{{ __('versatile::database.extra') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in table.rows">
                            <td><strong>@{{ row.Field }}</strong></td>
                            <td>@{{ row.Type }}</td>
                            <td>@{{ row.Null }}</td>
                            <td>@{{ row.Key }}</td>
                            <td>@{{ row.Default }}</td>
                            <td>@{{ row.Extra }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('versatile::generic.close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('javascript')

    <script>

        var table = {
            name: '',
            rows: []
        };

        new Vue({
            el: '#table_info',
            data: {
                table: table,
            },
        });

        $(function () {

            // Setup Delete BREAD
            //
            $('table .actions').on('click', '.delete', function (e) {
                id = $(this).data('id');
                name = $(this).data('name');

                $('#delete_builder_name').text(name);
                $('#delete_builder_form')[0].action += '/' + id;
                $('#delete_builder_modal').modal('show');
            });

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault();
                href = $(this).attr('href');
                table.name = $(this).data('name');
                table.rows = [];
                $.get(href, function (data) {
                    $.each(data, function (key, val) {
                        table.rows.push({
                            Field: val.field,
                            Type: val.type,
                            Null: val.null,
                            Key: val.key,
                            Default: val.default,
                            Extra: val.extra
                        });
                        $('#table_info').modal('show');
                    });
                });
            });
        });
    </script>

@stop
