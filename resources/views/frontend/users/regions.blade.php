<div class="form-group">
    <label class="form-label">{{__('main.region')}}</label>
    <select class="-form-control regions" name="region_id">
        @isset($regions)
        @foreach($regions as $region)
            <option value="{{$region->id}}">{{$region->title_ru}}</option>
        @endforeach
        @endisset
    </select>
</div>

<div class="form-group">
    <label class="form-label">{{__('main.city')}}</label>
    <select class="-form-control" name="city_id" id="cities">
        @isset($cities)
        @foreach($cities as $city)
            <option value="{{$city->id}}"
                    @if(isset($user->info) && $user->info->city_id==$city->id) selected @endif >{{$city->title_ru}}
            </option>
        @endforeach
        @endisset
    </select>
</div>



@section('js')
    <script>
        $(document).ready(function () {

            $('.regions').change(function () {
                region_id = $(this).val();
                $.ajax({
                    type: 'post',
                    url: '/ru/city/get-cities',
                    data: { '_token': _csrf_token,'region_id':region_id },
                    success: function($response) {
                        if ($response.status) {
                            $('#cities').html($response.data);
                        } else {
                            alert('Can`t get cities!');
                        }
                    },
                    error: function(e) {
                        alert('Server error or Internet connection failed!')
                    }
                });
            });

        });

    </script>

@endsection
