<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col">
    <!-- Навигация -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Логотип -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-900">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('links.index') }}" class="ml-4 text-gray-600 hover:text-gray-900">Мои ссылки</a>
                        <form method="POST" action="{{ route('logout') }}" class="ml-4">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                Выйти
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Войти</a>
                        <a href="{{ route('register') }}" class="ml-4 text-gray-600 hover:text-gray-900">Регистрация</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Контент -->
    <main class="flex-grow container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Футер -->
    <footer class="bg-white shadow mt-4">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </footer>
</div>
</body>
</html>
