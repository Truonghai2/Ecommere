<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="auth_id" content="{{ auth()->id() }}">
    <title>Quý Bình</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="{{ secure_asset('img/logo.png') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
    <script src="{{ secure_asset('js/app.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>

    <link
      rel="stylesheet"
      href="https://unpkg.com/swiper/swiper-bundle.min.css"
    />

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ secure_asset('css/loading.css') }}">


</head>
<body>
  
  <div class="block-loading container">
    <div class="content position-absolute top-50" style="transform: translate(-50%, -50%); left:50%;">
      <div class="bouncing-logo text-center">
        <img src="{{ secure_asset('img/logo.png') }}" width="120px" alt="">
      </div>
      {{-- <div class="loader text-center"></div> --}}
    </div>
  </div>


    <div id="wrapper" class="d-none">
        @include('flash::message')
        @include('layout.header')


        @yield("main-home")
        @yield("login")
        @yield('content')



        @yield("register")


        @include('layout.footer')
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>



<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

<script>
  window.onload = function(){
    $('.block-loading').addClass('d-none');
    $('#wrapper').removeClass('d-none');
  }
</script>
<script>
  $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>
<script>
  var subscription_id = "";

  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
      appId: "f41bdea2-508a-4082-9951-e77411fa9f53",
    });

    // Đảm bảo subscription_id được lấy sau khi OneSignal được khởi tạo
    const user = await OneSignal.User.PushSubscription.id;
    subscription_id = user; // Đặt giá trị của subscription_id

    // Gửi AJAX sau khi subscription_id đã được thiết lập
    $.ajax({
        url: '{{ route('user.addsubsription') }}',
        method: "GET",
        data: {
            subscription_id: subscription_id,
            user_id: $('meta[name=auth_id]').attr('content') ?? 0,
        },
    });
  });
</script>

</body>
</html>