
<link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/public.css') }}">
<header>
    <div class="header-menu">
        <div class="menu-content">
            <div class="mobile-box l-menu">
                <svg width="14" height="12" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="nonzero" fill="#2D317C">
                        <path d="M0 .313h14v1.75H0zM0 5.125h14v1.75H0zM0 9.938h7.438v1.75H0z"></path>
                    </g>
                </svg>
            </div>
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="100px">
            </a>
            <div class="mobile-box search" id="showMobileSearch"></div>
            <div class="mobile-box language">
                <svg class="i" width="16" height="16" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
                    <path class="globe -icon" d="M16.385 14.148a20.872 20.872 0 0 0-3.646-.843c.175-1.03.268-2.107.298-3.211h4.753a8.244 8.244 0 0 1-1.405 4.054zm-4.846 3.403c.395-.956.719-1.988.968-3.076 1.067.158 2.09.391 3.06.698a8.325 8.325 0 0 1-4.028 2.378zm-1.462.232c-.192.014-.382.03-.577.03-.195 0-.385-.016-.577-.03a17.732 17.732 0 0 1-1.217-3.465c.59-.052 1.188-.082 1.794-.082.606 0 1.204.03 1.794.082a17.772 17.772 0 0 1-1.217 3.465zm-6.645-2.61c.97-.307 1.994-.54 3.061-.698.249 1.088.573 2.12.968 3.076a8.327 8.327 0 0 1-4.029-2.378zm-.817-1.025a8.244 8.244 0 0 1-1.405-4.054h4.753c.03 1.104.123 2.182.298 3.21-1.287.184-2.51.466-3.646.844zm0-9.296c1.137.378 2.36.661 3.646.843a22.865 22.865 0 0 0-.298 3.211H1.21a8.244 8.244 0 0 1 1.405-4.054zM7.46 1.449a19.318 19.318 0 0 0-.968 3.076 19.689 19.689 0 0 1-3.06-.698A8.327 8.327 0 0 1 7.46 1.45zm1.462-.232c.192-.013.382-.03.577-.03.195 0 .385.017.577.03a17.786 17.786 0 0 1 1.217 3.465 20.58 20.58 0 0 1-1.794.082c-.606 0-1.204-.03-1.794-.082a17.746 17.746 0 0 1 1.217-3.465zm2.925 8.877a20.823 20.823 0 0 1-.306 3.061 23.511 23.511 0 0 0-2.042-.092c-.694 0-1.373.034-2.041.092a20.663 20.663 0 0 1-.306-3.061h4.695zM7.153 8.906c.031-1.045.126-2.074.306-3.061.668.058 1.348.093 2.041.093.694 0 1.373-.035 2.041-.093.18.987.275 2.016.307 3.061H7.153zm8.415-5.079c-.97.307-1.994.54-3.061.698a19.318 19.318 0 0 0-.968-3.076 8.325 8.325 0 0 1 4.029 2.378zm.817 1.025a8.244 8.244 0 0 1 1.405 4.054h-4.753a22.865 22.865 0 0 0-.298-3.21 20.819 20.819 0 0 0 3.646-.844zM9.5 0a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19z" fill="#2D317C" -rule="evenodd"></path>
                </svg>
                <svg class="close" width="26" height="26" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="nonzero" fill="#fff">
                        <path d="M1.557.143l24.3 24.323-1.414 1.391L.143 1.534z"></path>
                        <path d="M.143 24.332l24.2-24.19 1.514 1.526-24.2 24.19z"></path>
                    </g>
                </svg>
            </div>
            <div class="mobile-box down-language ">
                <a class="zh-cn" href="/cn">中文</a>
                <a class="en" href="/">English</a>
                <a class="en" href="/jp">日本語</a>
                <a class="en" href="/kr">한국어</a>
            </div>
            <div class="menu">
                <div class="mobile-box close">
                     <img src="{{ asset('frontend/assets/images/header/close.png') }}">
                </div>
                <div class="ul-box">
                    <a class="mobile-box mobile-logo2" href="/"></a>
                    <ul>
                        <li>
                            <a href="">ABOUT US</a>
                            <!-- <div class="two">
                                <dl>
                                    <dd><a href="/static/en/about">About RMS</a></dd>
                                    <dd><a href="/static/en/about-history">History</a></dd>
                                    <dd><a href="/static/en/about-clients">Major Clients</a></dd>
                                    <dd><a href="/static/en/about-mission">Mission &amp; Value</a></dd>
                                    <dd><a href="/static/en/csr">CSR</a></dd>
                                </dl>
                            </div> -->
                        </li>
                        <li>
                            <a href="#">PRODUCT & SERVICES</a>
                            <div class="two business">
                                <dl>
                                    <dd><a href="">Stores</a></dd>
                                    <!-- <dd><a href="/static/en/food">Food</a></dd>
                                    <dd><a href="/static/en/spare-parts">Spare Parts</a></dd>
                                    <dd><a href="/static/en/safety-service">Safety Service</a></dd>
                                    <dd><a href="/static/en/technical-service">Technical Service</a></dd>
                                    <dd><a href="#">Logistics</a></dd>
                                    <dd><a href="/static/en/general-agency">General Agency</a></dd>
                                    <dd><a href="/static/en/site-service">Site Service</a></dd>
                                    <dd><a href="/static/en/equipment-project">Equipment &amp; Project</a></dd>
                                    <dd><a href="/static/en/export">Export Service</a></dd>
                                    <dd><a href="/static/en/cruise">Cruise Service</a></dd>
                                    <dd><a href="/static/en/offshore">Offshore Service</a></dd> -->
                                </dl>
                            </div>
                        </li>
                        <!-- <li>
                            <a href="/news/en/news?code=E0001">NEWS &amp; EVENTS</a>
                            <div class="two">
                                <dl>
                                    <dd><a href="/news/en/news?code=E0001">News</a></dd>
                                    <dd><a href="/news/en/news?code=E0002">Events</a></dd>
                                    <dd><a href="/news/en/news?code=E0003">Voice of Customer</a></dd>
                                    <dd><a href="/news/en/news?code=E0010">RMS Tips</a></dd>
                                </dl>
                            </div>
                        </li>
                        <li>
                            <a href="/static/en/china">BRANCHES</a>
                            <div class="two">
                                <dl>
                                    <dd><a href="/static/en/china">China</a></dd>
                                    <dd><a href="/static/en/korea">Korea</a></dd>
                                    <dd><a href="/static/en/singapore">Singapore</a></dd>
                                    <dd><a href="/static/en/rotterdam">Rotterdam</a></dd>
                                    <dd><a href="/static/en/dubai">Dubai</a></dd>
                                    <dd><a href="/static/en/houston">Houston</a></dd>
                                </dl>
                            </div>
                        </li> -->
                        <li>
                            <a href="">CONTACTS</a>
                            <!-- <div class="two">
                                <dl>
                                   <dd><a href="/static/en/where-we-are">Where We Are</a></dd>
                                   <dd><a href="/static/en/feedback">Feedback</a></dd>
                                   <dd><a href="/static/en/collaboration">Collaboration</a></dd>
                                   <dd><a href="/static/en/contact-join">Join Us</a></dd>
                                </dl>
                            </div> -->
                        </li>
                        <!-- <li><a href="/resources/track/index.html">TRACKING</a></li>
                        <li><a href="https://rmsmarine.en.alibaba.com/">RTH</a></li> -->
                        <li class="search">
                            <div id="showSearch"></div>
                        </li>
                        <!--li class="vr">
                            <a href="/resources/track/vr_en.html" style="padding-top:-8px;"><img src="assets/images/header/vr.png"></a>
                        </li-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-content">
        <div class="search-box" style="display: none;">
            <div class="input-box">
                <form action="/news/en/search">
                    <input type="text" name="keyword" placeholder="Write your query here">
                    <button><img src="assets/images/header/search2.png"></button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('frontend/assets/js/jquery-1.9.1.min.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/swiper.min.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/countUp.min.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/main.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/size-page.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/index.js')}}"></script>
</header>
