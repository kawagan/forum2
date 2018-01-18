<nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">

                        <li class="dropdown">
                            <a href="/threads" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Threads <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/threads">All threads</a></li>
                                @if(Auth::check())
                                <li><a href="/threads?by={{auth()->user()->name}}">My Threads </a></li>
                                    <li><a href="/threads?popular=1">Popular Threads</a></li>
                                    <li><a href="/threads?unanswered=1">Unanswered threads</a></li>
                                @endif
                            </ul>
                        </li>

                        <li><a href="/threads/create">create thread</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Channels <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @foreach($channels as $channel)
                                    <li><a href="/threads/{{$channel->slug}}">{{$channel->slug}}</a></li>
                                @endforeach

                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right"> 
                        
                        <!--user must be subscriber to make notifications-->
                        @if(Auth::check())
                         @if($notifications->count()>0)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Notifications <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                              
                                    @foreach($notifications as $notification)
                              
                                        <li>
                                            <a onclick="event.preventDefault();
                                                        document.getElementById('notify-form').submit();"
                                                href="{{$notification->data['link']}}">{{$notification->data['message']}}</a>
                                            
                                            {!! Form::open([
                                             "route"=>['profile.notifications.delete',auth()->user()->id,$notification->id],
                                             "method"=>"delete",
                                             'id'=>"notify-form"
                                            ])!!}
                                            
                                            {!! Form::close() !!}
                                       </li> 
                                    @endforeach
                            </ul>  
                        </li>
                         @endif
                        @endif
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                     <li><a href='{{route('profile',auth()->user()->name) }}'> My profile</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

<script>

</script>