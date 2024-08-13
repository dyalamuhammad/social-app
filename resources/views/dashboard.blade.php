@extends('layouts.app')
@section('script')
    <!-- Add this to your blade/view file -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateModal = document.getElementById('optModal');

            updateModal.addEventListener('shown.bs.modal', function(event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-* attributes
                var postId = button.getAttribute('data-id');
                var postCapt = button.getAttribute('data-caption');

                // Update the modal's content.
                var captInput = document.getElementById('captPost');
                var idInput = document.getElementById('idPost');

                idInput.value = postId;
                captInput.value = postCapt;
            });
        });
    </script>

    <script>
        document.querySelectorAll('.comment').forEach(function(element) {
            element.addEventListener('click', function() {
                var idValue = this.getAttribute('data-id');
                var imgValue = this.getAttribute('data-img');
                var imgDetailValue = this.getAttribute('data-img-detail');
                var userValue = this.getAttribute('data-user');
                var captValue = this.getAttribute('data-caption');
                document.getElementById('idInput').value = idValue;
                document.getElementById('img').src = imgValue;
                document.getElementById('img-detail').src = imgDetailValue;
                document.getElementById('img-detail-1').src = imgDetailValue;

                document.getElementById('username').innerHTML = userValue;
                document.getElementById('username-1').innerHTML = userValue;

                document.getElementById('caption').innerHTML = captValue;
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.comment').on('click', function() {
                var postId = $(this).data('id');
                var modal = $('#postModal');
                var commentsContainer = $('#commentsContainer');
                var url = "{{ route('comments.get', ':postId') }}";


                // Clear previous comments
                commentsContainer.html('');

                // Make an Ajax request to get comments for the post
                $.ajax({
                    url: url.replace(':postId', postId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.comments && data.comments.length > 0) {
                            data.comments.forEach(function(comment) {
                                var commentHtml = `
                            <div class="row mb-4">
                                <div class="col-2 col-xxl-1">
                                    <img src="${comment.user_img}" alt="" class="rounded-circle" width="45px" height="45px" style="object-fit: cover">
                                </div>
                                <div class="col-10 col-xxl-11 align-self-center">
                                    <p class="fw-bold my-0">
                                        ${comment.user_name} <span class="ms-0 ms-lg-1 fw-light text-white d-block d-lg-inline">${comment.desc}</span>
                                    </p>
                                </div>
                            </div>
                        `;
                                commentsContainer.append(commentHtml);
                            });
                        } else {
                            commentsContainer.html(
                                '<h5 class="text-center fw-bold">No comments yet.</h5>');
                        }
                    },
                    error: function(xhr, status, error) {
                        commentsContainer.html(
                            '<p>An error occurred while loading comments.</p>');
                    }
                });
            });
        });
    </script>



    <!-- Skrip JavaScript untuk Klik Dua Kali pada Gambar dan Klik pada Tombol Like -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event Listener untuk Tombol Like
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    handleLike(this);
                });
            });

            // Event Listener untuk Klik Dua Kali pada Gambar
            document.querySelectorAll('.like-img').forEach(image => {
                image.addEventListener('dblclick', function() {
                    var postId = this.getAttribute('data-post-id');
                    var likeButton = document.querySelector('.like-btn[data-post-id="' + postId +
                        '"]');
                    if (likeButton.classList.contains('bi-heart-fill')) {
                        // Jika sudah dilike, tidak lakukan apa-apa
                        return;
                    }
                    handleLike(this);
                });
            });


            function handleLike(element) {
                var postId = element.getAttribute('data-post-id');
                var action = element.classList.contains('bi-heart-fill') ? 'unlike' : 'like';
                var url = action === 'like' ? '{{ route('like') }}' : '{{ route('unlike') }}';
                var token = '{{ csrf_token() }}';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            post_id: postId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'liked') {
                            updateLikeButton(postId, 'liked');
                        } else if (data.status === 'unliked') {
                            updateLikeButton(postId, 'unliked');
                        }
                        updateLikeCount(postId);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function updateLikeButton(postId, status) {
                var likeButton = document.querySelector('.like-btn[data-post-id="' + postId + '"]');
                if (status === 'liked') {
                    likeButton.className = 'bi bi-heart-fill text-danger fs-3 like-btn';
                    likeButton.id = 'unlike';
                } else if (status === 'unliked') {
                    likeButton.className = 'bi bi-heart text-light fs-3 like-btn';
                    likeButton.id = 'like';
                }
            }

            function updateLikeCount(postId) {
                var url = '{{ route('likes.count', ':postId') }}'.replace(':postId', postId);
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        var likeCountElement = document.querySelector('.like-count[data-post-id="' + postId +
                            '"]');
                        likeCountElement.textContent = data.likes_count + ' likes';
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
@endsection
@section('content')
    @php
        $isPost = App\Models\Post::where('user_id', $user->id)->first();
    @endphp
    @if ($isPost)
        @foreach ($post as $item)
            <div class="mx-md-auto col-12 col-md-8 col-lg-5 border-bottom mb-3">
                <div class="d-flex p-2 justify-content-between">
                    <div class="d-flex gap-3 align-items-center">
                        @php
                            $userImage = App\Models\User::where('id', $item->user_id)->value('img');
                            $userName = App\Models\User::where('id', $item->user_id)->value('name');
                            $updatedAt = \Carbon\Carbon::parse($item->updated_at);
                            $now = \Carbon\Carbon::now();

                            if ($updatedAt->diffInMinutes($now) < 60) {
                                $time = $updatedAt->diffInMinutes($now) . 'm';
                            } elseif ($updatedAt->diffInHours($now) < 24) {
                                $time = $updatedAt->diffInHours($now) . 'h';
                            } elseif ($updatedAt->diffInDays($now) < 7) {
                                $time = $updatedAt->diffInDays($now) . 'd';
                            } else {
                                $time = $updatedAt->diffInWeeks($now) . 'w';
                            }

                        @endphp
                        @if ($userImage)
                            <img src="{{ asset($userImage) }}" alt="" class="rounded-circle" width="45px"
                                height="45px" style="object-fit: cover">
                        @else
                            <img src="{{ asset('blank-profile.jpg') }}" alt="" class="rounded-circle col-1"
                                style="object-fit: cover">
                        @endif
                        <a class="p-0 m-0 align-middle align-self-center nav-link fw-bold text-white"
                            href="{{ route('profile', ['id' => $item->user_id]) }}">
                            {{ $userName }}<i class="bi bi-dot text-muted"></i><span
                                class="align-self-center align-middle text-muted fw-light">{{ $time }}</span></a>

                    </div>
                    @if ($user->id == $item->user_id)
                        {{-- <button data-bs-toggle="modal" data-bs-target="#optModal" data-id="{{ $item->id }}"
                    class="btn p-0"><i class="bi bi-three-dots"></i></button> --}}
                        <div class="dropdown align-self-center">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu mt-1">
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#optModal"
                                        data-id="{{ $item->id }}" data-caption="{{ $item->caption }}">Edit
                                        Post</button></li>
                                <form action="{{ route('delete-post') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id }}" name="id">

                                    <button type="submit" class="dropdown-item btn text-danger">Delete
                                        Post</button>

                                </form>
                            </ul>
                        </div>
                    @endif
                </div>
                <img src="{{ asset($item->img) }}" alt="" class="w-100 mb-2 like-img"
                    data-post-id="{{ $item->id }}">
                <div class="d-flex justify-content-between px-2">
                    @php
                        $isLike = \App\Models\Like::where('user_id', Auth::id())
                            ->where('post_id', $item->id)
                            ->exists();
                        $likesCount = \App\Models\Like::where('user_id', Auth::id())
                            ->where('post_id', $item->id)
                            ->count();
                    @endphp
                    <i class="fs-3 like-btn {{ $isLike ? 'bi bi-heart-fill text-danger' : 'bi bi-heart text-light' }}"
                        data-post-id="{{ $item->id }}" id="{{ $isLike ? 'unlike' : 'like' }}"></i>
                    <i class="bi bi-chat fs-3 comment pb-1" data-bs-toggle="modal" data-bs-target="#postModal"
                        data-id="{{ $item->id }}" data-img=" {{ '/' . $item->img }}"
                        data-img-detail=" {{ '/' . $userImage }}" data-user="{{ $userName }}"
                        data-caption="{{ $item->caption }}"></i>
                </div>
                <span class="px-2 fw-semibold fs-6 text-white like-count"
                    data-post-id="{{ $item->id }}">{{ $likesCount }}
                    likes</span>
                <p class="px-2"><span class="fw-bold text-white">{{ $userName }}</span> {{ $item->caption }}</p>
            </div>
        @endforeach
    @else
        <div class="d-flex flex-column justify-content-center align-items-center h-100">
            <h3>Follow People or Upload Post</h3>
            <a href="{{ route('profile', ['id' => $user->id]) }}"
                class="link-light link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Go to
                profile <i class="bi bi-arrow-right align-middle"></i></a>
        </div>
    @endif

    <!-- Modal Edit Post Option-->
    <div class="modal fade" id="optModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Post</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('edit-post') }}" method="post">
                        @csrf
                        <input type="hidden" id="idPost" name="id">
                        <div class="form-group mb-3">
                            <label for="">Caption</label>
                            <textarea type="text" id="captPost" class="form-control" rows="5" name="caption"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-light col-12">Edit Post</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>





    <!-- Modal Comment-->
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="max-height: 90vh">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body p-0 row">
                    <div class="col-12 col-lg-6 border-end pe-lg-0 display-post sticky-top d-none d-lg-block">
                        <img src="" alt="" id="img" class="h-100 sticky-top">
                    </div>
                    <div class="col-12 col-lg-6 pt-2 ps-2 ps-lg-0">
                        <div class="h-100">
                            <div
                                class="mb-4 border-bottom  py-2 border-secondary d-none d-lg-flex ps-2 sticky-top bg-dark">
                                <div class="col-2 col-xxl-1">
                                    <img src="" alt="" id="img-detail" class="rounded-circle me-1"
                                        width="45px" height="45px" style="object-fit: cover">
                                </div>
                                <div class="col-10 col-xxl-11 align-self-center">
                                    <p id="username" class="fw-semibold mb-0">
                                    </p>
                                </div>
                            </div>
                            <div class="mb-4 border-bottom pb-2 border-secondary d-block d-lg-none align-self-center">
                                <p class="text-center mb-0">Komentar</p>
                            </div>

                            <div class="ps-2 mb-4 d-none d-lg-flex">
                                <div class="col-2 col-xxl-1">
                                    <img src="" alt="" id="img-detail-1" class="rounded-circle me-1"
                                        width="45px" height="45px" style="object-fit: cover">
                                </div>
                                <div class="col-10 col-xxl-11 align-self-center">
                                    <p>
                                        <span id="username-1" class="fw-semibold d-block d-lg-inline"></span>
                                        <span id="caption" class="text-white"></span>
                                    </p>
                                </div>
                            </div>
                            <div id="commentsContainer" class="px-2">
                                <!-- Comments will be loaded here -->
                            </div>
                        </div>
                        <div class="row sticky-bottom mt-5 pb-0 mb-0">
                            <form action="{{ route('add-comment') }}" method="post" class="col-12 pe-0">
                                @csrf
                                <input type="hidden" name="post_id" id="idInput">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control rounded-0 border-bottom-0 border-start-0 py-3 py-lg-2"
                                        placeholder="Add Comment.." name="desc">
                                    <div class="d-none d-lg-block">
                                        <button class="btn btn-light rounded-0 py-2" type="submit">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <input type="hidden" name="" id="idInput">
                </div>

            </div>
        </div>
    </div>
@endsection
