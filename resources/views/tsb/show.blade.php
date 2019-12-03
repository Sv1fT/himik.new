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

            </div>
        </div>
    </div>
@endsection
