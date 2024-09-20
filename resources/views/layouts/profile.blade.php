<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
  />
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <title>{{ strip_tags(trim($__env->yieldContent('title'))) }}</title>


  <!-- PROFILE 2 -->
  {{--  <link rel="stylesheet" href="{{ asset('profile2/assets/modules/bootstrap/css/bootstrap.min.css') }}">--}}
  <link rel="stylesheet" href="{{ asset('profile2/assets/modules/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('icons/font-awesome-old/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('profile2/assets/modules/select2/dist/css/select2.min.css') }}">
  {{--  <link rel="stylesheet" href="{{ asset('profile2/assets/css/style.css') }}">--}}
  <link rel="stylesheet" href="{{ asset('profile2/assets/css/components.css') }}">
  {{--  <link rel="stylesheet" href="{{ asset('profile2/assets/css/custom/custom.css') }}">--}}
  <link href="{{ asset('css/sorting.css') }}" rel="stylesheet">
  <!-- PROFILE 2 END -->


  <link rel="stylesheet" href="{{ asset('profile/assets/css/slick.css') }}"/>
  <link rel="stylesheet" href="{{ asset('profile/assets/css/aos.css') }}"/>
  <link rel="stylesheet" href="{{ asset('profile/assets/css/output.css') }}"/>
  <link rel="stylesheet" href="{{ asset('profile/assets/css/style.css') }}"/>
  <link rel="stylesheet" href="{{ asset('profile/assets/css/app.css') }}"/>
  <script>
      let _csrf_token = '{{ csrf_token() }}';
      let locale = '{{ app()->getLocale() }}';
  </script>
  <style>

    .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #fafafa;
      color: #000;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
    min-height: 54px;
    }
      table button {
          padding:20px;
      }
      table a.saldo-link:hover{
          color:orange;
      }
  </style>
</head>
<body>
<!-- layout start -->
<div class="layout-wrapper active w-full">
  <div class="relative flex w-full">
    @include('frontend.profile.sections.sidebar')
    <div class="body-wrapper flex-1 overflow-x-hidden dark:bg-darkblack-500">
      @include('frontend.profile.sections.header')
      <main class="w-full px-6 pb-6 pt-[100px] sm:pt-[156px] xl:px-12 xl:pb-12">
        @yield('content')
      </main>
    </div>
  </div>
</div>


<div class="absolute bottom-10 right-12 z-50" id="appointment" style="display:none;position: fixed; right: 30px; bottom: 40px;">
    <div class="bg-bgray-200 dark:bg-darkblack-500 p-7 rounded-xl w-[400px]">
        <div class="flex-row space-x-6 2xl:flex-row 2xl:space-x-6 flex md:flex-col md:space-x-0 items-center">
            <div class="progess-bar flex justify-center md:mb-[13px] xl:mb-0 mb-0">
                <div class="w-12 tab-icon transition-all h-12 shrink-0 rounded-full inline-flex items-center justify-center bg-bgray-100">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.0717 4.06949C8.26334 4.49348 6.01734 6.81294 5.67964 9.79403L5.33476 12.8385C5.24906 13.595 4.94246 14.3069 4.45549 14.88C3.42209 16.0964 4.26081 18 5.83014 18H18.1699C19.7392 18 20.5779 16.0964 19.5445 14.88C19.0575 14.3069 18.7509 13.595 18.6652 12.8385L18.4373 10.8267M15 20C14.5633 21.1652 13.385 22 12 22C10.615 22 9.43668 21.1652 9 20M20 5C20 6.65685 18.6569 8 17 8C15.3431 8 14 6.65685 14 5C14 3.34315 15.3431 2 17 2C18.6569 2 20 3.34315 20 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
            </div>
            <div class="flex flex-col md:items-center xl:items-start items-start">
                <h4 class="text-bgray-900 dark:text-white text-base font-bold">{{__('main.new_payment_order')}}</h4>
                <span class="text-xs font-medium text-bgray-700 dark:text-darkblack-300" id="payment_order_company_name"></span>
                <span class="text-xs font-medium text-bgray-700 dark:text-darkblack-300" id="payment_order_info"></span>
            </div>
        </div>

        <button class="w-full mt-4 bg-success-300 hover:bg-success-400 text-white font-bold text-xs h-[52px] rounded-lg transition-all" id="appointmentbtn" style="background: orange">{{__('main.choice_movements')}}</button>

        <div class="w-full mt-4  h-[58px] flex space-x-4 items-center" id="appointmentsel" style="display: none;">
            <div class="lg:w-[318px] w-full h-full border border-transparent dark:border-darkblack-400 ">
                <select class="w-full h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:ring-0 dark:bg-darkblack-500 dark:text-white _select2-hidden-accessible" name="purpose_id" required="" tabindex="-1" aria-hidden="true" id="payment_order_movement_id">
                    <option value="">{{__('main.choice_movements')}}</option>
                    @if(!empty($movements))
                        @foreach($movements as $item)
                        <option value="{{$item->id}}">{{$item->getTitle()}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <button class="bg-success-400 rounded-lg flex items-center justify-center w-[52px] h-[52px]  font-semibold text-sm gap-1.5 text-white" style="background: orange" id="send_movement">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.0586 7.09154L7.92522 3.52487C3.13355 1.12487 1.16689 3.09153 3.56689 7.8832L4.29189 9.3332C4.50022 9.7582 4.50022 10.2499 4.29189 10.6749L3.56689 12.1165C1.16689 16.9082 3.12522 18.8749 7.92522 16.4749L15.0586 12.9082C18.2586 11.3082 18.2586 8.69153 15.0586 7.09154ZM12.3669 10.6249H7.86689C7.52522 10.6249 7.24189 10.3415 7.24189 9.99987C7.24189 9.6582 7.52522 9.37487 7.86689 9.37487H12.3669C12.7086 9.37487 12.9919 9.6582 12.9919 9.99987C12.9919 10.3415 12.7086 10.6249 12.3669 10.6249Z" fill="white"></path>
                </svg>
            </button>
        </div>

    </div>
</div>



<!-- layout end -->
<!--scripts -->
<script src="{{ asset('profile/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('profile/assets/js/aos.js') }}"></script>
<script src="{{ asset('profile/assets/js/slick.min.js') }}"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('profile/assets/js/quill.min.js') }}"></script>
<script src="{{ asset('profile/assets/js/main.js') }}"></script>
<script src="{{ asset('profile/assets/js/chart.js') }}"></script>
@include('frontend.sections.notify')

{{--<script src="{{ asset('profile2/assets/modules/jquery.min.js') }}"></script>--}}
<script src="{{ asset('profile2/assets/modules/popper.js') }}"></script>
<script src="{{ asset('profile2/assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('profile2/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('profile2/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('profile2/assets/js/stisla.js') }}"></script>
<script type="text/javascript">

    $('input').on('input change keyup',function(e) {
        q = $(this).val();
        if (e.keyCode == 75 && e.key == 'k') $(this).val(q + 'k');
    });

    $('textarea').on('input change keyup',function(e) {
        q = $(this).val();
        if (e.keyCode == 75 && e.key == 'k') $(this).val(q + 'k');
    });


    $(".card-slider").slick({
        dots: true,
        infinite: true,
        autoplay: true,
        speed: 500,
        fade: true,
        cssEase: "linear",
        arrows: false,
    });

    function totalEarn() {
        const ctx_bids = document.getElementById("totalEarn").getContext("2d");
        const bitsMonth = [
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Feb",
            "Mar",
            "Afril",
            "May",
        ];
        const bitsData = [
            0, 10, 0, 65, 0, 25, 0, 35, 20, 100, 40, 75, 50, 85, 60,
        ];
        const totalEarn = new Chart(ctx_bids, {
            type: "line",
            data: {
                labels: bitsMonth,
                datasets: [
                    {
                        label: "Visitor",
                        data: bitsData,
                        backgroundColor: () => {
                            const chart = document
                                .getElementById("totalEarn")
                                .getContext("2d");
                            const gradient = chart.createLinearGradient(0, 0, 0, 450);
                            gradient.addColorStop(0, "rgba(34, 197, 94,0.41)");
                            gradient.addColorStop(0.2, "rgba(255, 255, 255, 0)");

                            return gradient;
                        },
                        borderColor: "#ffa500",
                        pointRadius: 0,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#ffa500",
                        borderWidth: 1,
                        fill: true,
                        fillColor: "#fff",
                        tension: 0.4,
                    },
                ],
            },
            options: {
                layout: {
                    padding: {
                        bottom: -20,
                    },
                },
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },

                plugins: {
                    legend: {
                        position: "top",
                        display: false,
                    },
                    title: {
                        display: false,
                        text: "Visitor: 2k",
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
            },
        });
    }

    totalEarn();

    function totalSpendingChart() {
        let ctx_bids = document
            .getElementById("totalSpending")
            .getContext("2d");
        let bitsMonth = [
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Feb",
            "Mar",
            "Afril",
            "May",
        ];
        let bitsData = [
            0, 10, 0, 65, 0, 25, 0, 35, 20, 100, 40, 75, 50, 85, 60,
        ];
        let totalEarn = new Chart(ctx_bids, {
            type: "line",
            data: {
                labels: bitsMonth,
                datasets: [
                    {
                        label: "Visitor",
                        data: bitsData,
                        backgroundColor: () => {
                            const chart = document
                                .getElementById("totalEarn")
                                .getContext("2d");
                            const gradient = chart.createLinearGradient(0, 0, 0, 450);
                            gradient.addColorStop(0, "rgba(34, 197, 94,0.41)");
                            gradient.addColorStop(0.2, "rgba(255, 255, 255, 0)");

                            return gradient;
                        },
                        borderColor: "#ffa500",
                        pointRadius: 0,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#ffa500",
                        borderWidth: 1,
                        fill: true,
                        fillColor: "#fff",
                        tension: 0.4,
                    },
                ],
            },
            options: {
                layout: {
                    padding: {
                        bottom: -20,
                    },
                },
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },

                plugins: {
                    legend: {
                        position: "top",
                        display: false,
                    },
                    title: {
                        display: false,
                        text: "Visitor: 2k",
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
            },
        });
    }

    totalSpendingChart();

    function totalGoal() {
        let ctx_bids = document.getElementById("totalGoal").getContext("2d");
        let bitsMonth = [
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Jan",
            "Feb",
            "Mar",
            "Afril",
            "May",
            "Feb",
            "Mar",
            "Afril",
            "May",
        ];
        let bitsData = [
            0, 10, 0, 65, 0, 25, 0, 35, 20, 100, 40, 75, 50, 85, 60,
        ];
        let totalEarn = new Chart(ctx_bids, {
            type: "line",
            data: {
                labels: bitsMonth,
                datasets: [
                    {
                        label: "Visitor",
                        data: bitsData,
                        backgroundColor: () => {
                            const chart = document
                                .getElementById("totalGoal")
                                .getContext("2d");
                            const gradient = chart.createLinearGradient(0, 0, 0, 450);
                            gradient.addColorStop(0, "rgba(34, 197, 94,0.41)");
                            gradient.addColorStop(0.2, "rgba(255, 255, 255, 0)");
                            console.log({gradient});
                            return gradient;
                        },
                        borderColor: "#ffa500",
                        pointRadius: 0,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#ffa500",
                        borderWidth: 1,
                        fill: true,
                        fillColor: "#fff",
                        tension: 0.4,
                    },
                ],
            },
            options: {
                layout: {
                    padding: {
                        bottom: -20,
                    },
                },
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                    y: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },

                plugins: {
                    legend: {
                        position: "top",
                        display: false,
                    },
                    title: {
                        display: false,
                        text: "Visitor: 2k",
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
            },
        });
    }

    totalGoal();


    //chart dark mode
    let themeToggleSwitch = document.getElementById("theme-toggle");

    //onclick

    if (themeToggleSwitch) {
        themeToggleSwitch.addEventListener("click", function () {
            if (
                document.documentElement.classList[0] === "dark" ||
                localStorage.theme === "dark"
            ) {
                revenueFlow.data.datasets = dataSetsDark;
                revenueFlow.options.scales.y.ticks.color = "white";
                revenueFlow.options.scales.x.ticks.color = "white";
                revenueFlow.options.scales.x.grid.color = "#222429";
                revenueFlow.options.scales.y.grid.color = "#222429";
                revenueFlow.update();
            } else {
                revenueFlow.data.datasets = dataSetsLight;
                revenueFlow.options.scales.y.ticks.color = "black";
                revenueFlow.options.scales.x.ticks.color = "black";
                revenueFlow.options.scales.x.grid.color = "rgb(243 ,246, 255 ,1)";
                revenueFlow.options.scales.y.grid.color = "rgb(243 ,246, 255 ,1)";
                revenueFlow.update();
            }
        });
    }

    //initial load
    if (
        localStorage.theme === "dark" ||
        window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
        revenueFlow.data.datasets = dataSetsDark;
        revenueFlow.options.scales.y.ticks.color = "white";
        revenueFlow.options.scales.x.ticks.color = "white";
        revenueFlow.options.scales.x.grid.color = "#222429";
        revenueFlow.options.scales.y.grid.color = "#222429";
    } else {
        revenueFlow.data.datasets = dataSetsLight;
        revenueFlow.options.scales.y.ticks.color = "black";
        revenueFlow.options.scales.x.ticks.color = "black";
        revenueFlow.options.scales.x.grid.color = "rgb(243 ,246, 255 ,1)";
        revenueFlow.options.scales.y.grid.color = "rgb(243 ,246, 255 ,1)";
    }
    revenueFlow.update();
</script>

{{--PROFILE 2--}}
<!-- JS Libraies -->
<script src="{{ asset('profile2/assets/modules/select2/dist/js/select2.min.js') }}"></script>
{{--
<script src="{{ asset('profile/assets/modules/summernote/summernote-bs4.min.js') }}"></script>
--}}
{{--<script src="{{ asset('profile/assets/modules/dropzonejs/min/dropzone.min.js') }}"></script>--}}

<!-- Page Specific JS File -->
<script src="{{ asset('profile2/assets/js/page/bootstrap-modal.js') }}"></script>
<script src="{{ asset('profile2/assets/js/page/inputmask.min.js') }}"></script>
{{--<script src="{{ asset('profile/assets/js/page/components-multiple-upload.js') }}"></script>--}}

<!-- Template JS File -->
<script src="{{ asset('profile2/assets/js/scripts.js') }}"></script>
<script src="{{ asset('profile2/assets/js/custom.js') }}"></script>
<script src="{{ asset('js/sorting.js') }}"></script>
<script src="{{ asset('profile2/assets/js/global.js') }}"></script>

<script>
    $(document).ready(function(){

        var currentPaymentOrder = 0;
        var intervalPaymentOrder = setTimeout( getOrder(),getRandomInt(10,25));

        function getOrder(){
            clearTimeout(intervalPaymentOrder)

            $.ajax({
                type: 'post',
                url: '/ru/profile/modules/payment_order/get-order',
                data: {'_token': _csrf_token},
                success: function ($response) {
                    if ($response.status) {
                        currentPaymentOrder = $response.data.id;
                        $('#payment_order_company_name').text($response.data.company_name)
                        $('#payment_order_info').text($response.data.info)
                        $("#appointment").fadeIn(1000);
                        $("#appointmentsel").hide();
                        $("#appointmentbtn").show();
                        $("#appointmentbtn").click(function(){
                            $(this).hide();                 // скрыть кнопку
                            $("#appointmentsel").show();    // показать блок
                        });
                        if(currentPaymentOrder!=$response.data.id) {
                            intervalPaymentOrder = setTimeout(getOrder(), getRandomInt(5, 10))
                            currentPaymentOrder = $response.id
                        }
                    } else {
                        clearTimeout(intervalPaymentOrder);
                        if(currentPaymentOrder!=0) {
                            intervalPaymentOrder = setTimeout(getOrder(), 25000)
                        }
                    }
                },
                error: function (e) {
                    alert(e)
                }
            });
        }

        $('#send_movement').click(function(){
            console.log('send..');
            var movement_id = $('#payment_order_movement_id').val();
            if(movement_id==undefined || movement_id==''){
                alert('{{__('main.movement_not_set')}}');
                return false;
            }
            $.ajax({
                type: 'post',
                url: '/ru/profile/modules/payment_order/set-movement',
                data: {'_token': _csrf_token, 'id':currentPaymentOrder,'movement_id':movement_id},
                success: function ($response) {
                    if ($response.status) {
                        currentPaymentOrder = 0;
                        intervalPaymentOrder = setTimeout(getOrder(), getRandomInt(5, 10))
                        $("#appointment").fadeOut(500);
                    } else {
                        intervalPaymentOrder = setTimeout(getOrder(),getRandomInt(30,50))
                        console.log(intervalPaymentOrder)
                    }
                },
                error: function (e) {
                    alert(e)
                }
            });
        });

        function getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return (Math.floor(Math.random() * (max - min + 1)) + min)*1000;
        }

    });
</script>

@yield('js')

</body>
</html>
