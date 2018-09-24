@extends('versatile::master')

@section('css')

    @include('versatile::compass.includes.styles')

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="versatile-compass"></i>
        <p> {{ __('versatile::generic.compass') }}</p>
        <span class="page-description">{{ __('versatile::compass.welcome') }}</span>
    </h1>
@stop

@section('content')

    <div id="gradient_bg"></div>

    <div class="container-fluid">
        @include('versatile::alerts')
    </div>

    <div class="page-content compass container-fluid">
        <ul class="nav nav-tabs">
          <li @if(empty($active_tab) || (isset($active_tab) && $active_tab == 'resources')){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#resources"><i class="versatile-book"></i> {{ __('versatile::compass.resources.title') }}</a></li>
          <li @if($active_tab == 'commands'){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#commands"><i class="versatile-terminal"></i> {{ __('versatile::compass.commands.title') }}</a></li>
          <li @if($active_tab == 'logs'){!! 'class="active"' !!}@endif><a data-toggle="tab" href="#logs"><i class="versatile-logbook"></i> {{ __('versatile::compass.logs.title') }}</a></li>
        </ul>

        <div class="tab-content">
            <div id="resources" class="tab-pane fade in @if(empty($active_tab) || (isset($active_tab) && $active_tab == 'resources')){!! 'active' !!}@endif">
                <h3><i class="versatile-book"></i> {{ __('versatile::compass.resources.title') }} <small>{{ __('versatile::compass.resources.text') }}</small></h3>

                {{--
                <div class="collapsible">
                    <div class="collapse-head" data-toggle="collapse" data-target="#links" aria-expanded="true" aria-controls="links">
                        <h4>{{ __('versatile::compass.links.title') }}</h4>
                        <i class="versatile-angle-down"></i>
                        <i class="versatile-angle-up"></i>
                    </div>
                    <div class="collapse-content collapse in" id="links">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="https://laravelversatile.com/docs" target="_blank" class="versatile-link" style="background-image:url('{{ versatile_asset('images/compass/documentation.jpg') }}')">
                                    <span class="resource_label"><i class="versatile-documentation"></i> <span class="copy">{{ __('versatile::compass.links.documentation') }}</span></span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="https://laravelversatile.com" target="_blank" class="versatile-link" style="background-image:url('{{ versatile_asset('images/compass/versatile-home.jpg') }}')">
                                    <span class="resource_label"><i class="versatile-browser"></i> <span class="copy">{{ __('versatile::compass.links.versatile_homepage') }}</span></span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="https://larapack.io" target="_blank" class="versatile-link" style="background-image:url('{{ versatile_asset('images/compass/hooks.jpg') }}')">
                                    <span class="resource_label"><i class="versatile-hook"></i> <span class="copy">{{ __('versatile::compass.links.versatile_hooks') }}</span></span>
                                </a>
                            </div>
                        </div>
                    </div>
              </div>
              --}}

              <div class="collapsible">

                <div class="collapse-head" data-toggle="collapse" data-target="#fonts" aria-expanded="true" aria-controls="fonts">
                    <h4>{{ __('versatile::compass.fonts.title') }}</h4>
                    <i class="versatile-angle-down"></i>
                    <i class="versatile-angle-up"></i>
                </div>

                <div class="collapse-content collapse in" id="fonts">

                    @include('versatile::compass.includes.fonts')

                </div>

              </div>
            </div>

          <div id="commands" class="tab-pane fade in @if($active_tab == 'commands'){!! 'active' !!}@endif">
            <h3><i class="versatile-terminal"></i> {{ __('versatile::compass.commands.title') }} <small>{{ __('versatile::compass.commands.text') }}</small></h3>
            <div id="command_lists">
                @include('versatile::compass.includes.commands')
            </div>

          </div>
          <div id="logs" class="tab-pane fade in @if($active_tab == 'logs'){!! 'active' !!}@endif">
            <div class="row">

                @include('versatile::compass.includes.logs')

            </div>
          </div>
        </div>

    </div>

@stop
@section('javascript')
    <script>
        $('document').ready(function(){
            $('.collapse-head').click(function(){
                var collapseContainer = $(this).parent();
                if(collapseContainer.find('.collapse-content').hasClass('in')){
                    collapseContainer.find('.versatile-angle-up').fadeOut('fast');
                    collapseContainer.find('.versatile-angle-down').fadeIn('slow');
                } else {
                    collapseContainer.find('.versatile-angle-down').fadeOut('fast');
                    collapseContainer.find('.versatile-angle-up').fadeIn('slow');
                }
            });
        });
    </script>
    <!-- JS for commands -->
    <script>

        $(document).ready(function(){
            $('.command').click(function(){
                $(this).find('.cmd_form').slideDown();
                $(this).addClass('more_args');
                $(this).find('input[type="text"]').focus();
            });

            $('.close-output').click(function(){
                $('#commands pre').slideUp();
            });
        });

    </script>

    <!-- JS for logs -->
    <script>
      $(document).ready(function () {
        $('.table-container tr').on('click', function () {
          $('#' + $(this).data('display')).toggle();
        });
        $('#table-log').DataTable({
          "order": [1, 'desc'],
          "stateSave": true,
          "language": {!! json_encode(__('versatile::datatable')) !!},
          "stateSaveCallback": function (settings, data) {
            window.localStorage.setItem("datatable", JSON.stringify(data));
          },
          "stateLoadCallback": function (settings) {
            var data = JSON.parse(window.localStorage.getItem("datatable"));
            if (data) data.start = 0;
            return data;
          }
        });

        $('#delete-log, #delete-all-log').click(function () {
          return confirm('{{ __('versatile::generic.are_you_sure') }}');
        });
      });
    </script>
@stop
