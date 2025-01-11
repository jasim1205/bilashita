@extends('master')

@section('content')
    <style>
        .reguler-price {
            color: tomato;
        }
    </style>
    <!--  ? H E R O   S E C T I O N -->
    <section class="section-hero">
        <div class="container">
            {{-- <div class="row">
            <div class="col-md-3">
                @include('layout.sidebar')
            </div>
            <div class="col-md-9"> --}}
            <div class="row">
                <div class="col-md-7">
                    <img src="{{ asset('./../pos/uploads/slider_image/' . $heroone?->slider_image) }}" alt="slider-1"
                        class="img-fluid rounded my-2" style="height: 300px;">
                    {{-- <img src="{{ asset('assets/images/slider-1.png') }}" alt="slider-1" class="img-fluid"> --}}
                </div>
                <div class="col-md-5 hero-img-group">
                    <div class="row gx-1">
                        <div class="col-12">
                            <img class="hero-img rounded my-2"
                                src="{{ asset('./../pos/uploads/slider_image/' . $herotwo?->slider_image) }}"
                                alt="card-img-1">
                        </div>
                        <div class="col-12">
                            <img class="hero-img rounded my-2"
                                src="{{ asset('./../pos/uploads/slider_image/' . $herotwo?->slider_image) }}"
                                alt="card-img-1">
                        </div>
                        {{-- <div class="col-6">
                                <img class="hero-img rounded my-2" src="{{ asset('./../pos/uploads/slider_image/'.$herotwo?->slider_image) }}" alt="card-img-1" >
                            </div>
                            <div class="col-6">
                                <img class="hero-img rounded my-2" src="{{ asset('./../pos/uploads/slider_image/'.$herotwo?->slider_image) }}" alt="card-img-1" >
                            </div> --}}
                    </div>
                    <!-- <div class="row"> -->
                    {{-- <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herotwo?->slider_image) }}" alt="card-img-1" style="height: 150px;">
                        <img class="hero-img rounded " src="{{ asset('./../pos/uploads/slider_image/'.$herothree?->slider_image) }}" alt="card-img-1" style="height: 150px">
                        <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herofour?->slider_image) }}" alt="card-img-1" style="height: 150px">
                        <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herofive?->slider_image) }}" alt="card-img-1" style="height: 150px"> --}}
                    {{-- <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1"> --}}
                    <!-- </div> -->
                </div>
            </div>
            {{-- </div>
        </div> --}}
        </div>
    </section>
    <!--  ! H E R O   S E C T I O N -->

    <!--  ? C A T E G R I E S   S E C T I O N -->
    <section class="section-categories my-4">
        <div class="container">
            <!-- <div class="row"> -->
            <div class="row g-2 featture_category">
                <?php $category = DB::table('feature_categorys')->take(4)->get(); ?>
                @forelse ($category as $cat)
                    <div class="col-6 col-sm-3">
                        <div class="card justify-content-center align-items-center" style="padding: 8px 0px">
                            <a href="{{ $cat?->link }}">
                                <img src="{{ asset('./../pos/uploads/feature_category/' . $cat?->image) }}" alt="categories-1"
                                    width="40px" height="36px">
                                <span class="cat-title text-black fw-bold">{{ $cat?->title }}</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <p>no category</p>
                @endforelse
                <!-- </div> -->
            </div>

    </section>
    <!--  ! C A T E G R I E S   S E C T I O N -->

    <!--  ? P R O D U C T   A D S   S E C T I O N -->
    <section class="section-product-ads">
        <div class="container">
            <!-- <div class="product-ads-group">
                    </div> -->
            @php
                $gallery = DB::table('home_gallerys')->take('6')->get();
            @endphp

            <div class="product-ads-group mx-auto">
                <div class="row">
                    @foreach ($gallery as $item)
                        <div class="col-md-4">
                            <a href="{{ $item?->link }}" target="_blank">
                                <img class="hero-img rounded"
                                    src="{{ asset('./../pos/uploads/gallery_image/' . $item?->image) }}" width="180px"
                                    height="150px" alt="card-img-1">
                            </a>
                        </div>
                        {{-- <img class="hero-img" src="{{ asset('assets/images/product-ads-2.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-3.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-4.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-5.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-6.png') }}" width="200px" alt="card-img-1"> --}}
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--  ! P R O D U C T   A D S   S E C T I O N -->


    <!--  ? P O P U L A R  P R O D U C T   S E C T I O N -->
    <section class="section-product my-2">
        <div class="container">
            <div class="row">
                <h4 class="title fw-bold">Popular Products</h4>
            </div>
            <div class="product-groups my-3">
                <div class="row justify-content-center">
                    @foreach ($popular_products as $p)
                        <div class="col-12 col-md-6">
                            <div class="card shadow mb-3 p-2">
                                <div class="d-flex justufy-content-between align-items-center">
                                    <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                        <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1"
                                            width="100px">
                                    </a>
                                    <div class="card-body">
                                        <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                            <h5 class="card-title">
                                                {{ implode(' ', array_slice(explode(' ', $p->item_name), 0, 6)) }}{{ str_word_count($p->item_name) > 6 ? '...' : '' }}
                                            </h5>
                                            <p class="p-0 m-0">
                                                <span class="current-price">{{ $p->web_price . ' ' . 'TK' }}</span>
                                            </p>
                                            <p class="p-0 m-0 regular-price"><strike
                                                    class="regular-price">{{ $p->old_price }}</strike></p>
                                            <p class="p-0 m-0 price">Price</p>
                                        </a>
                                    </div>
                                    <form action="{{ route('add-to-cart') }}" method="post" class="d-none d-md-block">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                                        <input type="hidden" id="qtyBox" placeholder="1" value="1"
                                            name="order_qty" />
                                        <div class="card-button">
                                            <button onclick="addToCard('{{ $p->id }}')" type="button"
                                                class="cartsubmit btn">
                                                <i class="fa-solid fa-basket-shopping"></i>
                                                <span>Buy Now</span>
                                            </button>
                                            {{-- <i class="fa-solid fa-basket-shopping"></i>
                                        <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                            {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                            <a href="#"></a>
                                            {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('add-to-cart') }}" method="post" class="d-block d-md-none">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                                    <div class="card-button">
                                        <button onclick="addToCard('{{ $p->id }}')" type="button"
                                            class="cartsubmit btn">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span>Buy Now</span>
                                        </button>
                                        {{-- <i class="fa-solid fa-basket-shopping"></i>
                                    <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                        {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                        <a href="#"></a>
                                        {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--  ! P O P U L A R  P R O D U C T  S E C T I O N -->


    <!--  ? T O P   S A L E S  P R O D U C T   S E C T I O N -->
    <section class="section-product">
        <div class="container">
            <div class="row">
                <h4 class="title">Top Sales Product</h4>
            </div>
            <div class="product-groups my-3">
                <div class="row justify-content-center">
                    @foreach ($top_products as $p)
                        <div class="col-12 col-md-6">
                            <div class="card shadow mb-3 p-2">
                                <div class="d-flex justufy-content-between align-items-center">
                                    <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                        <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1"
                                            width="100px">
                                    </a>
                                    <div class="card-body">
                                        <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                            <h5 class="card-title">
                                                {{ implode(' ', array_slice(explode(' ', $p->item_name), 0, 6)) }}{{ str_word_count($p->item_name) > 6 ? '...' : '' }}
                                            </h5>
                                            <p class="p-0 m-0">
                                                <span class="current-price">{{ $p->web_price . ' ' . 'TK' }}</span>
                                            </p>
                                            <p class="p-0 m-0 regular-price"><strike
                                                    class="regular-price">{{ $p->old_price }}</strike></p>
                                            <p class="p-0 m-0 price">Price</p>
                                        </a>
                                    </div>
                                    <form action="{{ route('add-to-cart') }}" method="post" class="d-none d-md-block">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                                        <input type="hidden" id="qtyBox" placeholder="1" value="1"
                                            name="order_qty" />
                                        <div class="card-button">
                                            <button onclick="addToCard('{{ $p->id }}')" type="button"
                                                class="cartsubmit btn">
                                                <i class="fa-solid fa-basket-shopping"></i>
                                                <span>Buy Now</span>
                                            </button>
                                            {{-- <i class="fa-solid fa-basket-shopping"></i>
                                        <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                            {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                            <a href="#"></a>
                                            {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('add-to-cart') }}" method="post" class="d-block d-md-none">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" id="qtyBox" placeholder="1" value="1"
                                        name="order_qty" />
                                    <div class="card-button">
                                        <button onclick="addToCard('{{ $p->id }}')" type="button"
                                            class="cartsubmit btn">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span>Buy Now</span>
                                        </button>
                                        {{-- <i class="fa-solid fa-basket-shopping"></i>
                                    <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                        {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                        <a href="#"></a>
                                        {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--  ! P O P U L A R  P R O D U C T  S E C T I O N -->


    <!--  ? Offer Products  P R O D U C T   S E C T I O N -->
    <section class="section-product">
        <div class="container">
            <div class="row">
                <h4 class="title">Offer Product</h4>
            </div>
            <div class="product-groups my-3">
                <div class="row justify-content-center">
                    @foreach ($offer_products as $p)
                        <div class="col-12 col-md-6">
                            <div class="card shadow mb-3 p-2">
                                <div class="d-flex justufy-content-between align-items-center">
                                    <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                        <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1"
                                            width="100px">
                                    </a>
                                    <div class="card-body">
                                        <a href="{{ route('product_details.singleProduct', $p->id) }}">
                                            <h5 class="card-title">
                                                {{ implode(' ', array_slice(explode(' ', $p->item_name), 0, 6)) }}{{ str_word_count($p->item_name) > 6 ? '...' : '' }}
                                            </h5>
                                            <p class="p-0 m-0">
                                                <span class="current-price">{{ $p->web_price . ' ' . 'TK' }}</span>
                                            </p>
                                            <p class="p-0 m-0 regular-price"><strike
                                                    class="regular-price">{{ $p->old_price }}</strike></p>
                                            <p class="p-0 m-0 price">Price</p>
                                        </a>
                                    </div>
                                    <form action="{{ route('add-to-cart') }}" method="post" class="d-none d-md-block">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                                        <input type="hidden" id="qtyBox" placeholder="1" value="1"
                                            name="order_qty" />
                                        <div class="card-button">
                                            <button onclick="addToCard('{{ $p->id }}')" type="button"
                                                class="cartsubmit btn">
                                                <i class="fa-solid fa-basket-shopping"></i>
                                                <span>Buy Now</span>
                                            </button>
                                            {{-- <i class="fa-solid fa-basket-shopping"></i>
                                        <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                            {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                            <a href="#"></a>
                                            {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('add-to-cart') }}" method="post" class="d-block d-md-none">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" id="qtyBox" placeholder="1" value="1"
                                        name="order_qty" />
                                    <div class="card-button">
                                        <button onclick="addToCard('{{ $p->id }}')" type="button"
                                            class="cartsubmit btn">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span>Buy Now</span>
                                        </button>
                                        {{-- <i class="fa-solid fa-basket-shopping"></i>
                                    <input class="cartsubmit" type="submit" value="Buy Now" /> --}}
                                        {{--  <a href="{{ route('product_details.singleProduct',$p->id) }}">+ Add to Card</a>  --}}
                                        <a href="#"></a>
                                        {{--  <a href="{{ route('addwishlist',$p->id) }}"><i class="bi bi-heart-fill"></i></a>  --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--  ! P O P U L A R  P R O D U C T  S E C T I O N -->



    <!-- ! E N D -->
@endsection

@push('scripts')
    <script>
        const addToCard = (product_id) => {
            let totalCart = $("#totalCart").text();
            const url = "{{ route('add-to-cart') }}";
            const token = "{{ csrf_token() }}";
            const data = {
                _token: token,
                product_id: product_id,
                order_qty: 1
            }
            $.ajax({
                url,
                type: "POST",
                data,
                success: function(data) {
                    if (data.status == true) {
                        $("#totalCart").text(parseFloat(totalCart) + 1);
                    }
                }
            });
        }
    </script>
@endpush
