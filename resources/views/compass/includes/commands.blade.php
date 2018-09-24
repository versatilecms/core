@if($artisan_output)
    <pre>
        <i class="close-output versatile-x">{{ __('versatile::compass.commands.clear_output') }}</i><span class="art_out">{{ __('versatile::compass.commands.command_output') }}:</span>{{ trim(trim($artisan_output,'"')) }}
    </pre>
@endif

@foreach($commands as $command)
    <div class="command" data-command="{{ $command->name }}">
        <code>php artisan {{ $command->name }}</code>
        <small>{{ $command->description }}</small><i class="versatile-terminal"></i>
        <form action="{{ route('versatile.compass.post') }}" class="cmd_form" method="POST">
            {{ csrf_field() }}
            <input type="text" name="args" autofocus class="form-control" placeholder="{{ __('versatile::compass.commands.additional_args') }}">
            <input type="submit" class="btn btn-primary pull-right delete-confirm"
                    value="{{ __('versatile::compass.commands.run_command') }}">
            <input type="hidden" name="command" id="hidden_cmd" value="{{ $command->name }}">
        </form>
    </div>
@endforeach
