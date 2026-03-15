<div class="d-flex">
    @if (isset(App\Models\User::where('id', $review->user_id)->first()->profile))
        <img src="{{ asset(App\Models\User::where('id', $review->user_id)->first()->profile) }}"
            style="width:30px;height:30px">
    @else
        <img src="{{ asset('public/profile-user.png') }}" style="margin-top: 4px;width:30px;height:30px">
    @endif
    <div class="info ps-2">
        <h6 class="m-0" style="font-weight: bold;font-size: 14px;">
            {{ App\Models\User::where('id', $review->user_id)->first()->name }}</h6>
        <div class="review">
            <p style="font-size: 12px;">
                {{ $review->messages }}
            </p>

        </div>
    </div>
</div>
