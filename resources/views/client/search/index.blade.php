@include('client.clientParts.header')
@include('client.clientParts.nav2')
<div class="main guideMain">
    <div class="searchMainContetn">
        <div class="searchPageIssues">
            <h1>
                Search result for “Places”.
            </h1>
            <span>{{@count($place)}} Results Found</span>

        </div>
        <div class="searchConsole">
            <h2>Filter</h2>
            <form class="filterProducts" action="{{route('home.search.searchMain')}}">
                <div>
                    <label for="">Name</label>
                    <input name="name" type="text">
                </div>
                <div>
                    <label for="">Min price</label>
                    <input name="minPrice" min="0" type="text">
                </div>
                <div>
                    <label for="">Max price</label>
                    <input name="maxPrice" min="0" type="text">
                </div>
                <div>
                    <label for="">Location</label>
                    <select class="MainWebsiteSearch" name="location" id="">
                        <option value="{{null}}">Select,location</option>
                    </select>
                </div>
                <button type="submit">Apply Filter</button>
            </form>
        </div>
    </div>
    <div class="placesContainer">
        @if(@count($place) !=0)
        @foreach ($place as $item)
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
                        <div>
                            <a class="placeDetailsLink" href="{{route('home.place',['id'=>$item->id])}}">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @else
        <div class="color-changing-text">
            <span>S</span><span>o</span><span>r</span><span>r</span><span>y</span>
            <span>w</span><span>e</span><span> </span><span>d</span><span>o</span><span>n</span><span>'</span>
            <span>t</span><span> </span><span>h</span><span>a</span><span>v</span><span>e</span>
            <span> </span><span>p</span><span>l</span><span>a</span><span>c</span><span>e</span><span>s</span>
            <span> </span><span>a</span><span>s</span><span> </span><span>y</span><span>o</span><span>u</span>
            <span> </span><span>w</span><span>a</span><span>n</span><span>t</span>
        </div>
        @endif
    </div>
    <div class="pagination-container">
        <ul class="pagination">
            @if ($place->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $place->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif


            @for ($i = 1; $i <= $place->lastPage(); $i++)
            @if ($i == $place->currentPage())
                <li class="active"><span>{{ $i }}</span></li>
            @else
                <li><a href="{{ $place->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

            @if ($place->hasMorePages())
                <li><a href="{{ $place->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </div>
</div>
<script src="{{asset('/client/js/location.js')}}"></script>
<script src="{{asset('/client/js/location.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.25.0/esm/index.js"></script>
@include('client.clientParts.footer')
