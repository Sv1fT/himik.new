@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1>Товарно сырьевая база</h1>
                <div class="row">
                    @foreach($categories as $category)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow text-center h-100">
                            <img class="img-fluid m-auto" src="https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg" alt="{{ $category->title }}">
                            <a class="nav-link text-blue font-weight-bold" href="{{ $category->slug }}">{{ $category->title }} [{{ count($category->advert) }}]</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <a class="btn btn-lg btn-success w-100" href="">Добавить объявление</a>
                <div class="sticky-top">
                    <h2 class="py-3">Оформить подписку</h2>
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row form-group">
                                    <label for="category" class="label">Выберите категорию</label>
                                    <select name="" id="category" class="form-control"></select>
                                </div>
                                <div class="row form-group">
                                    <label for="form-email" class="label">Email Адрес</label>
                                    <input type="email" placeholder="Введите Email" id="form-email" class="form-control">
                                </div>
                                <div class="row form-group mb-0">
                                    <button class="form-control bg-success text-white">Подписаться на рассылку</button>
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
    </div>
@endsection
