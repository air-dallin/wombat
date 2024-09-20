<section class="content">
    <div class="contacts">
        <div class="container">
            <div class="contacts-wrapper">
                <h3 class="our-contacts">{{__('main.our_contacts')}}</h3>
                <div class="contacts-all">
                    <ul class="nav nav-tabs">
                        <li class="nav-item dropdown">
                            <div class="tab-navigation">
                                <select
                                    class="selectpicker"
                                    id="select-box"
                                    data-live-search="true"
                                >
                                    @foreach(__('main.regions_list') as  $region)
                                        <option value="{{ $loop->index+1 }}" data-tokens="{{ $region }}">
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    </ul>

                    @foreach($contacts as $contact)
                        <div id="tab-{{ $contact->id }}" class="tab-content">
                            <div class="contact-list">
                                <ul>
                                    <li>
                                        <small>{{__('main.address')}}:</small>
                                        <h3>{{$contact->getAddress() }}</h3>
                                    </li>
                                    <li>
                                        <small>{{__('main.phone_number')}}:</small>
                                        <div>
                                            <a href="#">{{$contact->phone_1}}</a>
                                            <a href="#">{{$contact->phone_2}}</a>
                                        </div>
                                    </li>
                                    <li>
                                        <small>{{__('main.email')}}:</small>
                                        <a href="#">{{$contact->email}}</a>
                                    </li>
                                    <li>
                                        <small>{{__('main.socials')}}:</small>
                                        <div class="contact-socials">
                                            <a href="{{$contact->telegram}}" target="_blank">
                                                <i class="fab fa-telegram-plane" ></i>
                                            </a>
                                            <a href="{{$contact->facebook}}" target="_blank">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a href="{{$contact->youtube}}" target="_blank">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="contacts-map">
                                {!! $contact->location  !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
