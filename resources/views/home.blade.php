@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-7 my-3">
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
                            <div class="w-100 h-100  image-bg" style='background-image:url("{{"storage/".$top_day->filename ?? 'images/not_found.jpg'}}")'></div>
                        </div>
                        <div class="col-md-9 top-day">
                            <p class="mb-2 font-weight-bold"><a class="text-blue" href="{{ url('advert/'.$top_day->slug) }}">{{ $top_day->title }}</a></p>
                            <p class="mb-2">Описание: {{ Str::limit($top_day->content, 120) }}</p>
                            <p class="mb-2 float-right">Просмотров: {{ $top_day->views_day }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="my-3">Блог компаний</h2>
            <div class="card shadow">
                @foreach ($blogs as $blogs_date => $blog_content)
                    <div class="card-body pt-3 pb-1">
                        <p class="h4">{{ $blogs_date }}</p>
                        @foreach ($blog_content as $blog)
                        <p class="font-weight-bold h4"><a class="text-blue" href="{{ url('blog'.$blog->slug) }}">{{ $blog->name }}</a></p>
                        <p>{!! Str::limit($blog->content, 250) !!}</p>
                        <p class="w-100 text-right"><a href="blog/{{$blog->slug}}">Подробнее...</a></p>
                        <hr class="mb-0">
                        @endforeach
                    </div>
                @endforeach

            </div>
        </div>
        <div class="col-md-5 my-3">
            <h2>Новые объявления</h2>
            @foreach ($new_adverts as $key => $advert)
                <div class="card my-2 shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center d-flex flex-wrap align-items-center">
                                <div class="w-100 h-100  image-bg" style='background-image:url("{{"storage/".$advert->filename ?? 'images/not_found.jpg'}}")'></div>
                            </div>
                            <div class="col-md-8 post-content">

                                <p class="mb-2"><a href="{{ url('advert/'.$advert->slug) }}">{{ $advert->title }}</a></p>
                                <p class="mb-2">Описание: {{ Str::limit($advert->content, 60) }}</p>
                                <p class="mb-2">Добавлено: {{ $advert->create_date }}</p>
                                <p class="mb-2"><b>Цена: {{ $advert->types->first()->price ?? ''}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
