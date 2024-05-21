@extends('layouts.app-v2')

@section('content-v2')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
					@if(session('success'))
						<div>{{ session('success') }}</div>
					@endif

					@if(session('error'))
						<div>{{ session('error') }}</div>
					@endif
                    <form method="POST" action="{{ route('password.update') }}">
						@csrf

						<div>
							<label for="current_password">Current Password</label>
							<input type="password" id="current_password" name="current_password" required class="form-control">
						</div>

						<div>
							<label for="new_password">New Password</label>
							<input type="password" id="new_password" name="new_password" required class="form-control">
						</div>

						<div>
							<label for="new_password_confirmation">Confirm New Password</label>
							<input type="password" id="new_password_confirmation" name="new_password_confirmation" required class="form-control">
						</div>

						<div class="row p-2">
                                <div class="col">
								<button type="submit" class="btn btn-primary">Change Password</button>
							</div>
						</div>
					</form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
