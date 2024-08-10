@extends('layouts.app')
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.follow-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var button = this;
                    var followingId = button.getAttribute('data-following-id');
                    var action = button.textContent.trim().toLowerCase();

                    var url = action === 'follow' ? '{{ route('follow') }}' :
                        '{{ route('unfollow') }}';
                    var token = '{{ csrf_token() }}';

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                following_id: followingId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'followed') {
                                button.textContent = 'Unfollow';
                                button.className = 'btn btn-secondary follow-btn';
                            } else if (data.status === 'unfollowed') {
                                button.textContent = 'Follow';
                                button.className = 'btn btn-primary follow-btn';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
@section('content')
    <div class="d-flex justify-content-center pt-5">
        <div class="col-10 col-lg-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari Pengguna">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>

    <div class="px-lg-5 px-1 mt-5">
        @foreach ($users as $item)
            <div class="d-flex justify-content-between align-items-center rounded-3 gap-3 border p-3 mb-3"
                onclick="window.location.href='{{ route('profile', ['id' => $item->id]) }}'" style="cursor: pointer;">
                <div class="d-flex align-item-center gap-3">
                    @if ($item->img)
                        <img src="{{ asset($item->img) }}" alt="" class="rounded-circle" width="50px"
                            height="50px" style="object-fit: cover">
                    @else
                        <img src="{{ asset('blank-profile.jpg') }}" alt="" class="rounded-circle" width="50px"
                            height="50px" style="object-fit: cover">
                    @endif
                    <a href="{{ route('profile', ['id' => $item->id]) }}"
                        class="nav-link align-self-center text-white fw-semibold">{{ $item->name }}</a>
                </div>
                <div class="col-lg-2 col-xl-1 text-end">
                    @php
                        $isFollowing = \App\Models\Follow::where('follower_id', Auth::id())
                            ->where('following_id', $item->id)
                            ->exists();
                    @endphp
                    <button class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-btn"
                        data-following-id="{{ $item->id }}">
                        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endsection
