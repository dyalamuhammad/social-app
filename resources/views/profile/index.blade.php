@extends('layouts.app')
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.querySelectorAll('.myDiv').forEach(function(element) {
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
                document.querySelector('.like-btn').setAttribute('data-post-id', idValue);
                document.querySelector('.like-count').setAttribute('data-post-id', idValue);

                var likeBtn = document.querySelector('.like-btn');
                var postId = likeBtn.getAttribute('data-post-id');

                fetch(`/check-like-status/${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.isLike) {
                            likeBtn.classList.add('bi-heart-fill', 'text-danger');
                            likeBtn.classList.remove('bi-heart', 'text-light');
                            likeBtn.setAttribute('id', 'unlike');
                        } else {
                            likeBtn.classList.add('bi-heart', 'text-light');
                            likeBtn.classList.remove('bi-heart-fill', 'text-danger');
                            likeBtn.setAttribute('id', 'like');
                        }

                    });
                updateLikeCount(postId);
            });

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateModal = document.getElementById('updateModal');

            updateModal.addEventListener('shown.bs.modal', function(event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-* attributes
                var userId = button.getAttribute('data-id');
                var userName = button.getAttribute('data-name');
                var bio = button.getAttribute('data-bio');
                console.log(bio)

                // Update the modal's content.
                var idInput = document.getElementById('idInputProfile');
                var nameInput = document.getElementById('nameInput');
                var bioInput = document.getElementById('bioInput');

                idInput.value = userId;
                nameInput.value = userName;
                bioInput.value = bio;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.myDiv').on('click', function() {
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
                                    <p class="fw-bold my-0 text-white">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event Listener untuk Tombol Like
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    handleLike(this);
                });
            });

            // Event Listener untuk Klik Dua Kali pada Gambar



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
                    likeButton.className = 'bi bi-heart-fill text-danger fs-4 like-btn ps-2';
                    likeButton.id = 'unlike';
                } else if (status === 'unliked') {
                    likeButton.className = 'bi bi-heart text-light fs-4 like-btn ps-2';
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

    <script>
        function validate() {
            const areatextarea = document.querySelector("#bioInput");
            const areatext = document.querySelector("#bioInput").value.length;
            const textcount = document.querySelector("#textcount");
            const wordcount = document.querySelector("#words_count");
            textcount.innerHTML = areatext;


            if (areatext >= 150) {
                textcount.classList.add("text-danger");
                areatextarea.classList.add("textarea_danger");
            } else {
                textcount.classList.remove("text-danger");
                areatextarea.classList.remove("textarea_danger");
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
                                button.className =
                                    'btn btn-secondary follow-btn py-1 px-3 fw-semibold';
                            } else if (data.status === 'unfollowed') {
                                button.textContent = 'Follow';
                                button.className =
                                    'btn btn-primary follow-btn py-1 px-3 fw-semibold';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center py-3 text-center">
        <div class="col-12 col-xl-2 col-md-4 justify-content-between d-flex px-2">

            @if ($users->img)
                <img src="{{ asset($users->img) }}" alt="" class="rounded-circle photo-profile"
                    style="object-fit: cover">
            @else
                <img src="{{ asset('blank-profile.jpg') }}" alt="" class="rounded-circle photo-profile"
                    style="object-fit: cover">
            @endif
            <div class="d-flex justify-content-between d-md-none align-items-center col-8">
                <p class="fs-5 fw-bold">{{ $post->count() }} <span class="d-block fs-6 fw-normal">Posts</span></p>
                <p class="fs-5 fw-bold">{{ $follower->count() }} <span class="d-block fs-6 fw-normal">Followers</span>
                </p>
                <p class="fs-5 fw-bold">{{ $following->count() }} <span class="d-block fs-6 fw-normal">Following</span>
                </p>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 col-8 d-none d-md-block mb-3">
            <div class="d-flex flex-column flex-md-row gap-2 mb-3">
                <h5 class="align-self-lg-center mb-3 mb-lg-0">{{ $users->name }}</h5>
                @if ($user->id == $users->id)
                    <div class="d-flex gap-2">
                        <button class="btn btn-secondary py-1 px-3 fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#updateModal" data-id="{{ $users->id }}" data-name="{{ $users->name }}"
                            data-bio="{{ $users->bio }}">Edit Profile</button>
                        <button class="btn btn-primary py-1 px-3 fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Upload
                            Post</button>
                    </div>
                @else
                    @php
                        $isFollowing = \App\Models\Follow::where('follower_id', Auth::id())
                            ->where('following_id', $users->id)
                            ->exists();
                    @endphp
                    <button
                        class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-btn py-1 px-3 fw-semibold"
                        data-following-id="{{ $users->id }}">
                        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                    </button>
                @endif
            </div>
            <div class="d-flex gap-0 gap-md-3 mb-2">
                <p class="fs-5"><span class="fw-semibold">{{ $post->count() }}</span> Posts</p>
                <p class="fs-5"><span class="fw-semibold">{{ $follower->count() }}</span> Followers</p>
                <p class="fs-5"><span class="fw-semibold">{{ $following->count() }}</span> Following</p>
            </div>
            <p class="text-start">{{ $users->bio }}
            </p>
        </div>

        <div class="col-12 mt-3 d-md-none">
            <p class="text-start px-2">{{ $users->bio }}</p>
            @if ($user->id == $users->id)
                <div class="d-flex justify-content-between px-2 gap-1">
                    <button class="btn btn-secondary py-1 px-3 fw-semibold col-6" data-bs-toggle="modal"
                        data-bs-target="#updateModal" data-id="{{ $users->id }}" data-name="{{ $users->name }}"
                        data-bio="{{ $users->bio }}">Edit
                        Profile</button>
                    <button class="btn btn-primary py-1 px-3 fw-semibold col-6" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Upload
                        Post</button>
                </div>
            @else
                @php
                    $isFollowing = \App\Models\Follow::where('follower_id', Auth::id())
                        ->where('following_id', $users->id)
                        ->exists();
                @endphp
                <div class="px-2">
                    <button
                        class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-btn py-1 px-3 fw-semibold col-12"
                        data-following-id="{{ $users->id }}">
                        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-3 row gap-2 column-gap-0 col-md-11 col-xl-8 mx-auto border-top pt-1">
        @foreach ($post as $item)
            @php
                $userImage = App\Models\User::where('id', $item->user_id)->value('img');
                $userName = App\Models\User::where('id', $item->user_id)->value('name');
            @endphp
            <div class="col-4 myDiv px-1" data-bs-toggle="modal" data-bs-target="#postModal" data-id="{{ $item->id }}"
                data-img=" {{ '/' . $item->img }}" data-img-detail=" {{ '/' . $userImage }}"
                data-user="{{ $userName }}" data-caption="{{ $item->caption }}">
                <div class="ratio ratio-1x1">
                    <img src="{{ asset($item->img) }}" class="img-fluid" style="object-fit: cover" alt="...">
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Upload Post-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">

                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Post</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('addPost') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group mb-3">
                            <label class="mb-2" for="image">Image</label>
                            <input type="file" name="img" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-2" for="captio">Caption</label>
                            <textarea type="text" name="caption" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary col-12 fw-semibold">Upload</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Display Post-->
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="max-height: 90vh">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body p-0 row">
                    <div class="col-12 col-lg-6 border-end pe-lg-0 display-post sticky-top d-none d-lg-block">
                        <img src="" alt="" id="img" class="h-lg-100 sticky-top">
                    </div>
                    <div class="col-12 col-lg-6 pt-2 ps-2 ps-lg-0">
                        <div class="h-100">

                            <div
                                class="mb-4 border-bottom py-2 mt-0 border-secondary d-none d-lg-flex ps-2 sticky-top bg-dark">
                                <div class="col-2 col-xxl-1">
                                    <img src="" alt="" id="img-detail" class="rounded-circle me-1"
                                        width="45px" height="45px" style="object-fit: cover">
                                </div>
                                <div class="col-10 col-xxl-11 align-self-center mb-0">
                                    <p id="username" class="fw-semibold mb-0 text-white">
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
                                    <p class="text-white mb-0">
                                        <span id="username-1" class="fw-semibold d-block d-lg-inline"></span>
                                        <span id="caption"></span>
                                    </p>
                                </div>
                            </div>
                            <div id="commentsContainer" class="px-2">
                                <!-- Comments will be loaded here -->
                            </div>
                        </div>
                        <div class="sticky-bottom">
                            <div class="d-flex gap-3 border-top pe-0 bg-dark py-2">

                                <i class="fs-4 like-btn ps-2" data-post-id="" id=""></i>
                                <p class="px-2 fw-semibold fs-6 text-white like-count" data-post-id="">0 likes</p>
                            </div>
                            <div class="row pb-0 mb-0">
                                <form action="{{ route('add-comment') }}" method="post" class="col-12 pe-0">
                                    @csrf
                                    <input type="hidden" name="post_id" id="idInput">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="input-group border-top">
                                        <input type="text" class="form-control border-0 py-3 py-lg-2 rounded-0"
                                            placeholder="Add Comment.." name="desc">
                                        <div class="d-none d-lg-block">
                                            <button class="btn rounded-0 py-2" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="" id="idInput">
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Update Profile-->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">

                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Profile</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('update-profile') }}" method="post" enctype="multipart/form-data"v>
                        @csrf
                        <input type="hidden" name="id" id="idInputProfile" class="form-control">
                        <div class="form-group mb-2">
                            <label class="mb-2" for="">Username</label>
                            <input type="text" name="name" id="nameInput" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-2" for="Image">Image</label>
                            <input type="file" name="img" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <label class="" for="Image">Bio</label>
                                <span id="words_count"><span id="textcount"></span> / 150</span>
                            </div>
                            <textarea type="text" name="bio" class="form-control" maxlength="150" id="bioInput" rows="3"
                                onkeyup="validate()"></textarea>
                        </div>
                        <button type="submit" class="btn btn-light col-12 mx-auto fw-semibold">Save</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
