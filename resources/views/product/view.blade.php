@extends('layouts.admin')

@push('script')
@endpush

@push('style')
@endpush

@section('content')
<section class="content p-4">
	<!-- Default box -->
	<div class="card card-solid">
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-sm-6">
					<!-- Delete logic -->
					@if (isset($product->deleted_at))
					<h3 class="alert alert-danger">Produkt nije aktivan!</h3>
					@else
					@can('product.delete')
					<form id="delete-product-form" action="{{ route('product::delete', [ $product->id ]) }}"
						method="post">
						@csrf
						<button type="submit" class="cs-btn-primary"> <i class="fas fa-trash"></i> Izbriši
							proizvod</button>
					</form>
					@endcan
					@endif
					<h3 class="d-inline-block d-sm-none">{{ $product->name }}</h3>
					<div class="col-12">
						@php
						$image_url = isset($product) ? $product->getImageURL() : Product::defaultImageURL();
						@endphp
						<img id="product-image" src="{{ $image_url }}" class="product-image" alt="product-image">
					</div>
					<div class="col-12 product-image-thumbs">
						<div class="product-image-thumb active"><img src="{{ $image_url }}" alt="Product Image"></div>
					</div>
				</div>
				<div class="col-12 col-sm-6">
					<h2 class="my-3">{{ $product->name }}</h2>
					<h4 class="my-1 badge bg-info text-lg"><strong>Ident-No: </strong>{{ $product->barcode }}</h4>
					<hr>
					<p>{{ $product->description }}</p>
					<hr>

					@role ('Administrator')
					<h4 class="my-2 text-md"><strong>Cena: </strong><span
							class="badge bg-purple  text-md">{{ $product->price }}
							{{ $product->currency->symbol }}</span></h4>
					<div class="bg-gray py-2 px-3 mt-4">
						<h2 class="mb-0">
							Stanje: <span>{{ $product->quantity }}</span> <span>{{ $product->uom ?? '' }}</span> 
						</h2>
					</div>
					@endrole

					@if (! isset($product->deleted_at))
					<div class="mt-4">
						@foreach ($actions as $action => $data)
						@can ($data->permission)
						<span class="cs-btn-primary {{ $data->class }}" onclick="onActionClick(event)"
							data-href="{{ route($data->route, [ $product->id ]) }}" data-action="{{ $action }}">
							{{ $data->label }}
							<i class="{{ $data->icon }}"></i>
						</span>
						@endcan
						@endforeach
					</div>
					@endif
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
</section>

@if (! isset($product->deleted_at))
@include('include.product-actions')
@endif

<script>
	function onActionClick(event) {
		let target = (typeof (event.target) !== 'undefined') ? $(event.target) : $(event.srcElement);

		if (target.prop('tagName') === 'I') {
			target = target.parent();
		}

		ProductActions.onActionInvoke(event, target);
	}

	document.addEventListener('DOMContentLoaded', () => {
		/** @if (! isset($product->deleted_at)) */
		ProductActions.initialize();
		/** @endif */

		$('#delete-product-form').on('submit', function (event) {
			event.preventDefault();

			if (confirm('Da li ste sigurni?')) {
				$(this).off('submit');
				$(this).trigger('submit');
			}
		});
	});
</script>
@endsection