@extends('layouts.site')
@section('breadcrumbs', __('main.search'))
@section('content')
    <style>
      .search {
        min-height: 100vh;
      }
      span.search {
        color: #a3d161;
      / / background: #A3D161;
        border-radius: 5px;
        font-weight: 700;
      }

    </style>

    <div class="search page">
        <div class="container">
            <div class="search-wrapper">
                @include('frontend.sections.breadcrumbs')
                {{--                <div><strong>{{__('main.search')}}:</strong> {{$searchStr}}</div>--}}
                @if($results)
                    <div class="search-all">
                        @foreach($results as $result)

                            <div class="search-item">
                                <div class="row">


                                    <div class="col-md-4 col-12">
                                        <div class="search-img">
                                            <img class="@if($result->image) crops-show__image @else default-img  @endif"
                                                 src="{{ $result->image ? \Illuminate\Support\Facades\Storage::url($result->image->small()) : asset('default_logo.png') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <div class="search-info">

                                             <h2>
                                                 <a href="{{ $result->getTypeUrl($result) /*'/'.app()->getLocale() .'/categories/' . $result->category->slug . '/' . $result->slug*/ }}">
                                                 {!! App\Models\Article::markResult($searchArray,$result->getTitle()) !!}
                                                 </a>
                                             </h2>
                                            {!! App\Models\Article::markResult($searchArray,$result->getText()) !!}
                                            <a href="{{ $result->getTypeUrl($result) /*'/'.app()->getLocale() .'/categories/' . $result->category->slug . '/' . $result->slug*/ }}">
                                                {{__('main.more_detailed')}}
                                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 12L12 1M12 1V12M12 1H1" stroke="#E0E0E0" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </a>
                                            <span class="badge badge-success">{{$result->getTypeLabel()}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        <div class="custom-pagination">
                          {{ $results->onEachSide(3)->withQueryString()->links('frontend.profile.sections.pagination') }}
                        </div>
                        @else
                            <h3>{{__('main.search_not_result')}}</h3>
                        @endif
                    </div>
            </div>
        </div>
    </div>
@endsection
