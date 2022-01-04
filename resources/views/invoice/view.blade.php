@extends('layouts.admin')

@section('content')
<div class="content">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Pregled fakture</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Lista Faktura</a></li>
						<li class="breadcrumb-item active">Faktura {{ $fields->unique_number ?? '' }}</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="callout callout-info cs-callout">
						<h5><i class="fas fa-info"></i> Napomena:</h5>
						Fakturu možete odštampati ili generisati PDF direktno iz aplikacije. <br>
						Trenutni status fakture je: <strong class="{{ "cs-status-{$invoice->status}" }}">@lang("alias.invoice_status.{$invoice->status}")</strong>
					</div>

					<!-- Main content -->
					<div class="invoice p-3 mb-3">
						<!-- title row -->
						<div class="row header">
							<div class="col-8 about-company">
								<div class="logo">
									<img src="{{ asset('assets/images/logo1.png') }}" alt="">
								</div>
								<div class="info">
									<address>
										<span>
											<i class="fas fa-map-marker-alt"></i>{{ $invoice_issuer->address }}
										</span>
										<span><i class="fas fa-phone"></i> {{$invoice_issuer->phone}}</span>
										<span><i class="fas fa-envelope"></i> {{ $invoice_issuer->email }}</span>
										<span><i class="fas fa-globe"></i> {{ $invoice_issuer->website }}</span>
									</address>
								</div>

							</div>
							<div class="col-4 invoice-date">
								<h3>@lang("alias.invoice_type.{$invoice->type}") broj: {{ $fields->unique_number ?? '' }}</h3>
								@isset($fields->is_delivery_note)
									<span class="bg-warning">Račun je ujedno i otpremnica</span>
								@endisset
								<div>
									<span>Datum izdavanja:
										<strong>{{ $fields->issue_date ?? 'nije navedeno' }}</strong></span>
									<span>Mesto izdavanja:
										<strong>{{ $fields->issue_place ?? 'nije navedeno' }}</strong></span>
									@isset($fields->delivery_date)
										<span>Datum prometa robe:
											<strong>{{ $fields->delivery_date ?? 'nije navedeno' }}</strong></span>
									@endisset
									
								</div>
							</div>
							<!-- /.col -->
						</div>
						<hr>
						<!-- info row -->
						<div class="row invoice-info">
							<div class="col-sm-6 invoice-col sender">
								<div class="card card-outline card-danger">
									<div class="card-header">
										<h3 class="card-title">Od:</h3>
									</div>
									<div class="card-body">
										<h4> <strong> {{$invoice_issuer->name }}</strong></h4>
										<span> <strong> PIB:</strong> {{$invoice_issuer->pib}}</span>
										<span> <strong> Matični br.:</strong> {{$invoice_issuer->firm_id}}</span>
										<span> <strong> Račun:</strong> {{$invoice_issuer->account_number}}</span>
										{{-- <span> <strong> IBAN:</strong> {{$invoice_issuer->IBAN}}</span> --}}
									</div>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-sm-6 invoice-col client ">
								<div class="card card-outline card-danger">
									<div class="card-header">
										<h3 class="card-title">Za:</h3>
									</div>
									<div class="card-body">
										<h4> <strong>
												{{ $fields->firm_name ?? $fields->individual_name ?? 'nije navedeno' }}
											</strong>
										</h4>
										<span> <strong> Adresa:</strong> {{$fields->street ?? ''}} |
											{{$fields->city ?? ''}} |
											{{$fields->state ?? ''}}</span>
										@isset($fields->individual_id )
										<span><strong> JMBG:</strong>
											{{$fields->individual_id ?? 'nije navedeno'}}</span>
										@endisset
										@isset($fields->pib)
										<span><strong> PIB:</strong> {{$fields->pib ?? 'nije navedeno'}}</span>
										@endisset
										@isset($fields->firm_id )
										<span> <strong> Matični br.:</strong> {{$fields->firm_id ?? '/'}}</span>
										@endisset
									</div>
								</div>
							</div>
						</div>
						<!-- /.row -->
						<!-- Table row -->
						<div class="row">
							<div class="col-12 table-responsive">
								<table class="table table-bordered  cs-print-table">
									<thead>
										<tr>
											<th>Id</th>
											<th>Naziv</th>
											<th>Jedinica mere</th>
											<th>Količina</th>
											<th>Cena bez PDV</th>
											<th>PDV (%)</th>
											<th>Cena sa PDV</th>
											<th>Popust (%)</th>
											<th>Ukupan PDV</th>
											<th>Ukupno</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($invoice->items as $key => $item)
										<tr>
											<td>{{ $key + 1 }}</td>
											<td>{{ $item->name }}</td>
											<td>{{ $item->uom }}</td>
											<td>{{ number_format((float)$item->quantity, 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->uom_price, 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->tax_rate, 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->uomPriceWithTax(), 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->discount, 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->taxValue(), 2, '.', '') }}</td>
											<td>{{ number_format((float)$item->totalPrice(), 2, '.', '') }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
						<div class="row invoice-final">
							<!-- accepted payments column -->
							<div class="col-6 invoice-note">
								<div class="invoice-note-item">
									<span class="text-bold">Napomena:</span>
									<span class="text-muted">{{ $invoice->note ?? '' }}</span>
								</div>
								<div class="invoice-note-item">
									<span class="text-bold">Iznos u slovima:</span>
									<span class="text-muted">{{ $invoice->price_as_word ?? '' }}</span>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-6 final-count">
								<h5 class="deadline">Rok za uplatu: {{$fields->payment_deadline}} <span
										class="deadline-value"></span></h5>
								<ul>
									<hr>
									<li>
										<span>Ukupan iznos bez PDV:</span>
										<span>
											{{ number_format((float)$fields->base_price, 2, '.', '') }}
										</span>
									</li>
									<li>
										<span>Ukupan rabat</span>
										<span>
											{{ number_format((float)$fields->discount_value, 2, '.', '') }}
										</span>
									</li>
									<hr>
									<li>
										<span>Ukupno sa rabatom:</span>
										<span>
											{{ number_format((float)($fields->base_price - $fields->discount_value), 2, '.', '') }}
										</span>
									</li>
									<li>
										<span>Ukupan PDV:</span>
										<span>
											{{ number_format((float)$fields->tax_value, 2, '.', '') }}
										</span>
									</li>
									<hr>
									@if($fields->type == "Avansni Račun")
									<li class="highlight-price">
										<span>Ukupno:</span>
										<span>{{ number_format((float)$fields->price, 2, '.', '') }}
										RSD
										</span>
									</li>
									<li class="highlight-price">
										<span>Uplaćeno Avansno:&nbsp;</span>
										<span>
											{{ number_format((float)$fields->advance_price, 2, '.', '') }}
											RSD
										</span>
									</li>
									<li class="highlight-price">
										<span>Ostalo za uplatu:&nbsp;</span>
										<span>
											{{ number_format((float)$fields->price - $fields->advance_price, 2, '.', '') }}
											RSD
										</span>
									</li>
									@else
										@isset($parent)
											@if ($parent->type == 'advance_invoice')
											<li class="highlight-price">
												<span>Ukupno:</span>
												<span>{{ number_format((float)$fields->price, 2, '.', '') }}
												RSD
												</span>
											</li>
											<li class="highlight-price">
												<span>Uplaćeno Avansno:&nbsp;</span>
												<span>
													{{ number_format((float)$fields->advance_price, 2, '.', '') }}
													RSD
												</span>
											</li>
											<li class="highlight-price">
												<span>Ostalo za uplatu:&nbsp;</span>
												<span>
													{{ number_format((float)$fields->price - $fields->advance_price, 2, '.', '') }}
													RSD
												</span>
											</li>
											@else
											<li class="highlight-price"> 	
												<span>Ukupno:&nbsp;</span>
												<span>
													{{ number_format((float)$fields->price, 2, '.', '') }}
													RSD
												</span>
											</li>
											@endif
										@else
											<li class="highlight-price"> 	
												<span>Ukupno:&nbsp;</span>
												<span>
													{{ number_format((float)$fields->price, 2, '.', '') }}
													RSD
												</span>
											</li>
										@endisset
									
									@endif
								</ul>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
						<hr>
						<div class="py-1 text-center invoice-footer">Datum izdavanja: {{$fields->issue_date ?? ''}}
						</div>
					</div>
					<!-- /.invoice -->
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-body invoice-controls">
					<div class="row">
						<div class="col-6">
							<button class="invoice-action btn bg-purple" data-action="email"
								data-route="{{ route('admin::invoice-mail', [ $invoice->id ]) }}"
								data-email="{{ $field->contact_email ?? '' }}" data-title="Prosledi fakturu na email"
								data-message="">
								<i class="fas fa-envelope"></i>
							</button>
							<a class="btn btn-success" href="{{ route('admin::invoice-pdf', [ $invoice->id ]) }}">
								<i class="fas fa-download"></i>
							</a>
							<a class="btn btn-info" id="print-js"
								href="{{ route('admin::invoice-pdf', [ $invoice->id ]) }}">
								<i class="fas fa-copy"></i>
							</a>
						</div>
						<div class="col-6 action-buttons">
							@foreach ($actions as $action => $data)
							<button class="invoice-action btn {{ $data->color_class }}" data-action="{{ $action }}"
								data-route="{{ route($data->route, [ $invoice->id ]) }}" data-title="{{ $data->title }}"
								data-message="{{ $data->message }}">
								<i class="{{ $data->icon_class }}"></i>
								{{ $data->label }}
							</button>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>
<!-- /.content -->

{{-- ACTIONS MODAL --}}
<div class="modal fade cs-invoice-modal cs-invoice-view-modal" id="invoice-actions-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header cs-bg-primary">
				<h5 class="modal-title text-light"></h5>
				<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="actions-form" action="" method="post">
				@csrf
				<div class="modal-body">
					<div class="modal-message"></div>
					{{-- CONFIRM ONLY ACTION --}}
					<div class="actions-section section-confirm"></div>
					{{-- CONFIRM EMAIL ACTION --}}
					<div class="actions-section section-email">

						@if($fields->contact_email)
						<p>Email adresa za slanje email-a je <span
								class="cs-color-primary">{{$fields->contact_email ?? ''}}</span> Ukoliko želite
							da račun posaljete na neku drugu email adresu unesite u polje ispod.
						</p>
						<div class="form-group form-inline">
							<label for="">Nov email:</label>
							<input class="email form-control ml-2" type="email" name="invoice_email">
						</div>
						@else
						<p>Nije uneta email adresa za slanje računa, molimo Vas unesite
							email adresu u polje ispod.
						</p>
						<div class="form-group form-inline">
							<label for="">Email adresa:</label>
							<input class="email form-control ml-2" type="email" name="invoice_email">
						</div>
						@endif
					</div>
					{{-- CREATE INVOICE ACTION --}}
					<div class="actions-section section-create-invoice">
						<span>ID profakture je  <span
								class="cs-color-primary">{{$fields->unique_number}}</span> ukoliko želite
								da izdate fakturu pod drugim brojem unesite u polje ispod. Poslednja izdata Faktura
								ima broj {{ $last_id }}.
						</span>
						<div class="form-group form-inline">
							<label for="">Id fakture:</label>
							<input class="email form-control ml-2" type="text" name="invoice_new_id">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="cs-btn-secondary" data-dismiss="modal">Zatvori</button>
					<button type="submit" class="cs-btn-primary">Potvrdi</button>
				</div>
			</form>
		</div>
	</div>
</div>
{{-- ACTIONS MODAL END --}}
<script>
	const InvoiceActions = {
		modal: null,
		actionButtons: null,
		form: null,
		sections: null,

		initialize: function () {
			this.modal = $('#invoice-actions-modal');
			this.actionButtons = $('.invoice-action');
			this.form = this.modal.find('.actions-form');
			this.sections = this.form.find('.actions-section');

			const _this = this;

			this.actionButtons.on('click', function (event) {
				_this.onActionInvoke(event, $(this));
			});
		},

		openModal: function () {
			this.sections.hide();
			this.modal.modal('show');
		},

		closeModal: function () {
			this.sections.hide();
			this.modal.modal('hide');
		},

		onActionInvoke: function (event, target) {
			let action = $(target).data('action');
			let section;

			switch (action) {
				case 'complete':
				case 'cancel':
				case 'confirm':
					section = 'confirm';
					break;
				case 'create_invoice':
					section = 'create-invoice';
					break;
				case 'email':
					section = 'email';
					break;

				default:
					return;
			}

			this.openModal();

			this.modal.find('.modal-title').text($(target).data('title'));
			this.modal.find('.modal-message').text($(target).data('message'));
			this.modal.find('.modal-message').text($(target).data('message'));
			this.form.attr('action', $(target).data('route'));
			this.form.find(`.section-${section}`)
				.show();
		},

		onActionCancel: function () {
			this.onClose();
		}
	};

	document.addEventListener('DOMContentLoaded', () => {
		InvoiceActions.initialize();
		/** PRINT PDF */
		$('#print-js').on('click', function (event) {
			event.preventDefault();

			let options = {
				url: $(this).attr('href'),
				method: 'GET',
				responseType: 'blob',
			};

			axios(options)
				.then((response) => {
					printJS(window.URL.createObjectURL(new Blob([response.data])));
				})
				.catch((error) => {

				});
		});
	});
</script>
@endsection