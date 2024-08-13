@extends('layouts.app')
@section('script')
    <script>
        function doDelete(id) {
            if (confirm('Yakin Hapus follower?')) {
                // Menggunakan fungsi route() untuk mendapatkan URL dari nama rute Laravel
                var url = "{{ route('softDeleteFollow', ':id') }}";
                // Mengganti placeholder :id dengan nilai id yang diteruskan
                url = url.replace(':id', id);
                // Melakukan redirect ke URL yang sudah dibuat
                location.href = url;
            }
        }
    </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-accept');
            const waiting = document.getElementById('waiting');
            const confirming = document.getElementById('confirming');
            const status = document.getElementById('status-follow');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const followId = this.getAttribute('data-id');

                    fetch('{{ route('acceptFollower') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: followId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Status berhasil diubah.');
                                waiting.className = 'd-none';
                                confirming.className = 'd-block';
                                status.textContent = 'started following you.';

                            } else {
                                console.error('Gagal mengubah status.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
@section('content')
    @php
        $isFollows = App\Models\Follow::where('following_id', $user->id)->first();
    @endphp
    @if ($isFollows)
        <div class="px-lg-5 px-1 mt-5">
            @foreach ($follows as $item)
                <div class="d-flex justify-content-between align-items-center rounded-3 gap-3 border p-3 mb-3"
                    style="cursor: pointer;">
                    @php
                        $follower = App\Models\User::where('id', $item->follower_id)->first();
                        $followerImage = App\Models\User::where('id', $item->follower_id)->value('img');
                    @endphp
                    <div class="d-flex align-item-center gap-3">
                        @if ($followerImage)
                            <img src="{{ asset($followerImage) }}" alt="" class="rounded-circle" width="50px"
                                height="50px" style="object-fit: cover">
                        @else
                            <img src="{{ asset('blank-profile.jpg') }}" alt="" class="rounded-circle" width="50px"
                                height="50px" style="object-fit: cover">
                        @endif
                        <p class="align-self-center pb-0 mb-0">
                            <a href="{{ route('profile', ['id' => $follower->id]) }}"
                                class="nav-link text-white fw-semibold d-inline">{{ $follower->name }}</a>
                            @if ($item->status == 1)
                                <span class="ms-2">started following you.</span>
                            @else
                                <span class="ms-2" id="status-follow">requested
                                    to follow you.</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-lg-3 col-xl-2 text-end">
                        @php
                            $isFollowing = \App\Models\Follow::where('follower_id', Auth::id())
                                ->where('following_id', $follower->id)
                                ->exists();
                        @endphp
                        @if ($item->status == 1)
                            <button class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-btn"
                                data-following-id="{{ $follower->id }}">
                                {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                            </button>
                        @else
                            <div class="d-none" id="confirming">
                                <button class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-btn"
                                    data-following-id="{{ $follower->id }}">
                                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                                </button>
                            </div>
                            <div id="waiting">
                                <button class="btn btn-primary btn-accept" data-id="{{ $item->id }}">
                                    Confirm
                                </button>
                                <button class="btn btn-secondary btn-delete" onclick="doDelete({{ $item->id }})">
                                    Delete
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex justify-content-center h-100 align-items-center">
            <p>No Notification Yet.</p>
        </div>
    @endif
@endsection
