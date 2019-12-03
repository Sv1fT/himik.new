<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="https://opt-himik.ru/image/logo.png" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="m-auto mr-auto navbar-nav py-3 w-100 w-md-75">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Введите запрос" aria-label="Введите запрос" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <select class="input-group-text"aria-describedby="basic-addon2">
                            <option value="">По компаниям</option>
                            <option value="">По объявлениям</option>
                            <option value="">По вакансиям</option>
                            <option value="">По резюме</option>
                        </select>
                    </div>
                    <div class="input-group-append">
                        <button class="input-group-text" id="basic-addon2">Поиск</button>
                    </div>
                </div>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->attributes->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Выход') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-dark shadow-sm p-0">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="m-auto mr-auto navbar-nav w-100">
                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Главная</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="{{ route('tsb.index') }}">Товарно-сырьевая база</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Компании</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Блог компаний</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Работа</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Регионы</a>

                <a class="d-block h-100 nav-header-a py-3 text-center text-white w-100" href="/">Спрос</a>

            </ul>
        </div>
    </div>
</nav>
