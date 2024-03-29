@extends('layouts.app')


@section('content')
<div class="modal fade" id="showCompanyPhone" tabindex="-1" role="dialog" aria-labelledby="showCompanyPhoneLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showCompanyPhoneLabel">Свяжитесь с компанией</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="company col-md-12 text-center">

                <img class="img-fluid" width="70px" height="70px" src="{{ Storage::disk('public')->exists($advert->user->attributes->filename) ? asset('storage/' . $advert->user->attributes->filename) : "/images/no-avatar.png"}}" alt="">
                <p class="m-0 text-center">{{ $advert->user->attributes->name }}</p>
                <p class="m-0 text-center"><small class="text-muted text-center">Должность</small></p>
                <hr>
                <p class="mb-2 text-center"><strong>Телефон</strong></p>
                <p class="mb-2 text-center h5"><a href="tel:{{ $advert->user->attributes->number }}">{{ $advert->user->attributes->number }}</a></p>
                <p class="mb-2 text-center text-muted"><small>Скажите, что нашли объявление на ОПТхимик</small></p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/">Главная</a></li>
              <li class="breadcrumb-item"><a href="/tsb/burovaya-promyshlennost">Буровая промышленность</a></li>
              <li class="breadcrumb-item"><a href="/advert/bentonity-i-zameniteli">Бентониты и заменители</a></li>
              <li class="breadcrumb-item active" aria-current="page">Бентонит 100 тонн в наличии</li>
            </ol>
          </nav>
        <div class="row">
            <div class="col-md-7">
                <div class="card h-100 border-0 shadow">
                    <img class="img-fluid m-auto" src="{{ Storage::disk('public')->exists($advert->filename) ? asset('storage/' . $advert->filename) : "https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg"}}" alt="">
                </div>
            </div>
            <div class="col-md-5">
                <div class="card border-0 shadow">
                    <small class="p-3 text-muted">Обновлено: {{ \Carbon\Carbon::parse($advert->created_at)->isoFormat('Do MMMM YYYY HH:mm') }}
                        @if(!empty($advert->favorite) and $advert->favorite->user_id = Auth::id() and $advert->favorite->advert_id = $advert->id)<i id="favorite_del" class="fa fa-heart text-danger float-right" @else <i id="favorite" class="fa fa-heart-o float-right"  @endif style="font-size:17px !important" aria-hidden="true"></i>
                    </small>
                    <h1 class="pl-3 pr-3 h4 text-blue">{{ $advert->title }}</h1>
                    <p class="pl-3 pr-3 text-muted m-0">Оптовая цена</p>
                    <p class="pl-3 pr-3 mb-5"> <span class="h5">{{ $advert->types->first()->price ?? ''}} -</span>  <span class="h5">{{ $advert->types->last()->price ?? ''}} {{ !empty($advert->types->first()->valute) ? $advert->types->first()->valute == "RUB" ? 'руб.' : '' : '' }}</span></p>
                    <div class="company col-md-12">
                        <div class="border">
                            <div class="no-avatar">
                                <img class="img-fluid" width="70px" height="70px" src="{{ Storage::disk('public')->exists($advert->user->attributes->filename) ? asset('storage/' . $advert->user->attributes->filename) : "/images/no-avatar.png"}}" alt="">
                            </div>
                            <p class="m-0 text-center mt-5">{{ $advert->user->attributes->name }}</p>
                            <p class="m-0 text-center"><small class="text-muted text-center">Должность</small></p>
                            <hr>
                            <p class="mb-2 text-center h5"><a href="{{ route('company.show', $advert->user->id) }}">{{ $advert->user->attributes->company }}</a>, {{ $advert->user->attributes->cities?->name }}</p>
                            <p class="mb-2 text-center text-muted"><small>На ОПТхимик с {{ \Carbon\Carbon::parse($advert->user->attributes->created_at)->isoFormat('Do MMMM YYYY') }}</small></p>
                            <p class="mb-2 text-center "><a href="{{ route('company.adverts', $advert->user->id) }}">Все объявления компании: </a>{{ count($advert->user->adverts) }}</p>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 mt-3">
                        <div class="row">
                            <button class="btn btn-primary col-md-5 m-auto text-white dot" data-toggle="modal" data-target="#showCompanyPhone">+7 показать телефон</button>
                            <button class="btn btn-success col-md-5 m-auto">Написать сообщение</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7 pt-3">
                <div class="card p-4 border-0 shadow">
                    <h2>Описание</h2>
                    <hr>
                    <div class="card-body p-0">
                        {!! $advert->content !!}
                    </div>
                    <hr>
                    <div>
                        <small class="text-muted">Номер объявления: {{ $advert->id }} | Просмотров: {{ $advert->views }}</small>
                        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                        <script src="//yastatic.net/share2/share.js"></script>
                        <div class="ya-share2 float-right" data-services="vkontakte,facebook,gplus,twitter,pinterest"></div>
                    </div>
                </div>

                <h2 class="pt-4">Похожие объявления:</h2>
                @if(!empty($similar_adverts))
                    @foreach($similar_adverts as $advert)
                        <div class="card mt-3 p-3 border-0 shadow">
                            <div class="row">
                                <span class="col-4">
                                    <img class="img-fluid" src="{{ '/storage/'.$advert->filename }}" alt="{{ $advert->title }}">
                                </span>
                                <span class="col-8">
                                    <h3><a class="text-blue h5" href="{{ route('advert.show',$advert->slug) }}">{{ $advert->title }}</a></h3>
                                <h5>{{ !empty($advert->types->first()->price) ? preg_replace('/[^\d\-]/','',$advert->types->first()->price) : 'Не указана' }} {{ !empty($advert->types->first()->valute) ? $advert->types->first()->valutes : '' }}</h5>
                                <small class="text-muted mb-2">{{ $advert->user->attributes->company }}, {{ $advert->user->attributes->cities?->name }}, Объявления +{{ count($advert->user->adverts) }}</small>
                                <p class="mt-2 mb-0">{{ Illuminate\Support\Str::limit($advert->content, 160) }}</p>
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection
@push('scripts')
        <script defer>
        $('#favorite').on('click',function(e){
            e.preventDefault;
            var id = "{{ $advert->id }}";
            $.ajax({
              type:'POST',
              url:"{{ route('advert.favorite') }}",
              data: {id:id},
              headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                if (data.redirect) {
                    // data.redirect contains the string URL to redirect to
                    window.location.href = data.redirect;
                } else {
                    $(".flash-message").html(data);
                    $('#favorite').removeClass( "fa-heart-o" )
                    $('#favorite').addClass( "fa-heart text-danger" )
                    $('#favorite').attr('id', 'favorite_del')
                    document.location.reload()
                }
              }
           });
        })

        $('#favorite_del').on('click',function(e){
            e.preventDefault;
            var id = "{{ $advert->id }}";
            var favorite = "{{ !empty($advert->favorite->id) ? $advert->favorite->id : null }}";
            $.ajax({
              type:'POST',
              url:"{{ route('advert.favorite') }}",
              data: {id:id,favorite:favorite},
              headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                 $(".flash-message").html(data);
                 $('#favorite_del').removeClass( "fa-heart" )
                 $('#favorite_del').removeClass( "text-danger" )
                 $('#favorite_del').addClass( "fa-heart-o" )
                 $('#favorite_del').attr('id', 'favorite')
                 document.location.reload()
              }
           });
        })
    </script>
@endpush
