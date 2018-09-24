<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('versatile.dashboard') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Versatile::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ versatile_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ Versatile::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Versatile::setting('admin.title', 'Versatile')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Versatile::image( Versatile::setting('admin.bg_image'), versatile_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4>{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('versatile.profile') }}" class="btn btn-primary">{{ __('versatile::generic.profile') }}</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>

        {!! menu('admin', 'admin_menu') !!}
    </nav>
</div>
