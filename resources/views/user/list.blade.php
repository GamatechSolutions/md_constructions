@extends('layouts.admin')

@push('style')
<link rel="stylesheet" href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/vendor/responsive.bootstrap4.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}" defer></script>
<script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}" defer></script>
<script src="{{ asset('js/vendor/dataTables.responsive.min.js') }}" defer></script>
<script src="{{ asset('js/vendor/responsive.bootstrap4.min.js') }}" defer></script>
@endpush

@section('content')
<section class="content p-4">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Lista korisnika aplikacije</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
						<li class="breadcrumb-item active">lista korisnika</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card cs-card-primary">
					<div class="card-header ">
						<h3 class="card-title"> <i class="fas fa-users"></i> Lista korisnika</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="product-table" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Ime</th>
									<th>Email</th>
									<th>Uloga</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($users as $user)
								<tr>
									<td>
										<a href="{{ route('admin::edit-user', [ $user->id ]) }}">
											{{ $user->name }}
										</a>
									</td>
									<td>{{ $user->email }}</td>
									<td>
										{{ implode(', ', $user->getRoleNames()->all()) }}
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
<script>
	document.addEventListener('DOMContentLoaded', () => {
		$('#user-table').DataTable({
			'responsive': true,
			'autoWidth': false,
			'columnDefs': [
				{ 'targets': 0, 'orderable': false, 'searchable': true },
				{ 'targets': 1, 'orderable': true, 'searchable': true },
				{ 'targets': 2, 'orderable': true, 'searchable': false },
				{ 'targets': 3, 'orderable': true, 'searchable': false },
				{ 'targets': 4, 'orderable': false, 'searchable': false },
			],
		});
	});
</script>
@endsection