@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <sidebar></sidebar>
            <router-view></router-view>
        </div>
    </div>
@endsection
