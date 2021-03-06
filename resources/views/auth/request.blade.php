<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if (config('versatile.multilingual.rtl')) dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>Admin - {{ Versatile::setting("admin.title") }}</title>
    <link rel="stylesheet" href="{{ versatile_asset('css/app.css') }}">
    @if (config('versatile.multilingual.rtl'))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ versatile_asset('css/rtl.css') }}">
    @endif
    <style>
        body {
            background-image:url('{{ Versatile::image( Versatile::setting("admin.bg_image"), versatile_asset("images/bg.jpg") ) }}');
            background-color: {{ Versatile::setting("admin.bg_color", "#FFFFFF" ) }};
        }
        body.login .login-sidebar {
            border-top:5px solid {{ config('versatile.primary_color','#22A7F0') }};
        }
        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top:0px !important;
                border-left:5px solid {{ config('versatile.primary_color','#22A7F0') }};
            }
        }
        body.login .form-group-default.focused{
            border-color:{{ config('versatile.primary_color','#22A7F0') }};
        }
        .login-button, .bar:before, .bar:after{
            background:{{ config('versatile.primary_color','#22A7F0') }};
        }
    </style>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body class="login">
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg animated"></div>
        <div class="hidden-xs col-sm-7 col-md-8">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <?php $admin_logo_img = Versatile::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ versatile_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ Versatile::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="copy animated fadeIn">
                            <h1>{{ Versatile::setting('admin.title', 'Versatile') }}</h1>
                            <p>{{ Versatile::setting('admin.description', __('versatile::auth.welcome')) }}</p>
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar">

            <div class="login-container">

                @if(Session::has('status'))
                    <div class="alert alert-success"> {{ Session::get('status') }}</div>
                @endif

                <p>{{ __('versatile::auth.request_password') }}</p>

                <form action="{{ route('versatile.password.email') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group form-group-default" id="emailGroup">
                        <label>{{ __('versatile::generic.email') }}</label>
                        <div class="controls">
                            <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('versatile::generic.email') }}" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block login-button">
                        <span class="signingin hidden"><span class="versatile-refresh"></span> {{ __('versatile::generic.processing') }}...</span>
                        <span class="signin">{{ __('versatile::auth.request_password') }}</span>
                    </button>

                </form>

                <div style="clear:both"></div>

                @if(!$errors->isEmpty())
                    <div class="alert alert-red">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div> <!-- .login-container -->

        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
<script>
  var btn = document.querySelector('button[type="submit"]');
  var form = document.forms[0];
  var email = document.querySelector('[name="email"]');
  var password = document.querySelector('[name="password"]');
  btn.addEventListener('click', function(ev){
    if (form.checkValidity()) {
      btn.querySelector('.signingin').className = 'signingin';
      btn.querySelector('.signin').className = 'signin hidden';
    } else {
      ev.preventDefault();
    }
  });
  email.focus();
  document.getElementById('emailGroup').classList.add("focused");

  // Focus events for email and password fields
  email.addEventListener('focusin', function(e){
    document.getElementById('emailGroup').classList.add("focused");
  });
  email.addEventListener('focusout', function(e){
    document.getElementById('emailGroup').classList.remove("focused");
  });

  password.addEventListener('focusin', function(e){
    document.getElementById('passwordGroup').classList.add("focused");
  });
  password.addEventListener('focusout', function(e){
    document.getElementById('passwordGroup').classList.remove("focused");
  });

</script>
</body>
</html>
