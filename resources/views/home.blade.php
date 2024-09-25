@extends('master')

@section('content')


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
                        <img src="{{ asset('assets/images/slider-1.png') }}" alt="slider-1" class="img-fluid">
                    </div>
                    <div class="col-md-5 hero-img-group ">
                        <!-- <div class="row"> -->
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
                        <img class="hero-img" src="{{ asset('assets/images/card-items-1.png') }}" alt="card-img-1">
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
            <div class="product-ads-group">
                <!-- <div class="row"> -->
                <img class="hero-img" src="{{ asset('assets/images/product-ads-1.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-2.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-3.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-4.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-5.png') }}" width="200px" alt="card-img-1">
                <img class="hero-img" src="{{ asset('assets/images/product-ads-6.png') }}" width="200px" alt="card-img-1">
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
                @foreach ($product as $p)
                    <div class="card">
                        <a href="{{ route('product_details.singleProduct',$p->id) }}">
                              <img src="{{ asset('./../pos/' . $p->item_image) }}" alt="categories-1" width="100px">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->item_name }}</h5>
                            <p>
                                <span class="current-price">{{ $p->web_price .' '.'TK' }}</span>
                                <span class="regular-price"><strike>3000.00</strike></span>
                            </p>
                            <p class="price">Price</p>
                        </div>
                        <form class="" action="{{ route('add-to.cart') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" id="qtyBox" placeholder="1" value="1" name="order_qty" />
                            <div class="card-button">
                                <button type="submit" class="cartsubmit btn btn-light" style="width: 100px;">
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


    {{-- <!--  ? T O P   S A L E S  P R O D U C T   S E C T I O N -->
    <section class="section-product">
        <div class="container">
            <div class="row">
                <h4 class="title">Top Sales Product</h4>
            </div>
            <div class="product-groups">
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
            </div>
        </div>
    </section>
    <!--  ! P O P U L A R  P R O D U C T  S E C T I O N -->


    <!--  ? Offer Products  P R O D U C T   S E C T I O N -->
    <section class="section-product">
        <div class="container">
            <div class="row">
                <h4 class="title">Offer Products Product</h4>
            </div>
            <div class="product-groups">
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
                        <i class="fa-regular fa-circle-user"></i>
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!--  ! P O P U L A R  P R O D U C T  S E C T I O N --> --}}



    <!-- ! E N D -->

@endsection
