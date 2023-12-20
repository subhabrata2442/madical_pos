<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>POS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/fabicon.ico') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/admin/css/app.css') }}">
    <!--<link rel="stylesheet" href="{{ url('assets/admin/css/datatables.bundle.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/css/vendors.bundle.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/css/style.bundle.css') }}">-->
    <link rel="stylesheet" href="{{ url('assets/admin/css/dev.css') }}">

    <script>
 var base_url = "{{url('/')}}";
 var csrf_token = "{{csrf_token()}}";
 var prop = <?php echo json_encode(array('url'=>url('/'), 'ajaxurl' => url('/ajaxpost'),  'csrf_token'=>csrf_token()));?>;
 var decimalpoints = '2';
</script>
    <script src="{{ url('assets/admin/js/app.js') }}"></script>
</head>

<body class="hold-transition dark_mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <x-preloader />
    <x-ajaxloader />
    <div class="wrapper">

        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('assets/admin-lte/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div> --}}

        @include('admin.includes.header')
        @include('admin.includes.sidenav')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 justify-content-between">
                        <div class="col-auto">
                            <h1 class="m-0">
                                {{ !empty($data['heading']) && $data['heading'] ? $data['heading'] : 'Dashboard' }}
                            </h1>
                        </div>
                        <div class="col-auto">
                            <div class="select-store">
                                <ul>
                                    <li><label>Select store</label></li>
                                    <li class="select-store-file">
                                        <select class="form-control select2" id="store" style="width: 100%;">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                @foreach ($data['breadcrumb'] as $item)
                                    <li class="breadcrumb-item active">{{ $item }}</li>
                                @endforeach

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @yield('admin-content')
                </div>
            </section>
        </div>
        <div class="loader_section" style="display:none"><span><div class="loader"></div>Loading, Please wait...</span></div>
        @include('admin.includes.footer')

    </div>
    @yield('scripts')


    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script>

        var admin_type = {{Session::get('admin_type')}};
        var store_id	= {{Session::get('store_id')}};
        var mainUrl = '{{url('/')}}';




        $(document).ready(function() {

            // alert(mainUrl);


            Pusher.logToConsole = true;
            var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
            });
            var channel = pusher.subscribe('stockalert-channel');
            channel.bind('stockalert-event-send-meesages', function(data) {
                console.log(data);
                var totalunreadnotification = $("#totalunreadnotification").val();
                var total = (parseFloat(totalunreadnotification)+1)
                $("#totalunreadnotification").val(total);

                var urls = mainUrl+'/'+data.urls;
                var htmls = '<a href="'+urls+'" class="dropdown-item"><i class="fas fa-envelope mr-2"></i>'+data.message+'</a>';

                if(admin_type==1){
                    if(data.message!=''){
                        $(".zeronoti").hide();
                        $(".appendnotification").prepend(htmls);
                        $(".totalNoti").html(total);
                        toastr.success(data.message);
                    }
                }else if(admin_type==2){
                    if(data.store_id==store_id){
                        if(data.message!=''){
                            $(".zeronoti").hide();
                            $(".appendnotification").prepend(htmls);
                            $(".totalNoti").html(total);
                            toastr.success(data.message);
                        }
                    }
                }

            });
        });

        function seenNotification(ids){
            console.log(ids);
            $.ajax({
                type: "GET",
                cache: false,
                url: '{{url('/seenNotification')}}',
                dataType: 'html',
                data: {'ids':ids},
                success: function(data) {

                },
                beforeSend: function() {

                },
                complete: function() {

                }
            });

        }

        @if (Session::has('permissioncheck'))
            $(document).ready(function() {
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You don\'t have permission to access!'
                });
            });
        @endif


    </script>

</body>

</html>
