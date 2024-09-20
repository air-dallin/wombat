@extends('layouts.site')
@section('content')


 <div class="not-found">
     <div class="container">
         <div class="not-found__wrapper">
             <div class="not-found__img">
                 <img src="{{ asset('images/404.jpg') }}" alt="404">
             </div>
             <h2>Страница не найдена</h2>
             <a href="{{ url('/',app()->getLocale()) }}">Вернуться на главную</a>
         </div>
     </div>
 </div>
@endsection
