@php
$title = 'Home';
@endphp
@include('client.clientParts.header')
@include('client.clientParts.nav')
<div class="root">
{{--     {{auth()->user()->role}}--}}
    <div class="rootMain">
        <div class="reponsiveElements">
            <h1>
                Let’s make your life dairy with travelling!
            </h1>
            <a class="planLink" href="">Make plan</a>
            <div class="mobileInput">
                <input type="search"> <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
    </div>

    <div class="rootContent">
        <div class="demoContainer">
            <div class="demo">
                <h1>
                    Let’s make
                    your life dairy
                    with travelling!
                </h1>
            </div>
            <div  class="demo2">
                <img class="demoImage" src="images/demo2Back.svg" alt="">
            </div>
        </div>
        <div class="searchContainer">
            <div class="search">
                <div class="searchBar">
                    <ul class="">
                        <li class="li1 activeList">Places</li>
                        <li class="li2">Property</li>
                        <li class="li3">Guide</li>
                    </ul>
                </div>
                <form class="placeSearchCard searchActive" action="{{route('home.search.searchMain')}}">
                        <div class="searchCard-1">
                            <p>Keyword</p>
                            <input type="text" name="name" placeholder="Search...">
                            <p>Min price</p>
                            <input name="minPrice" min="0" type="number">
                        </div>
                        <div class="searchCard-1">
                            <p>Select Location</p>
                            <select class="MainWebsiteSearch" name="location" id="">
                                <option value="{{null}}">Select,location</option>
                            </select>
                            <p>Max price</p>
                            <input name="maxPrice" min="0"  type="number">
                        </div>
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                </form>
                <form class="propertySearhCard " action="{{ route('home.search.searchProperty') }}">
                    <div class="searchCard-1">
                        <p>Keyword</p>
                        <input type="search" name="name"  placeholder="Search...">
                        <p>Min price</p>
                        <input name="minPrice" min="0" type="number">
                    </div>
                    <div class="searchCard-1">
                        <p>Select Location</p>
                        <select name="location" class="MainWebsiteSearch2" id="">
                            <option value="{{null}}">Select,location</option>
                        </select>
                        <p>Max price</p>
                        <input min="0" name="maxPrice" type="number">
                    </div>
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                <form class="guideSearhCard" action="{{route('home.search.searchGuide')}}">
                    <div class="searchCard-1">
                        <p>Keyword</p>
                        <input name="name" type="text" placeholder="Search...">
                        <p>Min price</p>
                        <input name="minPrice" type="number">
                    </div>
                    <div class="searchCard-1">
                        <p>Select Location</p>
                        <select class="MainWebsiteSearch3" name="location" id="">
                            <option value="">Sylhet,Bangladesh</option>
                        </select>
                        <p>Max price</p>
                        <input name="maxPrice" type="number">
                    </div>
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="main">
    <div class="main-1">
        <div class="attached"></div>
        <h1 class="placesText">
            You might love these places.
        </h1>
        <div class="placesContainer">
            @foreach ($places as $item)
                    <div class="places" style="background-image:url({{asset("/images/imgs/$item->image")}})">
                        <div class="placesDetails">
                            <div class="placeRow">
                                <a href="{{route('home.place',['id'=>$item->id])}}">{{$item->name}}</a>
                                <div class="lovelyPlace">
                                    @guest
                                    <a href="{{route('login')}}">
                                        <img src="{{asset("images/heart.svg")}}" alt="">
                                    </a>
                                    @else
                                    @if($item->selections==null)
                                    <a id="ajaxLink" href="{{route('selection.place',['id'=>$item->id])}}">
                                        <img src="{{asset("images/heart.svg")}}" alt="">
                                    </a>
                                    @elseif($item->selections!=null && $item->selections->user_id==auth()->id())
                                    <a href="{{route('selection.deletePlace',['id'=>$item->id])}}">
                                        <img src="{{asset("images/redHeart.svg")}}" alt="">
                                    </a>
                                    @endif
                                    @endguest
                                </div>
                            </div>
                            <div class="placeDetainlsContent">
                                <div class="placeDetainlsContent-1">
                                    <p>
                                        <img src="{{asset("/images/location.svg")}}" alt=""> <span class="location">{{$item->location}}</span>
                                    </p>
                                    <p>
                                        <img src="{{asset('/images/homeStar.svg')}}" alt=""><span class="value">5.0</span><span
                                            class="rewiews">({{@count($item->comments)}} rewiews)</span>
                                    </p>
                                    <span class="placesPrice">
                                        Price: <span class="placePrice">{{$item->price}}$</span>
                                    </span>
                                </div>
                                <div style="display: flex; align-items: end;">
                                    <a class="placeDetailsLink " href="{{ route('home.place',['id'=>$item->id]) }}">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach


        </div>
        <div class="viewMore">
            <a class="moreViews" href="http://127.0.0.1:8000/home/search/place?name=&minPrice=&location=&maxPrice=">View More</a>
        </div>
    </div>
    <div class="main-2">
        <div class="attached attached-v2">
        </div>
        <h1 class="placesText placesTextv2">
            Best Properties.
        </h1>
        <div class="propertiesContainer">
            @foreach ($properties as $item)
                @php
                    $image = $item->homeImage->image;
                @endphp
            <div class="properties">
               <a href="{{route("home.property",['id'=>$item->id])}}"> <img class="hotelMainImg" src="{{asset("/images/imgs/$image")}}" alt=""></a>
                <div class="propertiesContent">
                    <div class="placeRow">
                        <h2>
                            <a href="{{ route("home.property",['id'=>$item->id]) }}">
                                {{$item->name}}
                            </a>
                        </h2>
                        <div class="lovelyPlace">
                            @guest
                            <a href="{{route('login')}}">
                                <img src="{{asset("images/heart.svg")}}" alt="">
                            </a>
                            @else
                            @if($item->selections==null)
                            <a href="{{route('selection.property',['id'=>$item->id])}}">
                                <img src="{{asset("/images/heart.svg")}}" alt="">
                            </a>
                            @elseif($item->selections!=null && $item->selections->user_id==auth()->id())
                            <a href="{{ route('selection.deleteProperty',['id'=>$item->id]) }}">
                            <img src="{{ asset("/images/redHeart.svg") }}" alt="">
                            </a>
                            @endif
                            @endguest
                        </div>
                    </div>
                    <p style="padding-bottom: 10px; display: flex; align-items: center;">
                        <img src="{{ asset("/images/location.svg") }}" alt=""> <span class="location">{{$item->location}}</span>
                    </p>
                    <span>
                        <span class="propertyPrice">
                            {{$item->price}}$/
                        </span>night
                    </span>
                    <div class="propertyRow">
                        <div class="bed">
                            <img src="{{asset("/images/bed.svg")}}" alt=""> {{$item->bed_count}} bed
                        </div>
                        <div class="bed">
                            <img src="{{asset("/images/bath.svg")}}" alt=""> {{$item->bath_count}} Bath
                        </div>
                        <div class="bed">
                            <img src="{{asset("/images/sqft.svg")}}" alt=""> {{$item->sqft_count}} sqft
                        </div>
                    </div>
                    <div class="hotelText">
                        <div class="addition">
                            <img src="{{asset("/images/homeStar.svg")}}" alt=""><span class="value">5.0</span><span
                                class="rewiews">({{@count($item->comments)}} rewiews)</span>
                        </div>
                        <a class="propertyLink" href="{{route("home.property",['id'=>$item->id])}}">View details</a>
                    </div>
                </div>
            </div>

            @endforeach

        </div>
        <div class="viewMore">
            <a class="moreViews" href="http://127.0.0.1:8000/home/search/property?name=&minPrice=&location=&maxPrice=">View More</a>
        </div>
    </div>
    <div class="main-3">
        <div class="attached attached-v2">
        </div>
        <h1 class="placesText placesTextv2">
            Our best guides.
        </h1>
        <div class="guideContainer">
            <div class="my-swipers">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($guide as $item)
                      <div class="swiper-slide">
                            <div class="silde" style="background-image: url({{asset("/images/userImgs/$item->image")}});">
                                <div class="lovelyPlace lovelyPlace-v2">
                                    @guest
                                    <a href="{{route('login')}}">
                                        <img src="{{asset("images/heart.svg")}}" alt="">
                                    </a>
                                    @else
                                    @if($item->selections==null)
                                    <a class="dilVuranRazil" href="{{route('selection.guide',['id'=>$item->id])}}">
                                        <img src="{{asset('/images/heart.svg')}}" alt="">
                                    </a>
                                    @elseif($item->selections != null && $item->selections->user_id==auth()->id())
                                    <a href="{{route('selection.guideDelete',['id'=>$item->id])}}">
                                        <img src="{{asset('/images/redHeart.svg')}}" alt="">
                                    </a>
                                    @endif
                                    @endguest
                                </div>
                                <div class="guideContent">
                                    <div class="guideName">
                                        <h2>
                                            <a href="{{route("home.guide",['id'=>$item->id])}}" style="color: black;">
                                                {{$item->name}}
                                            </a>
                                        </h2>
                                        <div class="mainLocation">
                                            <img src="{{asset('/images/location.svg')}}" alt=""> <span
                                                class="location">{{$item->location}}</span>
                                        </div>
                                        <span class="guideStar">
                                            <img src="{{asset('/images/homeStar.svg')}}" alt=""> <span class="rengli">5.0</span>
                                            <span class="rewiews">({{@count($item->comments)}}rewiews)</span>
                                        </span>

                                    </div>
                                    <div>
                                        <span>
                                            <span class="propertyPrice">
                                                {{$item->guides->price}}$\
                                            </span>day
                                        </span>
                                        <a href="{{route("home.guide",['id'=>$item->id])}}" class="guideDetails">View details</a>
                                    </div>
                                </div>
                            </div>
                      </div>
                      @endforeach

                    </div>
                    <div class="swiper-pagination"></div>
                  </div>

            </div>
        </div>
    </div>
    <div class="main-3">
        <div class="attached attached-v2">
        </div>
        <h1 class="placesText placesTextv2">
            Our best Packages.
        </h1>
         <div class="guideContainer">
            <div class="my-swipers">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($tours as $item)
                      <div class="swiper-slide RadiusedBorder">
                        <div class="silder">
                            <div class="imageContainerPackage">
                                <a href="{{route('home.tourDetails',['id'=>$item->id])}}">
                                    <img src="{{asset("/images/tourImgs/$item->image")}}" alt="">
                                </a>
                                <div class="packageContent">
                                    <div class="packageName">
                                        <h3>{{$item->name}}.</h3>
                                        <span>
                                            <span class="propertyPrice">
                                                {{$item->price}}$\
                                            </span>person
                                        </span>
                                    </div>
                                    <div class="packageInner">
                                        <div>
                                            <span class="planesvg">
                                                <img src="images/plane.svg" alt=""><span>{{$item->start_location}}</span>
                                            </span>
                                            <p class="tourDay">
                                                {{$item->start_time}}
                                            </p>
                                        </div>
                                        <a class="planDetails" href="{{route('home.tourDetails',['id'=>$item->id])}}">View plan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      @endforeach

                    </div>
                    <div class="swiper-pagination"></div>
                  </div>

                </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
 var swiper = new Swiper('.mySwiper', {
    slidesPerView: 1, // Number of slides per view
    spaceBetween: 10, // Space between slides
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        // Responsive breakpoints
        768: {
            slidesPerView: 3, // Number of slides per view for tablets
            spaceBetween: 20,
        },
        576: {
            slidesPerView: 1, // Number of slides per view for mobile devices
            spaceBetween: 10,
        },
    },
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dilVuranRazil').click(function(event) {
            event.preventDefault();

            var guideId = $(this).data('id');
            var link = $(this).attr('href');

            $.ajax({
                type: 'GET',
                url: link,
                success: function(response) {
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
<script src="{{asset('/client/js/location.js')}}"></script>
<script src="{{asset('/client/js/search.js')}}"></script>
<script src="{{asset('/client/js/responsive.js')}}"></script>
@include('client.clientParts.footer')
