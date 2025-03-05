@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="text-center mb-4">Мои ссылки</h1>
                <form action="{{ route('links.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="url" name="original_url" class="form-control" required placeholder="Введите URL">
                    <button type="submit" class="btn btn-primary">Сократить</button>
                </form>

                @if ($links->isNotEmpty())
                    <ul class="list-group mt-4">
                        @foreach ($links as $link)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('links.redirect', $link->short_code) }}" target="_blank" class="text-decoration-none">
                                        {{ url('/link/' . $link->short_code) }}
                                    </a>
                                    <small class="text-muted">({{ $link->clicks }} кликов)</small>
                                </div>
                                <form action="{{ route('links.destroy', $link) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted mt-3">У вас пока нет сокращенных ссылок.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
