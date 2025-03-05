<body id="body-pd" class="p-0">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_img" style="height: 100%;"> <img src="{{ asset('img/logo.png') }}" alt=""> </div>
    </header>
    <div class="l-navbar bg-color h-100" style="overflow: auto; " id="nav-bar">
        <nav class="nav">
            <div> <a href="{{ route('home') }}" class="nav_logo">
                <img class="nav_logo-icon" style="margin-left: -10px" src="{{ asset('img/logo.png') }}" width="40px" alt=""> 
                <span
                        class="nav_logo-name">Bình Quý</span> </a>
                <div class="nav_list text-white"> 
                        <a href="{{ route('home') }}" class="nav_link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}"> <i class='bx bx-home nav_icon' ></i> <span class="nav_name ">Trang Chủ</span> </a> 
                        <a href="{{ route('admin.view.layout') }}" class="nav_link {{ Route::currentRouteName() == 'admin.view.layout' ? 'active' : '' }}"> <i class='bx bx-layout nav_icon'></i> <span class="nav_name ">Giao diện</span> </a> 
                        <a href="{{ route('admin.categories') }}" class="nav_link {{ Route::currentRouteName() == 'admin.categories' || Route::currentRouteName() == 'admin.thumbnailCategory' ? 'active' : '' }}"> 
                            <i class='bx bx-category-alt nav_icon' ></i>
                                <span class="nav_name ">Danh Mục Sản Phẩm</span> </a> 
                        <a href="{{ route('admin.products') }}" class="nav_link {{ Route::currentRouteName() == 'admin.products' || Route::currentRouteName() == 'admin.addproducts' ? 'active' : '' }}"> 
                            <i class='bx bxs-package nav_icon'></i>
                            <span class="nav_name ">Sản Phẩm</span> </a> 
                        <a href="{{ route('admin.view.orderProduct') }}" class="nav_link {{ Route::currentRouteName() == 'admin.view.orderProduct' || Route::currentRouteName() == 'admin.view.orderProduct' ? 'active' : '' }}"> 
                            <i class='bx bx-money-withdraw nav_icon'></i>
                                <span class="nav_name ">Đơn đặt hàng</span> </a> 
                        <a
                        href="{{ route('users.index') }}" class="nav_link {{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}"> <i class='bx bx-user nav_icon'></i> <span
                            class="nav_name">Quản Lý Người dùng</span> </a> 
                        <a
                            href="{{ route('admin.notification') }}" class="nav_link {{ Route::currentRouteName() == 'admin.notification' ? 'active' : '' }}"> <i class='bx bx-bell nav_icon' ></i> <span
                                class="nav_name">Quản Lý Thông Báo</span> </a> 
                        <a href="/chatify" class="nav_link"> <i
                            class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Messages</span>
                    </a> <a href="#" class="nav_link"> <i class='bx bx-bookmark nav_icon'></i> <span
                            class="nav_name">Bookmark</span> </a> <a href="#" class="nav_link"> <i
                            class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a> <a
                        href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                            class="nav_name">Stats</span> </a> </div>
            </div> <a href="{{ url('/') }}" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                    class="nav_name">Thoát Quản Lý Hệ Thống</span> </a>
        </nav>
    </div>
    <!--Container Main start-->

    
</body>
    
    <!--Container Main end-->

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show')
                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>
