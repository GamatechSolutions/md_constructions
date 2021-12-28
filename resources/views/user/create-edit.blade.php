@extends('layouts.admin')

@push('script')
@endpush

@push('style')
@endpush

@section('content')
<div class="content p-4">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Dodavanje korisnika</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
						<li class="breadcrumb-item active">dodavanje korisnika</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<div class="card cs-card-primary">
		<div class="card-header">
			<!-- Delete logic (moda da bude van forme) -->
			@if ($mode == 'edit' && ($user->id != Auth::user()->id))
			<h3 class="card-title">Promeni informacije o korisniku</h3>
			@role('Administrator')
			<form id="delete-user-form" action="{{ route('admin::delete-user', [ $user->id ]) }}" method="post">
				@csrf
				<button type="submit" class="cs-btn-primary">Izbriši</button>
			</form>
			@endrole
			@else
			<h3 class="card-title">Dodaj novog korisnika</h3>
			@endif
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form action="{{ $route }}" method="post">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Ime</label>
							<input type="text" name="name" class="form-control @error('name') cs-input-error @enderror"
								value="{{ old('name') ?? $user->name ?? '' }}">
							@error('name')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<label>Email</label>
							<input type="text" name="email"
								class="form-control @error('email') cs-input-error @enderror"
								value="{{ old('email') ?? $user->email ?? '' }}">
							@error('email')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Lozinka</label>
							<input type="text" name="password"
								class="form-control @error('password') cs-input-error @enderror">
							@error('password')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Potvrdi lozinku</label>
							<input type="text" name="password_confirmation" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="">Dodeli ulogu korisniku</label>
					@foreach ($roles as $role)
					@php
					$has_role = isset($user) ? $user->hasRole($role->name) : false;
					@endphp
					<div class="form-check">
						<input type="radio" name="role" class="form-check-input" value="{{ $role->id }}" @if ($has_role
							|| $role->id ==
						old('role'))
						checked @endif>
						<label class="form-check-label">{{ $role->name }}</label>
					</div>
					@endforeach
					@error('role')
					<span class="cs-error-message">
						{{ $message }}
					</span>
					@enderror
				</div>
			</div>
			<div class="card-footer">
				@if ($mode == 'create')
				<button type="submit" class="cs-btn-primary">Dodaj</button>
				@else
				<button type="submit" class="cs-btn-primary">Sačuvaj</button>
				@endif
			</div>
		</form>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		$('#delete-user-form').on('submit', function (event) {
			event.preventDefault();

			if (confirm('Da li ste sigurni?')) {
				$(this).off('submit');
				$(this).trigger('submit');
			}
		});
	});
</script>

@endsection