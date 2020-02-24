@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
        @include('user.partials.sidebar')

        @if(!empty($user))
        @foreach ($user as $favorite)

                <div class="my-2 col-lg-4 col-md-6 col-sm-12 ">
                    <div class="card h-100 shadow">
                        <img class="card-img-top" src="{{ Storage::disk('public')->exists($favorite->advert->filename) ? asset('storage/' . $favorite->advert->filename) : "https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg"}}" alt="Card image cap">
                        <div class="card-body">
                            <div class="post-content">
                                <p class="mb-2"><a class="text-blue font-weight-bold" href="{{ route('advert.show',$favorite->advert->slug) }}">{{ $favorite->advert->title }}</a></p>
                                <p class="mb-2">Описание: {{ Illuminate\Support\Str::limit($favorite->advert->content, 60) }}</p>
                                <p class="mb-2">Добавлено: {{ $favorite->advert->create_date }}</p>
                                <p class="mb-2"><b>Цена: {{ $favorite->advert->types->first()->price ?? ''}}</b></p>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            {{ \Carbon\Carbon::parse($favorite->advert->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
