@include('client.clientParts.header')
<div class="root">
    @include('client.clientParts.nav')
    <div class="detailsSwiperContainer">
        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
            <div class="swiper-wrapper">

                @foreach ($images as $item)
                <div class="swiper-slide">
                    <img src="{{asset("/images/imgs/$item->image")}}"/>
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
                <h1>{{$details->name}}</h1>
                <div class="fourElementRow-1">
                    <div class="fourElementInner">
                        <span>
                            Cost: <span>{{$details->price}}</span> /mo
                        </span>
                        <div class="placeDetailsLane">
                            <div style="width:{{$details->price/10}}%;" class="placeDetailsLane-1">

                            </div>
                        </div>
                    </div>
                    <div class="fourElementInner-2">
                        <span>
                            Safety: <b>{{$safety}}</b>
                        </span>
                        <div class="placeDetailsLane">
                            <div style="width: {{$details->safety}}%;" class="placeDetailsLane-2">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="fourElementRow-1">
                    <div class="fourElementInner">
                        <span>
                            Internet: {{$details->internet}} /mbps
                        </span>
                        <div class="placeDetailsLane">
                            <div style="width: {{$details->internet}}%;" class="placeDetailsLane-3">

                            </div>
                        </div>
                    </div>
                    <div class="fourElementInner-2">
                        <span>
                            Fun: <b>{{$fun}}</b>
                        </span>
                        <div class="placeDetailsLane">
                            <div style="width: {{$details->fun}}%;" class="placeDetailsLane-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="placeContentRow-1">
                <h2>
                    About
                </h2>
                <p class="PlaceDetailsText">
                   {{$details->about}}
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
                    @guest
                    <a href="{{route('login')}}" class="writeCommentLink">Write Comment</a>
                    @else
                    <button id="commentWirterBtn">Write Comment</button>
                    <div class="commentDiv">
                        <div class="userComment">
                            <div class="userCommentImage">
                                <img src="{{asset("/images/userImgs/" . Auth::user()->image)}}" alt="">
                            </div>
                            <form  action="{{route('home.comments.placeComment',['id'=>$details->id])}}" method="POST">
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
        <div class="placeDetailsRigtSide">
            {{-- <div class="recommendedPlacesLinksContainer">
                <a href="" class="makeTripPlaceDetails">Make Trip</a>
                <a href="" class="savePlaceDetails">Save</a>
            </div> --}}
            <div class="RecomendedPLacesContainer">
                <h2>Recommended Places</h2>
                <div class="recommenedPlaces">
                    @foreach($recommendedPlaces as $item)
                        <div class="recommenedPlacesRow">
                         <img class="placeRecommendedImage" src="{{asset("/images/imgs/{$item->homeImage->image}")}}" alt="">
                         <div>
                            <a style="color: black" href="{{ route('home.place', $item->id) }}">
                                <h3>{{$item->name}}</h3>
                            </a>
                             <p>
                                 <img src="{{asset('/images/location.svg')}}" alt=""> {{$item->location}}
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
                            <a style="color: black" href="{{route("home.guide",['id'=>$item->id])}}">
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
<script src="{{asset('/client/js/comment.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper2", {
            loop: true,
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
@include('client.clientParts.footer')
