
        <!-- App Header -->
        <div class="appHeader bg-purple text-light">
            <div class="left">
                <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 512 512"><path fill="none" stroke="white" stroke-linecap="round" stroke-miterlimit="10" stroke-width="48" d="M88 152h336M88 256h336M88 360h336"/></svg>
                </a>
            </div>
            <div class="pageTitle">
                <h3 class="text-light">LAKSA MEDICAL</h3>
            </div>
            <div class="right">
                <div class="headerButton" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true">
                    @if(Auth::user()->photo == '')
                        <img src="{{ asset('content/avatar.jpg') }}" alt="picture" class="imaged w32 rounded">
                    @else
                        <img src="{{ asset('storage/photos/' . Auth::user()->photo ) }}" alt="picture" class="imaged w32 rounded">
                    @endif

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                         {{ __('Logout') }}
                     </a>

                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                         @csrf
                     </form>
                    </div>
                </div>
            </div>
            <div class="progress" style="display:none;position:absolute;top:50px;z-index:4;left:0px;width: 100%">
                <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">0%</span>
                </div>
            </div>
        </div>
        <!-- * App Header -->

        <!-- App Sidebar -->
        <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <!-- profile box -->
                        <div class="profileBox pt-2 pb-2">
                            <div class="image-wrapper">
                           @if(Auth::user()->photo == '')
                                <img src="{{ asset('content/avatar.jpg') }}" alt="picture" class="imaged w36 rounded">
                            @else
                                <img src="{{ asset('storage/photos/' . Auth::user()->photo ) }}" alt="picture" class="imaged w36 rounded">
                            @endif
  
                            </div>
                            <div class="in">
                                <strong>{{ Auth::user()->employees_name }}</strong>
                                <div class="text-muted"></div>
                            </div>
                            <a href="#" class="btn btn-link btn-icon sidebar-close" data-dismiss="modal">
                                <ion-icon name="close-outline"></ion-icon>
                            </a>
                        </div>
                        <!-- * profile box -->

                        <!-- menu -->
                        <div class="listview-title mt-1">Main Menu</div>
                        <ul class="listview flush transparent no-line image-listview">
                            <li>
                                <a href="./" class="item">
                                    <div class="icon-box bg-purle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M80 212v236a16 16 0 0 0 16 16h96V328a24 24 0 0 1 24-24h80a24 24 0 0 1 24 24v136h96a16 16 0 0 0 16-16V212"/><path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M480 256L266.89 52c-5-5.28-16.69-5.34-21.78 0L32 256m368-77V64h-48v69"/></svg>
                                    </div> Home 
                                </a>
                            </li>
                            <li>
                                <a href="./logout" class="item">
                                    <div class="icon-box bg-purle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M304 336v40a40 40 0 0 1-40 40H104a40 40 0 0 1-40-40V136a40 40 0 0 1 40-40h152c22.09 0 48 17.91 48 40v40m64 160l80-80l-80-80m-192 80h256"/></svg>
                                    </div> Keluar
                                </a>
                            </li>
                        </ul>
                        <!-- * menu -->
                    </div>
                </div>
            </div>
        </div>
        <!-- * App Sidebar -->

