@include('client.clientParts.header')
@include('client.clientParts.nav2')
<div class="main guideMain">
    <div class="mainPackageContainer">
        <div class="packageLeftSide">
            <div class="packageContent-1">
                <h1>{{ $tour->name }} <img src="{{asset('/images/tourPlane.svg')}}" alt=""></h1>
                <span>{{ $tour->start_time }},{{ $tour->end_time }}</span>
                <span><span class="packagePrice">${{$tour->price}}/</span>person</span>
            </div>
            <div class="packageContent-2">
                <h1>See who's going</h1>
                    <div class="tourUsersImages">
                        @foreach ($tourUsers ?? [] as $item)
                        <img src="{{asset('/images/userImgs/'.$item->image)}}" alt="">
                        @endforeach
                        @if(count($tourUsers)>4)
                        <div class="img5">

                        </div>
                        @endif
                    </div>
            </div>
            <div class="TourInformations">
                <div class="TourInformations-1">
                    <h2>
                        Tour Plan
                    </h2>
                    <span>
                        Tour location
                    </span>
                    <span>
                        <b>
                            -{{$tour->start_location}}
                        </b>
                    </span>
                </div>
                <div class="TourInformations-2">
                    <h3>
                        Target places visit ({{count($tour->hotels)}} Places)
                    </h3>
                    <ul>
                        @foreach ($tour->hotels as $item)
                        <li>{{ $item->hotel->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="TourInformations-2">
                    <h3>
                        Meet Plan
                    </h3>
                    <ul>
                        <li>Location: {{ $tour->start_location }}</li>
                        <li>Date: {{ $tour->start_time }}</li>
                    </ul>
                </div>
                <div class="TourInformations-2">
                    <h3>
                        Tour transport
                    </h3>
                    <ul>
                        @foreach ($tour->transports as $item)
                        <li>{{ $item->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="packageContent-4">
                <h2>
                    More about Tour
                </h2>
                <span>
                   {{$tour->about}}
                </span>
            </div>
            <div class="packageContent-2">
                <h2>
                    Hotel\Resort to Stay ( {{ count($tour->hotels) }} Booked)
                </h2>
                @foreach ($tour->hotels as $item)
                <div class="bookedPlacesContainer">
                    <div class="bookedPlaces">
                            <img class="bookedPlaceImgs" src="{{ asset('/images/imgs/' . $item->hotel->homeImage->image) }}" alt="">
                            <div class="bookedPlaceContent">
                                <a href="{{route("home.place", $item->hotel->id)}}">
                                    <p>{{ $item->hotel->name }}</p>
                                </a>
                                <div class="mainLocation mainLocation-2">
                                    <img src="{{ asset('/images/recoloc.svg') }}" alt="">
                                    {{ $item->hotel->location }}
                                </div>
                                <div>
                                    <span class="bookedPlacePrice">
                                        {{ $item->hotel->price }}
                                    </span>
                                    <span>
                                        /Night
                                    </span>
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
            </div>
            <div class="packageContent-2">
                <h2>
                    Tour Guide
                </h2>
                @foreach ($tour->guides as $item)
                <div class="bookedPlacesContainer">
                    <div class="bookedPlaces">
                        <img class="bookedPlacesImg" src="{{asset('/images/userImgs/'.$item->guide->image)}}" alt="">
                        <div class="bookedPlaceContent">
                            <a href="{{route("home.guide",['id'=>$item->guide->id])}}">
                                <p>{{$item->guide->name}}</p>
                            </a>
                            <div class="mainLocation mainLocation-2">
                                <img src="{{asset('/images/recoloc.svg')}}" alt="">
                                {{$item->guide->location}}
                            </div>
                            <div>
                                <span class="bookedPlacePrice">
                                    {{$item->guide->guides->price}}$
                                </span>
                                <span>
                                    /Night
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="packageRightSide">
            <div class="mainTourHostContainer">
                <div class="hostContainer">
                    <div class="tourHostContainer">
                        <img src="{{{asset('/images/userImgs/'. $tour->host->image)}}}" alt="">
                        <div class="tourHostInfoContent">
                            <h3>
                                {{ $tour->host->name }}
                            </h3>
                            <span>
                                Tour host
                            </span>
                        </div>
                    </div>
                    <div class="TourHostLinks">
                        <a class="tourLink1" href="">Make Call</a>
                        <a class="tourLink2"@if($user == null) href="{{ route('home.tourJoin', $tour->id) }}" @else href="" @endif>Join the Tour</a>
                    </div>
                </div>
                <div class="RelatedToursContainer">
                    <div>
                        <h1>More Guide you may like</h1>
                        <div class="recommendedGuidesContainer">
                            @foreach($randGuides as $guide)
                            <div style="margin-top: 20px" class="oneGuideContainer">
                                <div class="recGuideImage">
                                    <img src="{{asset("/images/userImgs/$guide->image")}}"
                                        alt="">
                                </div>
                                <div class="recGuideContent">
                                    <h3>
                                        <a href="{{route('home.guide',['id'=>$guide->id])}}">{{$guide->name}}</a>
                                    </h3>
                                    <div class="mainLocation">
                                        <img src="{{asset("/images/recoloc.svg")}}" alt=""> {{$guide->location}}
                                    </div>
                                    <div>
                                        <span class="guidePriceBlue">{{$guide->guides->price}}$/</span>day
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@include('client.clientParts.footer')
