@extends('master')

@section('content')

<style>
    .reguler-price{
        color: tomato;
    }
</style>
    <!--  ? H E R O   S E C T I O N -->
    <section class="section-hero mt-5">
    <div class="container">
        {{-- <div class="row">
            <div class="col-md-3">
                @include('layout.sidebar')
            </div>
            <div class="col-md-9"> --}}
                <div class="row">
                    <div class="col-md-7 mt-4">
                        <img src="{{ asset('./../pos/uploads/slider_image/'.$heroone?->slider_image) }}" alt="slider-1" class="img-fluid rounded" style="height: 300px">
                        {{-- <img src="{{ asset('assets/images/slider-1.png') }}" alt="slider-1" class="img-fluid"> --}}
                    </div>
                    <div class="col-md-5 hero-img-group mt-4">
                        <!-- <div class="row"> -->
                        <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herotwo?->slider_image) }}" alt="card-img-1" style="height: 150px;">
                        <img class="hero-img rounded " src="{{ asset('./../pos/uploads/slider_image/'.$herothree?->slider_image) }}" alt="card-img-1" style="height: 150px">
                        <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herofour?->slider_image) }}" alt="card-img-1" style="height: 150px">
                        <img class="hero-img rounded" src="{{ asset('./../pos/uploads/slider_image/'.$herofive?->slider_image) }}" alt="card-img-1" style="height: 150px">
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
    <section class="section-categories">
        <div class="container">
            <!-- <div class="row"> -->
            <div class="category-group">
                <?php $category = DB::table('db_category')->where('is_slied', '1')->select('id','category_name','image','is_slied')->get(); ?>
                @forelse ($category as $cat)

                <div class="card">
                    <img src="{{ asset('./../pos/uploads/category/'.$cat->image) }}" alt="categories-1" width="30px">
                    <span class="cat-title">{{ $cat->category_name }}</span>
                </div>
                {{-- <div class="card">
                    <img src="{{ asset('assets/images/category-1.png') }}" alt="categories-2" width="30px">
                    <h5 class="cat-title">Gadget Shop</h5>
                </div>
                <div class="card">
                    <img src="{{ asset('assets/images/category-1.png') }}" alt="categories-3" width="30px">
                    <h5 class="cat-title">Gadget Shop</h5>
                </div>
                <div class="card">
                    <img src="{{ asset('assets/images/category-1.png') }}" alt="categories-4" width="30px">
                    <h5 class="cat-title">Gadget Shop</h5>
                </div>
                <div class="card">

                    <img src="{{ asset('assets/images/category-1.png') }}" alt="categories-4" width="30px">
                    <h5 class="cat-title">Gadget Shop</h5>
                </div>
                <div class="card">
                    <img src="{{ asset('assets/images/category-1.png') }}" alt="categories-4" width="30px">
                    <h5 class="cat-title">Gadget Shop</h5>
                </div> --}}
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
                <!-- <div class="row"> -->
                @foreach ($gallery as $item)
                <a href="{{$item->link}}" target="_blank">
                    <img class="hero-img rounded" src="{{ asset('./../pos/uploads/gallery_image/'.$item->image) }}" width="180px" height="150px" alt="card-img-1">
                </a>
                {{-- <img class="hero-img" src="{{ asset('assets/images/product-ads-2.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-3.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-4.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-5.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-6.png') }}" width="200px" alt="card-img-1"> --}}
                @endforeach
                <!-- </div> -->
            </div>
        </div>
    </section>
    <!--  ! P R O D U C T   A D S   S E C T I O N -->


    <!--  ? P O P U L A R  P R O D U C T   S E C T I O N -->
    <section class="section-product">
        <div class="container">
            <div class="row">
                <h4 class="title">Popular Products</h4>
            </div>
            <div class="product-groups">
                @foreach ($popular_products as $p)
                    <div class="card">
                        <a href="{{ route('product_details.singleProduct',$p->id) }}">
                              <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1" width="100px">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->item_name }}</h5>
                            <p>
                                <span class="current-price">{{ $p->web_price .' '.'TK'}}</span>
                            </p>
                            <p class="regular-price"><strike class="regular-price">3000.00</strike></p>
                            <p class="price">Price</p>
                        </div>
                        <form class="" action="{{ route('add-to-cart') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                            <div class="card-button">
                                <button onclick="addToCard('{{$p->id }}')" type="button" class="cartsubmit btn btn-light" style="width: 100px;">
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
                        {{-- <button type="button" class="btn btn-light">
                        <i class="fa-solid fa-basket-shopping"></i>
                            <span>Buy Now</span>
                        </button> --}}
                    </div>
                    {{-- <div class="card">
                        <img src="{{ asset('assets/images/product-1.png') }}" alt="categories-1" width="100px">
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
                    <div class="card">
                        <img src="{{ asset('assets/images/product-1.png') }}" alt="categories-1" width="100px">
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
                    <div class="card">
                        <img src="{{ asset('assets/images/product-1.png') }}" alt="categories-1" width="100px">
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
                    <div class="card">
                        <img src="{{ asset('assets/images/product-1.png') }}" alt="categories-1" width="100px">
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
                    <div class="card">
                        <img src="{{ asset('assets/images/product-1.png') }}" alt="categories-1" width="100px">
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
                    </div> --}}
                @endforeach
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
            <div class="product-groups">
                 @foreach ($top_products as $p)
                    <div class="card">
                        <a href="{{ route('product_details.singleProduct',$p->id) }}">
                              <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1" width="100px">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->item_name }}</h5>
                            <p>
                                <span class="current-price">{{ $p->web_price .' '.'TK' }}</span>
                            </p>
                            <p class="regular-price"><strike class="regular-price">3000.00</strike></p>
                            <p class="price">Price</p>
                        </div>
                        <form class="" action="{{ route('add-to-cart') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                            <div class="card-button">
                                <button onclick="addToCard('{{$p->id }}')" type="button" class="cartsubmit btn btn-light" style="width: 100px;">
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
                    @endforeach
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
            <div class="product-groups">
                 @foreach ($offer_products as $p)
                    <div class="card">
                        <a href="{{ route('product_details.singleProduct',$p->id) }}">
                              <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1" width="100px">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->item_name }}</h5>
                            <p>
                                <span class="current-price">{{ $p->web_price .' '.'TK' }}</span>
                            </p>
                            <p class="regular-price"><strike class="regular-price">3000.00</strike></p>
                            <p class="price">Price</p>
                        </div>
                        <form class="" action="{{ route('add-to-cart') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                            <div class="card-button">
                                <button onclick="addToCard('{{$p->id }}')" type="button" class="cartsubmit btn btn-light" style="width: 100px;">
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
                    @endforeach
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
        success: function(data){
            if(data.status == true){
                $("#totalCart").text(parseFloat(totalCart) +1);
            }
  }
    });
 }

</script>
@endpush
