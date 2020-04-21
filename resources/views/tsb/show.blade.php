@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-4 my-4">
                <form action="" method="get">
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select name="category" id="category">
                            <option value="">Нет категории</option>
                            @foreach(App\Category::all() as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <h1>{{$title}}</h1>
                <div class="row">
                    @foreach($adverts as $advert)
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 rounded-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="img-fluid" src="{{asset('/storage/'.$advert->filename)}}" alt="">
                                        </div>
                                        <div class="col-9 post-content">
                                            <a href="/advert/show/{{$advert->slug}}">@if($advert->show = 0)<span class="alert alert-danger">куплю</span>@endif{{$advert->title}}</a>
                                            <p class="text-muted">{{ $advert->user->attributes->company }}, {{ $advert->user->attributes->city->name }}</p>
                                            <p>
                                                {{$advert->short_content}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $adverts->render() }}
            </div>

        </div>
    </div>
@endsection
