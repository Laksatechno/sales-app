<div class="appBottomMenu">
    <a href="{{ route('dashboard') }}" class="item {{ Request::is('home*') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 512 512"><path fill="none" stroke="{{ Request::is('home*') ? '#90319a' : 'black' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M80 212v236a16 16 0 0 0 16 16h96V328a24 24 0 0 1 24-24h80a24 24 0 0 1 24 24v136h96a16 16 0 0 0 16-16V212"/><path fill="none" stroke="{{ Request::is('home*') ? '#90319a' : 'black' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M480 256L266.89 52c-5-5.28-16.69-5.34-21.78 0L32 256m368-77V64h-48v69"/></svg>
            <strong>Home</strong>
        </div>
    </a>

    @if (Auth::user()->role == 'superadmin')
    <a href="{{ route('reports.index') }}" class="item {{ Request::is('reports*') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32"><path fill="#000" d="M15 20h2v4h-2zm5-2h2v6h-2zm-10-4h2v10h-2z"/><path fill="#000" d="M25 5h-3V4a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v1H7a2 2 0 0 0-2 2v21a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2M12 4h8v4h-8Zm13 24H7V7h3v3h12V7h3Z"/></svg>
            <strong>Laporan</strong>
        </div>
    </a>
    @endif

    <a href="{{ url('profile') }}" class="item {{ Request::is('profile*') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 512 512"><path fill="none" stroke="{{ Request::is('profile*') ? '#90319a' : 'black' }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96"/><path fill="none" stroke="{{ Request::is('profile*') ? '#90319a' : 'black' }}" stroke-miterlimit="10" stroke-width="32" d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304Z"/></svg>
            <strong>Profil</strong>
        </div>
    </a>
</div>

<!-- * App Bottom Menu -->

<footer class="text-muted text-center" style="display:none">
   <p>© 2023 - {{ now()->year }} Laksa Medika Internusa</p>
</footer>
@include ('layouts.scripts')
</body>
</html>