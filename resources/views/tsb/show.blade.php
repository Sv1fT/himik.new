@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1>Товарно сырьевая база</h1>
                <div class="card shadow">

                    @foreach($categories as $category)
                        <a class="nav-link text-blue font-weight-bold" href="{{ $category->slug }}">{{ $category->title }} [{{ count($category->advert) }}]</a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <a href="">Добавить объявление</a>
                <h2>Оформить подписку</h2>
                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="row form-group">
                                <select name="" id="" class="form-control"></select>
                            </div>
                            <div class="row form-group">
                                <input type="text" class="form-control">
                            </div>
                            <div class="row form-group mb-0">
                                <button class="form-control bg-primary text-white">Подписаться на рассылку</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Подписались: 1234
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
