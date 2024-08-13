@extends('layouts.auth')
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resetPasswordForm = document.querySelector('form[action="{{ route('doReset') }}"]');
            const resetForm = document.getElementById('resetForm');
            const editPasswordForm = document.querySelector('form[action="{{ route('newPassword') }}"]');
            const emailInput = resetPasswordForm.querySelector('input[name="email"]');
            const editPasswordEmailInput = editPasswordForm.querySelector('input[name="email"]');

            resetPasswordForm.addEventListener('submit', function(event) {
                event.preventDefault();

                fetch('{{ route('resetPassword') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: emailInput.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            editPasswordEmailInput.value = data.email;
                            resetForm.classList.add('d-none');
                            editPasswordForm.parentElement.classList.remove('d-none');
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
@section('content')
    <h3 class="text-white fw-normal text-center pb-2">Reset Password</h3>
    <p class="text-muted text-center">Enter the email that associates with your account.</p>
    <form action="{{ route('resetPassword') }}" method="post" id="resetForm">
        @csrf
        <div class="form-floating mb-3">
            <input type="email" class="form-control border-0 border-bottom rounded-0" id="email" name="email"
                placeholder="Email">
            <label for="username">Email</label>
        </div>
        <button class="btn btn-light col-12 mt-3 fw-bold" type="submit">Submit</button>
    </form>
    <div class="d-none">
        <form action="{{ route('newPassword') }}" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="hidden" class="form-control border-0 border-bottom rounded-0" id="email" name="email"
                    placeholder="Email">
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control border-0 border-bottom rounded-0" id="password" name="password"
                    placeholder="Password">
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control border-0 border-bottom rounded-0" id="password_confirmation"
                    name="password_confirmation" placeholder="Confirm Password">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <button class="btn btn-light col-12 mt-3 fw-bold" type="submit">Reset Password</button>
        </form>
    </div>
@endsection
