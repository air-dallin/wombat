@extends('layout.default')
@section('title',__('main.region'))
@section('content')
  @include('alert')
  <div class="rounded-xl bg-white dark:bg-darkblack-600 p-5">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data"
                @isset($region)
                  action="{{localeRoute('admin.region.update',$region)}}"
                @else
                  action="{{localeRoute('admin.region.store')}}"
              @endisset
          >
            @csrf
            @isset($region)
              @method('PUT')
            @endisset

            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-1" id="uz">
              <div class="flex flex-col gap-2">
                <label class="text-base font-medium text-bgray-600 dark:text-bgray-50">{{__('main.title_uz')}}</label>
                <input type="text" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border  focus:ring-0 dark:bg-darkblack-500 dark:text-white " name="title_uz" value="{{ old('title_uz',isset($region)?$region->title_uz:'') }}" required>
                @error('title_uz')
                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror

              </div>
            </div>

              <?php
              /*<div class="form-group col-6">
                                         <label>{{__('main.region')}}</label>
                                         <select class="form-control" name="region_id">

                                             @foreach($regions as $_region)
                                                 <option value="{{ $_region->id }}"
                                                         @if(isset($region) && $region->region_id==$_region->id) selected @endif >{{ $_region->title_ru }}
                                                 </option>
                                             @endforeach
                                         </select>
                                         @error('region_id')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                         @enderror
                                     </div> */ ?>


            <div class="flex justify-end">
              <button type="submit" class="btn mt-10 rounded-lg px-4 py-3.5 font-semibold text-white" style="background: orange">{{__('main.save')}}</button>
            </div>

          </form>

        </div>

      </div>


    </div>
  </div>

@endsection

@push('scripts')
  <script>
      $(document).ready(function () {
          $('.remove-image').click(function () {
              id = $(this).data('id');
              obj = $('#image_' + id)
              $.ajax({
                  type: 'post',
                  url: '/admin/photo/destroy/' + id,
                  data: {"_token": "{{ csrf_token() }}"},
                  success: function ($response) {
                      console.log($response)
                      if ($response.status) {
                          $(obj).fadeOut();
                      } else {
                          alert('Can`t remove image!')
                      }
                  },
                  error: function (e) {
                      alert('Server error or Internet connection failed!')
                  }
              });
          });
      });

  </script>

@endpush
