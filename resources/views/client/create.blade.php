@extends ('layouts.admin')

@push('style')
@endpush

@push('script')
<script src="{{ asset('js/DynamicForm.js') }}" defer></script>
@endpush

@section('content')

<div class="content">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Izmeni klijenta</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
						<li class="breadcrumb-item active">Izmeni klijenta</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<form id="client-form" class="p-3 mt-2" action="{{ route('admin::create-client') }}" method="post">
		<div class="card cs-card-primary">
			<div class="card-header">
				<h3 class="card-title"> <i class="fas fa-edit"></i> Informacije o klijentu</h3>
				<!-- /.card-tools -->
			</div>
			<div class="card-body">
				<div class="form-group form-inline ">
					<label class="pr-2">Tip klijenta</label>
					<select class="form-control" id="select-client-type" name="client_type">
						<option value="legal_entity" selected>Pravno lice</option>
						<option value="individual">Fizičko lice</option>
					</select>
				</div>
				<hr>
				<div id="client-fields" class="cs-invoice-inputs">
				</div>
			</div>
		</div>

		<button type="submit" class="cs-btn-primary">Sačuvaj</button>
	</form>

	<div class="cs-overlay-loader">
		<div class="spinner-border cs-spinner" role="status">
		</div>
		  <span>Molimo sačekajte...</span>
	</div>
</div>

<script>
	class Client {
		constructor(options) {
			this.form = $(options.formSelector);
			this.fieldsContainer = this.form.find(options.fieldsContainerSelector);

			this.initFields(options.fieldsData);
		}

		initFields(data) {
			this.fieldsContainer.empty();

			this.fields = new DynamicFields(
				this.fieldsContainer,
				data
			);
		}
	}

	document.addEventListener('DOMContentLoaded', () => {
		const overlayLoader = $('.cs-overlay-loader');

		overlayLoader.hide();

		const client = new Client({
			formSelector: '#client-form',
			fieldsContainerSelector: '#client-fields',
			fieldsData: JSON.parse(`{!!$fields!!}`),
		});

		$('#select-client-type').on('change', function () {
			let data = {
				client_type: $(this).val()
			};

			axios.post(`{{ route('admin::get-client-fields') }}`, data)
				.then((response) => {
					client.initFields(response.data);

					$('#client-type').val(data.client_type);
				})
				.catch((response) => {});
		});

		const onErrorFieldClick = function (event) {
			$(this).removeClass('validation-error');
			$(this).siblings('.error-message').text('');
		};

		$('#client-form').on('submit', function (event) {
			event.preventDefault();

			let serialized = $(this).serializeArray();
			let data = {};

			serialized.forEach((field) => {
				data[field.name] = field.value;
			});

			overlayLoader.show();

			let start = Date.now();

			axios.post($(this).attr('action'), data)
				.then((response) => {
					let elapsed = (Date.now() - start) / 1000.;

					setTimeout(() => {
						overlayLoader.hide();

						$('#client-form')[0].reset();

						toastr['success']("Uspešno ste dodali klijenta!");
					}, 400 - elapsed);
				})
				.catch((error) => {
					let elapsed = (Date.now() - start) / 1000.;

					setTimeout(() => {
						overlayLoader.hide();
					}, 400 - elapsed);

					let data = error.response.data;
					let offset = Number.MAX_SAFE_INTEGER;

					$('.validation-error').removeClass('validation-error');

					if (!data) {
						return;
					}

					for (let id in data.errors) {
						let messages = data.errors[id];
						let field = $(`#${id.replace(/\./g, '\\.')}`);

						if (field.length === 0) {
							toastr['error'](messages[0]);
							continue;
						}

						let top = field.offset().top;

						if (offset > top) {
							offset = top
						}

						field.addClass('validation-error');
						field.siblings('.error-message').text(messages[0]);
					}

					$('html, body').animate({scrollTop: offset - 100}, 400);

					$('.validation-error')
						.off('click', onErrorFieldClick)
						.on('click', onErrorFieldClick);
				});
		});
	});
</script>
@endsection