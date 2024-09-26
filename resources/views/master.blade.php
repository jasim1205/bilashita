<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from dev3design.com/demos/dev3-menu/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2024 06:27:12 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilashita</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="{{ asset('assets/css/root.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/all-style.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
    {{-- <link href="{{asset('assets/css/icons/bootstrap-icons.css')}}" rel="stylesheet"/> --}}
    <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-5.3.3/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/vendors/bootstrap-5.3.3/js/bootstrap.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">




    <link rel="icon" type="image/x-icon" href="{{ asset('/') }}favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/') }}apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/') }}favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/') }}favicon-16x16.png">
    <!-- Main CSS -->

    <link rel="stylesheet" href="{{ asset('assets/resource/css/single.css') }}" />
</head>

<body class="dev3-h8xji90zyww0">
    <header>
        <div class="dev3-erfacg36b140">
            <i class="dev3-3gvfdjtgvl400 fa-solid fa-list"></i>
            <div class="dev3-3n8wvbuhw7600"><a href="{{ route('home') }}"><img
                        src="{{ asset('assets/images/logo.png') }}" alt=""></a></div>
            <div class="dev3-1s5djf2dxh0g0"></div>
            <div class="dev3-19uwbciixjls0">
                <form action="#" method="post"> <input type="text" placeholder="Search..."> <button
                        type="submit"> <i class="bi bi-search"></i> </button> </form>
            </div>
        </div>
        @php
            $categories = \App\Models\Category::where('status',1)->limit(5)->get();
        @endphp
        <div class="dev3-2lvevpbewi200">
            <div class="container">
                <nav class="dev3-25d3ticmqvls0">
                    <div class="dev3-243ztdnf8yww0 dev3-2gwcdwkqhmi00">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo.png') }}"
                                alt=""></a> <a href="#" class="dev3-di0jbv86i000"><i
                                class="fa-solid fa-xmark"></i></a>
                    </div>
                    <ul class="dev3-243ztdnf8yww0">
                        @foreach ($categories as $category)
                            <li class="dev3-3m8wtemb60w00">
                                <a href="#"><i class="bi bi-justify"></i>{{ $category->category_name }}<i
                                        class="fa-solid fa-angle-down"></i></a>
                                <div class="dev3-1gygrqpnsm5c0">
                                    <div class="container-fluid">
                                        <div class="row">

                                                    @php
                                                        $subcategories = DB::table('db_subcategory')
                                                            ->where('category_id', $category->id)
                                                            ->get();
                                                    @endphp
                                                    {{-- <li><img src="assets/images/ng-1.jpg" alt="Image"/></li> --}}
                                                    @foreach ($subcategories as $sub)
                                            <div class="col-12 col-lg-3">
                                                <ul class="dev3-rkv9dgqv1b40">
                                                        <div class="dev3-3b9hjbbf11e00">
                                                            <a href="{{ url("/category/$category->id/$sub->id") }}">

                                                            <i class="bi bi-arrow-right"></i>
                                                            {{ $sub->subcategory_name ?? '' }}
                                                            </a>
                                                        </div>
                                                        <ul class="dev3-12eh4crkm0ls0">
                                                            @php
                                                                $childcategories = DB::table('db_childcategory')
                                                                    ->where('subcategory_id', $sub->id)
                                                                    ->get();
                                                            @endphp

                                                            @foreach ($childcategories as $child)
                                                                <li><a
                                                                        href="{{ url("/products/$category->id/$sub->id/$child->id")}}">{{ $child->childcategory_name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                </ul>
                                            </div>
                                                    @endforeach
                                            {{--  --}}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{-- <div class="dev3-243ztdnf8yww0 dev3-3syxh487kww00">
                        <form action="{{ route('search_product') }}" method="get"> <input required type="text"
                                name="item_name" class="form-control" placeholder="search your product..."
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"> <button
                                class="input-group-text" id="inputGroup-sizing-sm" type="submit"> <i
                                    class="fa fa-search"></i> </button> </form>
                    </div> --}}
                    <ul class="dev3-243ztdnf8yww0">
                        <li class="dev3-3m8wtemb60w00 dev3-9uy6i2cdjmw0">
                            <a href="#"><i class="bi bi-person"></i>My Account <i
                                    class="fa-solid fa-angle-down"></i></a>
                            <ul class="dev3-2rybrszefoe00">
                                @if (Session::get('userId'))
                                    <li><a class="dropdown-item"
                                            href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logOut') }}">Logout</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('register') }}">Registration</a></li>
                                    <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                                @endif
                            </ul>
                        </li>
                        {{-- <li class="dev3-9uy6i2cdjmw0"> <a href="#"><em class="round">7</em><i class="fa-solid fa-heart"></i><span class="hide-text">Wishlist</span></a> </li> --}}
                        <li class="dev3-1se0vseabf5s0 dev3-9uy6i2cdjmw0">
                            {{-- <a href="{{ route('cart.page') }}"><i class="bi bi-basket"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ \Gloudemans\Shoppingcart\Facades\Cart::content()->count() }}
                                    </span>
                                </a> --}}
                            <a href="{{ route('cart.page') }}">
                                <em
                                    class="round">{{ \Gloudemans\Shoppingcart\Facades\Cart::content()->count() }}</em>
                                <i class="fa-solid fa-cart-shopping"></i>

                            </a>

                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>

        @yield('content')

    </main>
    <!-- F O O T E R -->
    <footer class="footer-32892 pb-0 mt-5">
        <div class="site-section">
            <div class="container">
                <div class="row">
                    <div class="col-md pr-md-5 mb-4 mb-md-0">
                        <h3>About Us</h3>
                        <p class="mb-4">Bilashita specializes in providing premium-grade lubricants, motor parts, and
                            related chemicals, catering to diverse industries. With a commitment to quality and
                            reliability, we ensure optimal performance and longevity for motors of all types. Trust us
                        </p>
                    </div>
                    <div class="col-md mb-4 mb-md-0">
                        <h3>Regular Product</h3>
                        <div class="row gallery">
                            <div class="product-card">
                                <img src="assets/images/product-1.png" alt="categories-1" width="100px">
                                <div class="card-body">
                                    <h5 class="card-title">Airphone 6</h5>
                                    <p>
                                        <span class="current-price">3000.00</span>
                                        <span class="regular-price"><strike>3000.00</strike></span>
                                    </p>
                                    <p class="price">Price</p>
                                </div>
                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Buy Now
                                </button>
                            </div>
                            <div class="product-card">
                                <img src="assets/images/product-1.png" alt="categories-1" width="100px">
                                <div class="card-body">
                                    <h5 class="card-title">Airphone 6</h5>
                                    <p>
                                        <span class="current-price">3000.00</span>
                                        <span class="regular-price"><strike>3000.00</strike></span>
                                    </p>
                                    <p class="price">Price</p>
                                </div>
                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h3>Recent Order</h3>
                        <div class="row gallery">
                            <div class="product-card">
                                <img src="assets/images/product-1.png" alt="categories-1" width="100px">
                                <div class="card-body">
                                    <h5 class="card-title">Airphone 6</h5>
                                    <p>
                                        <span class="current-price">3000.00</span>
                                        <span class="regular-price"><strike>3000.00</strike></span>
                                    </p>
                                    <p class="price">Price</p>
                                </div>
                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Buy Now
                                </button>
                            </div>
                            <div class="product-card">
                                <img src="assets/images/product-1.png" alt="categories-1" width="100px">
                                <div class="card-body">
                                    <h5 class="card-title">Airphone 6</h5>
                                    <p>
                                        <span class="current-price">3000.00</span>
                                        <span class="regular-price"><strike>3000.00</strike></span>
                                    </p>
                                    <p class="price">Price</p>
                                </div>
                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="py-5 footer-menu-wrap d-md-flex align-items-center justify-content-between">
                            <ul class="list-unstyled footer-menu mr-auto">
                                <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-square-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                            </ul>
                            <div class="site-logo-wrap ml-auto">
                                <a href="muktodharaltd.com" class="site-logo" target="_blank">
                                    <img src="https://muktodharaltd.com/wp-content/uploads/2022/10/updatelogo.png"
                                        alt="Image" class="img-fluid" width="150px">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('lib/vendor/jquery/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
</body>
<!-- Mirrored from dev3design.com/demos/dev3-menu/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Sep 2024 06:27:18 GMT -->

</html>
