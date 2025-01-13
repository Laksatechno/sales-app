<div class="appBottomMenu">
    <a href="/" class="item {{ Request::is('/*') ? 'active' : '' }}">
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
    @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'customer')
    <a href="{{ route('shipments.index')}} " class="item {{ Request::is('shipments*') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24"><path fill="#000" d="M7.502 19q-1.04 0-1.771-.73Q5 17.543 5 16.5H3.379q-.213 0-.356-.144t-.144-.357t.144-.356t.356-.143h1.877q.271-.667.875-1.084Q6.735 14 7.5 14t1.37.416q.603.417.874 1.084h4.618L16.558 6H6.212q-.213 0-.357-.144t-.143-.357t.143-.356T6.212 5h10.577q.384 0 .626.308q.243.308.156.686L16.998 8.5h1.271q.384 0 .727.172q.344.171.566.474l1.797 2.398q.218.292.283.609q.066.316.01.664l-.598 3.037q-.056.292-.284.469t-.518.177h-.483q0 1.039-.728 1.77t-1.77.73t-1.771-.73q-.73-.728-.73-1.77H10q0 1.039-.728 1.77t-1.77.73m8.385-5.75h4.651l.177-.89l-2.138-2.86h-1.818zm-1.283 1.248l.13-.58q.13-.58.33-1.42q.113-.46.198-.85q.084-.39.134-.646l.13-.58q.13-.58.33-1.42t.33-1.42l.13-.58L16.558 6l-2.197 9.5zm-12.315-1.5q-.205 0-.343-.144t-.138-.356t.143-.357t.357-.143h3.48q.213 0 .357.144t.143.357t-.143.356t-.357.143zm2-3.496q-.213 0-.357-.144t-.144-.357t.144-.356t.356-.143h4.5q.213 0 .357.144q.143.144.143.357t-.143.356t-.357.143zM7.5 18q.617 0 1.059-.441Q9 17.117 9 16.5t-.441-1.059T7.5 15t-1.059.441Q6 15.883 6 16.5t.441 1.059Q6.883 18 7.5 18m9.77 0q.617 0 1.058-.441q.441-.442.441-1.059t-.441-1.059T17.269 15t-1.058.441q-.442.442-.442 1.059t.441 1.059q.442.441 1.06.441"/></svg>
            <strong>Shipment</strong>
        </div>
    </a>

    <a href="{{ route ('brochures.index')}}" class="item {{ Request::is ('brochures*') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 17V7c0-1.886 0-2.828-.586-3.414S16.386 3 14.5 3h-5c-1.886 0-2.828 0-3.414.586S5.5 5.114 5.5 7v10c0 1.886 0 2.828.586 3.414S7.614 21 9.5 21h5c1.886 0 2.828 0 3.414-.586S18.5 18.886 18.5 17m0-11h.5c1.414 0 2.121 0 2.56.44C22 6.878 22 7.585 22 9v7c0 1.414 0 2.121-.44 2.56c-.439.44-1.146.44-2.56.44h-.5M5.5 6H5c-1.414 0-2.121 0-2.56.44C2 6.878 2 7.585 2 9v7c0 1.414 0 2.121.44 2.56C2.878 19 3.585 19 5 19h.5m9-11h-5m5 4h-5m5 4h-5" color="#000"/></svg>
            <strong> Brosur </strong>
        </div> 
    </a>
    @endif

    <a href="{{ route('profile') }}" class="item {{ Request::is('profile*') ? 'active' : '' }}">
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