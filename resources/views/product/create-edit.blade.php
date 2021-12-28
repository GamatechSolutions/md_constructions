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
					<h1>Dodaj proizvod</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a class="cs-color-primary" href="{{route('admin::index')}}"> <i
									class="fas fa-home"></i>
								Početna</a></li>
						<li class="breadcrumb-item active">Nov proizvod</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<div class="card cs-card-primary">
		<div class="card-header">
			@if ($mode == 'create')
			<h3 class="card-title">Dodaj nov proizvod</h3>
			@else
			<h3 class="card-title">Promeni {{ $product->name }}</h3>
			@endif
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form role="form" action="{{ $route }}" method="post">
			@csrf
			<div class="card-body">
				<div class="row">
					<div class="col-md-8 pr-4">
						<!-- Barcode -->
						<div class="form-group">
							<label>Ident-No.</label>
							<input type="text" name="barcode"
								class="form-control @error('barcode') cs-input-error @enderror"
								value="{{ old('barcode') ?? $product->barcode ?? '' }}">
							@error('barcode')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>
						<!-- Name -->
						<div class="form-group">
							<label>Naziv proizvoda</label>
							<input type="text" name="name" class="form-control @error('name') cs-input-error @enderror"
								value="{{ old('name') ?? $product->name ?? '' }}">
							@error('name')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>
						<!-- DESCRIPTION -->
						<div class="form-group">
							<label>Opis proizvoda</label>
							<textarea name="description"
								class="form-control @error('description') cs-input-error @enderror" cols="30"
								rows="3">{{ old('description') ?? $product->description ?? '' }}</textarea>
							@error('description')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>

						<!-- PRICE -->
						<div class="form-group">
							<label for="">Cena</label>
							<div class="row">
								<div class="col-md-9 col-7 ">
									<input type="number" class="form-control @error('price') cs-input-error @enderror"
										name="price" step="0.01" value="{{ old('price') ?? $product->price ?? 0 }}">
									@error('price')
									<span class="cs-error-message">
										{{ $message }}
									</span>
									@enderror
								</div>
								<div class="col-md-3 col-5">
									<select name="currency_id" class="form-control">
										@php
										$key = old('currency_id' ) ?? $product->currency_id ?? 0;
										@endphp
										@foreach($currencies as $currency)
										<option value="{{ $currency->id }}" @if($key==$currency->id) selected @endif>
											{{ $currency->symbol }}
										</option>
										@endforeach
									</select>
									@error('currency_id')
									<span class="alert alert-danger">
										{{ $message }}
									</span>
									@enderror
								</div>
							</div>
						</div>
						<!-- QUANTITY -->
						<div class="form-group">
							<label for="">Stanje na lageru</label>
							<input type="number" name="quantity" step="0.01"
								class="form-control @error('quantity') cs-input-error @enderror"
								value="{{ old('quantity') ?? $product->quantity ?? 0 }}">
							@error('quantity')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>

						<!-- UOM -->
						<div class="form-group">
							<label for="">Jedinica mere</label>
							<input type="text" name="uom"
								class="form-control @error('uom') cs-input-error @enderror"
								value="{{ old('uom') ?? $product->uom ?? '' }}">
							@error('uom')
							<span class="cs-error-message">
								{{ $message }}
							</span>
							@enderror
						</div>

					</div>
					<div class="col-md-4 pl-4">
						<div class="form-group cs-form-group-img">
							<label>Slika Proizvoda</label>
							@php
							$image_url = isset($product) ? $product->getImageURL() :
							App\Models\Product::defaultImageURL();
							@endphp
							<div class="lds-roller cs-roller">
								<div></div>
								<div></div>
								<div></div>
								<div></div>
							</div>
							<img id="product-image" src="{{ $image_url }}" alt="product-image">
							<input id="product-image-input" type="file" accept="image/jpeg, image/png, image/tiff"
								data-is-uploading=false hidden>
							<button type="button" id="product-image-trigger" class="cs-btn-primary mt-2">Dodaj
								sliku</button>
							<input id="product-image-name" type="text" name="image"
								value="{{ old('image') ?? $product->image ?? ''  }}" hidden>
						</div>
					</div>
				</div>
			</div>
			<!-- /.card-body -->
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
		let image = null;
		let uploader = new Components.FileUploader({
			uploadUrl: `{{ route('file::upload') }}`,
			deleteUrl: `{{ route('file::delete') }}`
		});

		$('#product-image-trigger').on('click', () => {
			if ($('#product-image-input').data('is-uploading')) {
				return;
			}

			$('#product-image-input').trigger('click');
		});

		$('#product-image-input').on('change', function (event) {
			let file = this.files[0];
			$('.lds-roller').addClass('cs-show')
			if ($(this).data('is-uploading')) {
				return;
			}

			if (file === void (0)) {
				return;
			}

			$(this).data('is-uploading', true);

			let helper = (uploaded) => {
				$('.lds-roller').removeClass('cs-show')
				image = uploaded;

				$(this).data('is-uploading', false);

				$('#product-image').attr('src', uploaded.url);
				$('#product-image-name').val(uploaded.name);
			};

			if (image === null) {
				uploader.upload(file).then((uploaded) => {
					helper(uploaded);
				}).catch((error) => {
					console.error(error);
				});

				return;
			}

			image.delete().then(() => {
				image = null;

				uploader.upload(file).then((uploaded) => {
					helper(uploaded);
				}).catch((error) => {
					console.error(error);
				});
			}).catch((error) => {
				console.error(error);
			});;
		});
	});
</script>
@endsection