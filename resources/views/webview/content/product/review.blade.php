@forelse ($reviews as $review)
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                @if (isset(App\Models\User::where('id', $review->user_id)->first()->profile))
                    <img src="{{ asset(App\Models\User::where('id', $review->user_id)->first()->profile) }}"
                        style="width:60px;height:60px">
                @else
                    <img src="{{ asset('public/profile-user.png') }}" style="width:60px;height:60px">
                @endif
                <div class="info ps-2">
                    <h6 class="m-0" style="font-weight: bold;font-size: 18px;">
                        {{ App\Models\User::where('id', $review->user_id)->first()->name }}</h6>
                    <div class="review">
                        <div class="d-flex">
                            <div class="star">
                                @if ($review->rating == 1)
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                @elseif($review->rating == 2)
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                @elseif($review->rating == 3)
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                @elseif($review->rating == 4)
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;"></span>
                                @else
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                    <span class="fas fa-star" style="font-size:16px;" id="checked"></span>
                                @endif
                            </div>
                            <span style="padding-left: 10px;padding-top: 2px;">{{ date('d M, Y') }}</span>
                        </div>
                        <p>
                            {{ $review->messages }}
                        </p>
                        @if (isset($review->file))
                            <img src="{{ asset($review->file) }}" alt="" width="60px">
                        @else
                        @endif

                    </div>
                </div>
            </div>
            <div class="d-flex" style="justify-content: space-around;font-size: 26px;">
                <span><span class="stss"
                        id="likeof{{ $review->id }}">{{ App\Models\Like::where('review_id', $review->id)->get()->count() }}</span><i
                        class="fas fa-thumbs-up" id="likedone{{ $review->id }}"
                        onclick="givelike({{ $review->id }})"></i></span>
                <span><span class="stss"
                        id="shareof{{ $review->id }}">{{ App\Models\Share::where('review_id', $review->id)->get()->count() }}</span><i
                        class="fas fa-heart" id="sharedone{{ $review->id }}"
                        onclick="giveshare({{ $review->id }})"></i></span>
            </div>


        </div>
    </div>

@empty
    <div class="card">
        <div class="card-body">
            No review found !
        </div>
    </div>
@endforelse
