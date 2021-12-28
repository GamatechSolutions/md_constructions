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
					<h1>Lista proizvoda</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a class="cs-color-primary" href="{{route('admin::index')}}"> <i
									class="fas fa-home"></i>
								Početna</a></li>
						<li class="breadcrumb-item active">Lista proizvoda</li>
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
						<h3 class="card-title"><i class="fas fa-warehouse"></i> Lista proizvoda</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="product-table" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Ident-No.</th>
									<th>Ime</th>
									<th>Cena</th>
									<th>Stanje</th>
									<th>Akcije</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($products as $product)
								<tr>
									<td>
										@can ('product.view')
										<a
											href="{{ route('product::view', [ $product->id ]) }}">{{ $product->barcode }}</a>
										@else
										{{ $product->barcode }}
										@endcan
										{{ $product->previous_barcode }}
									</td>
									<td>{{ $product->name }}</td>
									<td>{{ $product->price }}&nbsp;{{ $product->currency->symbol }}</td>
									<td>{{ $product->quantity }} {{ $product->uom ?? '' }}</td>
									<td class="project-actions text-right">
										@foreach ($actions as $action => $data)
										@can ($data->permission)
										<span class="cs-btn-primary {{ $data->class }}" onclick="onActionClick(event)"
											data-href="{{ route($data->route, [ $product->id ]) }}"
											data-action="{{ $action }}">
											{{ $data->label }}
											<i class="{{ $data->icon }}"></i>
										</span>
										@endcan
										@endforeach
										@can ('product.edit')
										<a href="{{ route('product::edit', [ $product->id ]) }}"
											class="btn btn-primary">Izmeni <i class="far fa-edit"></i></a>
										@endcan

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
@include('include.product-actions')

<script>
	function onActionClick(event) {
		let target = (typeof (event.target) !== 'undefined') ? $(event.target) : $(event.srcElement);

		if (target.prop('tagName') === 'I') {
			target = target.parent();
		}

		ProductActions.onActionInvoke(event, target);
	}

	document.addEventListener('DOMContentLoaded', () => {
		ProductActions.initialize();
		$('#product-table').DataTable({
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