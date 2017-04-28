
    <header>

        <!--Navbar-->
        <nav class="navbar navbar-toggleable-md navbar-dark">
            <div class="container">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav1" aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <strong>{{ config('app.vendor') }}</strong>
                </a>
                <div class="collapse navbar-collapse" id="navbarNav1">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="/about" class="nav-link">About <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown btn-group">
                            <a class="nav-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account</a>
                            <div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu1">
                             @if (Auth::guest())
                                <a class="dropdown-item" href="{{ route('login') }}"><i class="material-icons" role="presentation">lock_outline</i>Login</a>
                             @else
                                <a class="dropdown-item" href="/home"><i class="material-icons" role="presentation">dashboard</i>Dashboard</a>
                                @include('includes.menus.logout-item-form', [ 'css_class' => 'dropdown-item' ])
                             @endif
                            </div>
                        </li>
                    </ul>
                    <form class="form-inline waves-effect waves-light">
                        <input class="form-control" type="text" placeholder="Search">
                    </form>
                </div>
            </div>
        </nav>
        <!--/.Navbar-->

    </header>