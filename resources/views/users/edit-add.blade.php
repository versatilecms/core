@extends('versatile::master')

@section('page_title', __('versatile::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('css')
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('versatile::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
@stop

@php
    $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
@endphp

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="{{ (isset($dataTypeContent->id)) ? route('versatile.'.$dataType->slug.'.update', $dataTypeContent->id) : route('versatile.'.$dataType->slug.'.store') }}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                    {{-- <div class="panel"> --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            {!! form_fields($dataTypeRows, $dataTypeContent, ['name', 'email', 'password']) !!}

                            @can('editRoles', $dataTypeContent)

                                <div class="form-group col-md-12">
                                    <label for="default_role">{{ __('versatile::profile.role_default') }}</label>
                                    @php
                                        $row = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                        $options = json_decode($row->details);
                                    @endphp
                                    @include('versatile::_components.fields.form.relationship')
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="additional_roles">{{ __('versatile::profile.roles_additional') }}</label>
                                    @php
                                        $row = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        $options = json_decode($row->details);
                                    @endphp
                                    @include('versatile::_components.fields.form.relationship')
                                </div>
                            @endcan

                            @php
                            if (isset($dataTypeContent->locale)) {
                                $selected_locale = $dataTypeContent->locale;
                            } else {
                                $selected_locale = config('app.locale', 'en');
                            }
                            @endphp
                            <div class="form-group col-md-12">
                                <label for="locale">{{ __('versatile::generic.locale') }}</label>
                                <select class="form-control select2" id="locale" name="locale">
                                    @foreach (Versatile::getLocales() as $locale)
                                    <option value="{{ $locale }}"
                                    {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            {!! form_fields($dataTypeRows, $dataTypeContent, ['avatar']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('versatile::generic.save') }}
            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('versatile.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();
        });
    </script>
@stop
