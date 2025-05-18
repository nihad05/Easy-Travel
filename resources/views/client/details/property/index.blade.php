@include('client.clientParts.header')
    <div class="root">
        @include('client.clientParts.nav')
        <div class="detailsSwiperContainer">
            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                <div class="swiper-wrapper">

                    @foreach ($images as $item)
                    <div class="swiper-slide">
                        <img src="{{asset("/images/imgs/$item->image")}}" />
                    </div>
                    @endforeach

                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div thumbsSlider="" class="swiper swiperMyself mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($images as $item)
                    <div class="swiper-slide">
                        <img src="{{asset("/images/imgs/$item->image")}}" />
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="placeDeatilsContainer">
            <div class="placeDetailsContent">
                <div class="placeContentRow-1">
                    <h1>{{ $element->name }}</h1>
                    <div class="propertiesDetailsContainer">
                        <div class="propertiesDetailsContentContainer">
                            <p class="mainLocation">
                                <img src="{{asset("/images/recoloc.svg")}}" alt=""> {{ $element->location }}
                            </p>
                            <div class="threeDivsProperty">
                                <div>
                                    <img src="{{asset('/images/bed.svg')}}" alt="">
                                    <span>
                                        {{ $element->bed_count }} bed
                                    </span>
                                </div>
                                <div>
                                    <img src="{{asset('/images/bath.svg')}}" alt="">
                                    <span>
                                        {{ $element->bath_count }} bath
                                    </span>
                                </div>
                                <div>
                                    <img src="{{ asset('/images/sqft.svg') }}" alt="">
                                    <span>
                                        {{ $element->sqft_count }} sqft
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span>
                                    <span class="propertyDetailsPrice">{{ $element->price }}$</span>/night
                                </span>
                            </div>
                        </div>
                        <div>
                            <h2>More Facilities</h2>
                            <div class="propertiesDetails">
                                <ul>
                                    @php
                                    $supplyIds = $element->supplies->pluck('supply_id')->toArray();
                                    $comments = $element->comments;
                                    @endphp
                                    @foreach ($supplies as $supply)
                                        <li><img src="{{ in_array($supply->id, $supplyIds) ? asset('/images/approve.svg') : asset('/images/x.svg') }}" alt="">
                                            {{ $supply->name }}</li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="placeContentRow-1">
                    <h2>
                        Description
                    </h2>
                    <p class="PlaceDetailsText">
                       {{ $element->description }}
                    </p>
                </div>
                <div class="placeContentRow-1">
                    <h2>View location on map</h1>
                        <iframe class="placeDetailsLocation"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d801.9384012627332!2d49.72626096848304!3d40.47610349540858!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x403085750a9f3d61%3A0x24e5c126412d760!2sBaku%20Engineering%20University!5e1!3m2!1sen!2saz!4v1692470819023!5m2!1sen!2saz"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="placeReviewsContainer">
                    <h2>Reviews</h2>
                    @include('client.clientParts.comments')
                        <div>
                            @guest
                            <a href="{{route('login')}}" class="writeCommentLink">Write Comment</a>
                            @else
                            <button id="commentWirterBtn">Write Comment</button>
                            <div class="commentDiv">
                                <div class="userComment">
                                    <div class="userCommentImage">
                                        <img src="{{asset("/images/userImgs/". $image)}}" alt="">
                                    </div>
                                    <form  action="{{route('home.comments.propertyComment',['id'=>$element->id])}}" method="POST">
                                        @csrf
                                        <input class="commentInput" placeholder="Write comment..." type="text" name="comment">
                                        <button type="submit">Done</button>
                                    </form>
                                </div>
                            </div>
                            @endguest
                        </div>
                    </div>

                </div>
            </div>
            <div class="placeDetailsRigtSide">
                <div class="recommendedPlacesLinksContainer">
                    @if($bookedProperty == null || count($bookedProperty) == 0)
                    <a href="{{route('payment.index',['id'=>$element->id])}}" class="makeTripPlaceDetails">Book now</a>
                    @else
                    <button class="closedTripPlaceDetails">Booked</button>
                    @endif
                    <a href="" class="savePlaceDetails">Save</a>

                </div>
                <div class="RecomendedPLacesContainer">
                    <h2>Recommended Properties</h2>
                    <div class="recommenedPlaces">
                        @foreach ($recoProperty as $item)
                            @php
                            $image = $item->homeImage->image;
                            @endphp
                        <div class="recommenedPlacesRow">
                                <img class="placeRecommendedImage" src="{{asset("images/imgs/$image")}}" alt="">
                            <div>
                                <a style="color: black" href="{{route("home.property",['id'=>$item->id])}}">
                                    <h3>{{$item->name}}</h3>
                                </a>
                                <p>
                                    <img src="{{asset('/images/recoloc.svg')}}" alt=""> {{$item->location}}
                                </p>
                                <span>
                                    {{$item->price}}$
                                </span>/day
                            </div>
                        </div>
                        @endforeach


                    </div>
                </div>
                <div class="RecomendedPLacesContainer  ">
                    <h2>Recommended Guide for this area</h2>
                    <div class="recommenedPlaces">
                        @foreach ($recGuide as $item)
                        <div class="recommenedPlacesRow">
                                <img class="placeRecommendedImage" src="{{asset("images/userImgs/$item->image")}}" alt="">
                            <div>
                                <a style="color: black" href="{{route('home.guide',['id'=>$item->id])}}">
                                    <h3>{{$item->name}}</h3>
                                </a>
                                <p>
                                    <img  src="{{asset('/images/recoloc.svg')}}" alt=""> {{$item->location}}
                                </p>
                                <span>
                                    {{$item->guides->price}}$
                                </span>/day
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            // loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            // autoplay:true
        });
        var swiper2 = new Swiper(".mySwiper2", {
            // loop: true,
            // autoplay:true
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: swiper,
            },
        });
    </script>
<script src="{{asset('/client/js/comment.js')}}"></script>
@include('client.clientParts.footer')
