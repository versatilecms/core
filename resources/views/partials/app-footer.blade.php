<footer class="app-footer">
    <div class="site-footer-right">
        {!! __('versatile::theme.footer_copyright') !!} <a href="https://codions.com" target="_blank">Codions</a>
        @php $version = Versatile::getVersion(); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    </div>
</footer>
