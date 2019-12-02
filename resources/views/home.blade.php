@extends('layouts.app')

@section('content')
    <div class="container-fluid slider">

    </div>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8 my-3">
            <h2>Товар дня</h2>
            <div class="card shadow">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 text-center d-flex flex-wrap align-items-center" style="">
                            <div class="w-100 h-100  image-bg" style='background-image:url({{ Storage::disk('local')->exists($advert->filename) ? asset('storage/' . $advert->filename) : "https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg" }})'></div>
                        </div>
                        <div class="col-md-9 top-day">
                            <p class="mb-2 font-weight-bold"><a class="text-blue" href="{{ url('advert/'.$top_day->slug) }}">{{ $top_day->title }}</a></p>
                            <p class="mb-2">Описание: {{ Illuminate\Support\Str::limit($top_day->content, 120) }}</p>
                            <p class="mb-2 float-right">Просмотров: {{ $top_day->views_day }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="my-3">Новые объявления</h2>
            <div class="row">
                @foreach ($new_adverts as $key => $advert)
                    <div class="my-2 col-lg-4 col-md-6 col-sm-12 ">
                        <div class="card h-100 shadow">
                            <img class="card-img-top" src="{{ Storage::disk('local')->exists($advert->filename) ? asset('storage/' . $advert->filename) : "https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg"}}" alt="Card image cap">
                            <div class="card-body">
                                <div class="post-content">
                                    <p class="mb-2"><a class="text-blue font-weight-bold" href="{{ url('advert/'.$advert->slug) }}">{{ $advert->title }}</a></p>
                                    <p class="mb-2">Описание: {{ Illuminate\Support\Str::limit($advert->content, 60) }}</p>
                                    <p class="mb-2">Добавлено: {{ $advert->create_date }}</p>
                                    <p class="mb-2"><b>Цена: {{ $advert->types->first()->price ?? ''}}</b></p>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                {{ \Carbon\Carbon::parse($advert->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4 my-3">
            <h2>Блог компаний</h2>
            <div class="card shadow">
                @foreach ($blogs as $blogs_date => $blog_content)
                    <div class="card-body pt-3 pb-1">
                        <p class="h5">{{ $blogs_date }}</p>
                        @foreach ($blog_content as $blog)
                            <p class="font-weight-bold h5"><a class="text-blue" href="{{ url('blog'.$blog->slug) }}">{{ $blog->name }}</a></p>
                            <p>{!! Illuminate\Support\Str::limit($blog->content, 250) !!}</p>
                            <p class="w-100 text-right"><a href="blog/{{$blog->slug}}">Подробнее...</a></p>
                            <hr class="mb-0">
                        @endforeach
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
