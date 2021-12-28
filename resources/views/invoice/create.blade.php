@extends('layouts.admin')

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
					<h1>Napravi fakturu</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
						<li class="breadcrumb-item active">Napravi fakturu</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<form id="invoice-form" class="p-3 mt-2" action="{{ route('admin::create-invoice') }}" method="post">
		<div class="card cs-card-primary">
			<div class="card-header">
				<h3 class="card-title"> <i class="fas fa-edit"></i> Informacije o klijentu</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i
							class="fas fa-minus"></i></button>
				</div>
				<!-- /.card-tools -->
			</div>
			<div class="card-body">
				<div class="form-group form-inline ">
					<label class="pr-2">Tip fakture</label>
					<select class="form-control" id="select-invoice-type" name="invoice_type">
						<option value="invoice" selected>Račun</option>
						<option value="proforma_invoice">Predračun</option>
						<option value="advance_invoice">Avansni Račun</option>
						{{-- <option value="delivery_note">Otpremnica</option> --}}
					</select>
				</div>
				<div class="form-group form-inline ">
					<label class="pr-2">Broj fakture</label>
					<input name="unique_identifier" id="unique_identifier" type="text" class="form-control">
					<span class="error-message"></span>
				</div>
				<div class="form-group form-inline ">
					<label for="">Dodaj klijenta iz baze</label>
					<button id="select-client" class="cs-btn-primary ml-3" type="button"><i
							class="fas fa-database"></i></button>
				</div>

				<hr>
				<div id="invoice-fields" class="cs-invoice-inputs">
				</div>
			</div>
		</div>

		<div class="card cs-card-primary">
			<div class="card-header">
				<h3 class="card-title"> <i class="fas fa-warehouse"></i> Proizvodi za fakturisanje</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse"><i
							class="fas fa-minus"></i></button>
				</div>
			</div>
			<div class="card-body cs-product-table">
				<div class="cs-product-table-heading">
					<span>Tip</span>
					<span>Naziv</span>
					<span>Jedinica mere</span>
					<span>Količina</span>
					<span>Cena bez PDV</span>
					<span>PDV (%)</span>
					<span>Cena sa PDV</span>
					<span>Popust (%)</span>
					<span>Ukupan PDV</span>
					<span>Ukupno</span>
					<span></span>
				</div>
				<div id="invoice-items" class="cs-product-table-body">
					<template id="invoice-item-template">
						<div class="cs-product-table-row">
							<input type="hidden" class="item-product-id">
							<span>
								<select class="item-type">
									<option value="service">Usluga</option>
									<option value="product">Produkt</option>
								</select>
							</span>
							<span>
								<input id="items.{id}.item-name" class="item-name" type="text" />
							</span>
							<span>
								<input id="items.{id}.item-uom" class="item-uom" type="text" />
							</span>
							<span>
								<input id="items.{id}.item-quantity" class="item-quantity" type="text" />
							</span>
							<span>
								<input id="items.{id}.uom-price" class="item-uom-price" type="text" />
							</span>
							<span>
								<input id="items.{id}.item-tax-rate" class="item-tax-rate" type="text" />
							</span>
							<span>
								<input id="items.{id}.item-uom-price-tax" class="item-uom-price-tax" type="text" disabled />
							</span>
							<span>
								<input id="items.{id}.item-discount" class="item-discount" type="text" />
							</span>
							<span>
								<input id="items.{id}.item-tax-value" class="item-tax-value" type="text" disabled />
							</span>
							<span>
								<input id="items.{id}.item-final-price" class="item-final-price" type="text" disabled />
							</span>
							<span class="item-delete">
								<i class="fas fa-trash text-danger text-sm cs-delete-icon"></i>
							</span>
						</div>
					</template>
				</div>
				<button type="button" class="my-2 cs-btn-primary" id="add-item">Dodaj <i
						class="fas fa-plus"></i></button>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="row invoice-final-count">
					<!-- accepted payments column -->
					<div class="col-6">
						<div class="form-group">
							<label for="">Napomena:</label>
							<textarea class="form-control" name="invoice_note">Dokument je izrađen elektronski i punovažan je bez pečata i potpisa.</textarea>
						</div>

						<div class="form-group">
							<label for="">Ukupan iznos slovima:</label>
							<input id="invoice_price_as_word" name="invoice_price_as_word" class="form-control" type="text" placeholder="primer:dvestačetrdesetdva dinara">
							<span class="error-message"></span>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-6">
						<h4>Obračun:</h4>
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th style="width:50%">Ukupan iznos bez PDV:</th>
									<td class="price">0.00</td>
								</tr>
								<tr>
									<th>Ukupan rabat</th>
									<td class="discount_value">0.00</td>
								</tr>
								<tr>
									<th>Ukupno sa rabatom</th>
									<td class="base_price">0.00</td>
								</tr>
								<tr>
									<th>Ukupno iznos PDV</th>
									<td class="tax_value">0.00</td>
								</tr>
								<tr>
									<th>Ukupno za naplatu</th>
									<td>
										<span class="total">0.00</span>
										<strong>RSD</strong>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<!-- /.col -->
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
	{{-- SELECT PRODUCT MODAL START --}}
	<div class="modal fade cs-invoice-modal" id="select-product-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header cs-bg-primary">
					<h5 class="modal-title text-light" id="exampleModalLabel">Izaberi proizvod</h5>
					<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="modal-product-table" 
						data-url="{{ route('admin::products-json') }}" 
						class="product-list cs-react-table">
					</div>
					<input id="selected-product-json" type="text" hidden>
				</div>
				<div class="modal-footer">
					<button type="button" class="cs-btn-secondary" data-dismiss="modal">Zatvori</button>
					<button id="select-product-confirm" type="button" class="cs-btn-primary">Izaberi</button>
				</div>
			</div>
		</div>
	</div>
	{{-- SELECT PRODUCT MODAL END --}}

	{{-- SELECT CLIENT MODAL START --}}
	<div class="modal fade cs-invoice-modal" id="select-client-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header cs-bg-primary">
					<h5 class="modal-title text-light" id="exampleModalLabel">Izaberi klijenta</h5>
					<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fas fa-times"></i></span>
					</button>
				</div>
				<div class="modal-body">
					<div id="modal-clients-table"
						 data-url="{{ route('admin::get-clients') }}" 
						 class="client-list cs-react-table">
					</div>
					<input id="selected-client-json" type="text" hidden>
				</div>
				<div class="modal-footer">
					<button type="button" class="cs-btn-secondary" data-dismiss="modal">Zatvori</button>
					<button id="select-client-confirm" type="button" class="cs-btn-primary">Izaberi</button>
				</div>
			</div>
		</div>
	</div>
	{{-- SELECT PRODUCT MODAL END --}}
</div>
<script>
	class InvoiceItem {
		static ID = 0

		constructor(invoice, type, options, onChange) {
			this.invoice = invoice;
			this.type = type;
			this.options = options;
			this.onChange = onChange;
			this.id = ++InvoiceItem.ID;
			this.DOMNode = null;
			this.DOMFields = {};
			this.DOMValues = {};
			this.DOMNumericValues = {};

			this.createDOMNode();
		}

		getKey() {
			return `item-${this.id}`;
		}

		getDOMNode() {
			return this.DOMNode;
		}

		setDefaultValues() {
			this.DOMValues['item-product-id'] = 0;
			this.DOMValues['item-type'] = 'service';
			this.DOMValues['item-name'] = '';
			this.DOMValues['item-uom'] = 'kom';

			this.DOMNumericValues['item-quantity'] = 1;
			this.DOMNumericValues['item-uom-price'] = 0;
			this.DOMNumericValues['item-uom-price-tax'] = 0;
			this.DOMNumericValues['item-price'] = 0;
			this.DOMNumericValues['item-discount'] = 0;
			this.DOMNumericValues['item-base-price'] = 0;
			this.DOMNumericValues['item-tax-rate'] = 20;
			this.DOMNumericValues['item-tax-value'] = 0;
			this.DOMNumericValues['item-final-price'] = 0;
		}

		createDOMNode() {
			this.DOMNode = $(this.invoice.getItemTemplate().replace(/\{id\}/g, this.id));

			this.DOMFields['item-product-id'] = this.DOMNode.find('.item-product-id');
			this.DOMFields['item-type'] = this.DOMNode.find('.item-type');
			this.DOMFields['item-name'] = this.DOMNode.find('.item-name');
			this.DOMFields['item-uom'] = this.DOMNode.find('.item-uom');
			this.DOMFields['item-quantity'] = this.DOMNode.find('.item-quantity');
			this.DOMFields['item-uom-price'] = this.DOMNode.find('.item-uom-price');
			this.DOMFields['item-uom-price-tax'] = this.DOMNode.find('.item-uom-price-tax');
			this.DOMFields['item-price'] = this.DOMNode.find('.item-price');
			this.DOMFields['item-discount'] = this.DOMNode.find('.item-discount');
			this.DOMFields['item-base-price'] = this.DOMNode.find('.item-base-price');
			this.DOMFields['item-tax-rate'] = this.DOMNode.find('.item-tax-rate');
			this.DOMFields['item-tax-value'] = this.DOMNode.find('.item-tax-value');
			this.DOMFields['item-final-price'] = this.DOMNode.find('.item-final-price');

			this.setDefaultValues();

			const _this = this;

			this.DOMNode.find('.item-delete').on('click', function(event) {
				_this.invoice.removeItem(_this);
				_this.DOMNode.remove();
			});

			for (let key in this.DOMNumericValues) {
				let field = this.DOMFields[key];

				field.on('keydown', function (event) {
					let value = _this.filterInput(this, event);
				});

				field.on('input', () => {
					let value = field.val() ?? 0.;

					this.DOMNumericValues[key] = Number.parseFloat(value).toFixed(2) ?? 0.;
					this.updateNumericFields(key);
				});

				field.on('change', () => {
					let value = field.val() ?? 0.;

					this.DOMNumericValues[key] = Number.parseFloat(value).toFixed(2) ?? 0.;
					this.updateNumericFields();
				});
			}

			for (let key in this.DOMValues) {
				let field = this.DOMFields[key];

				field.on('change', function () {
					let value = $(this).val()

					_this.DOMValues[key] = value;

					if ($(this).hasClass('item-type')) {
						if (value === 'product') {
							_this.selectProduct()
								.then((data) => {
									_this.setDefaultValues();

									_this.type = 'product';
									_this.DOMValues['item-product-id'] = data.id;
									_this.DOMValues['item-type'] = 'product';
									_this.DOMValues['item-name'] = data.name;
									_this.DOMNumericValues['item-uom-price'] = data.price;
									_this.DOMFields['item-name'].prop('disabled', true);
									_this.DOMValues['item-uom'] = data.uom;

									_this.updateFields();
									_this.updateNumericFields();
								})
								.catch(() => {
									_this.type = 'service';

									$(this).val('service');
								});
						} else if (value === 'service') {
							_this.type = 'service';

							_this.DOMFields['item-name'].prop('disabled', false);

							_this.setDefaultValues();
							_this.updateFields();
							_this.updateNumericFields();
						}
					}
				});
			}

			this.updateFields();
			this.updateNumericFields();
		}

		selectProduct() {
			return this.invoice.selectProductAsync();
		}

		filterInput(input, event) {
			let value = $(input).val();
			let caret = input.selectionStart;
			let regexp = /^[0-9]*(\.[0-9]{0,2})?$/;

			if (!!event.key && event.key.length !== 1) {
				return value;
			}

			value = value.substring(0, caret) + event.key + value.substring(caret);

			if (value.length && !regexp.test(value)) {
				event.preventDefault();

				return false;
			}

			return value;
		}

		updateFields() {
			let values = this.DOMValues;

			for (let key in values) {
				this.DOMFields[key].val(values[key]);
			}
		}

		updateNumericFields(ignore) {
			let values = this.DOMNumericValues;

			values['item-price'] = values['item-quantity'] * values['item-uom-price'];
			values['item-base-price'] = values['item-price'] * (1 - (values['item-discount'] / 100));
			values['item-tax-value'] = values['item-base-price'] * (values['item-tax-rate'] / 100);
			values['item-final-price'] = values['item-base-price'] + values['item-tax-value'];
			values['item-uom-price-tax'] = values['item-uom-price'] * (1 + (values['item-tax-rate'] / 100));

			for (let key in values) {
				if (ignore && ignore === key) {
					continue;
				}

				this.DOMFields[key].val(Number.parseFloat(values[key]).toFixed(2) ?? 0.);
			}

			this.onChange(this);
		}
	}

	class Invoice {
		constructor(options) {
			this.form = $(options.formSelector);
			this.fieldsContainer = this.form.find(options.fieldsContainerSelector);
			this.itemsContainer = $(options.itemsContainerSelector);
			this.itemTemplate = options.itemTemplate;
			this.items = {};
			this.onItemChange = this.onItemChange.bind(this);

			this.initFields(options.fieldsData);
			this.initSelectProduct();
		}

		addItem(type, options) {
			let item = new InvoiceItem(this, type, options, this.onItemChange);

			this.items[item.getKey()] = item;
			this.itemsContainer.append(item.DOMNode);
		}

		removeItem(item) {
			delete this.items[item.getKey()];
		}

		getItemTemplate() {
			return this.itemTemplate;
		}

		selectProductAsync() {
			return new Promise((resolve, reject) => {
				this.selectProduct.resolve = resolve;
				this.selectProduct.reject = reject;

				this.selectProduct.modal.modal('show');
			});
		}

		initFields(data) {
			this.fieldsContainer.empty();

			this.fields = new DynamicFields(
				this.fieldsContainer,
				data
			);
		}

		initSelectProduct() {
			this.selectProduct = {
				modal: $('#select-product-modal'),
				confirm: $('#select-product-confirm'),
				resolve: null,
				reject: null
			};

			const closeModal = () => {
				this.selectProduct.modal.modal('hide');
				this.selectProduct.resolve = null;
				this.selectProduct.reject = null;
			};

			this.selectProduct.modal.on('hidden.bs.modal', () => {
				let reject = this.selectProduct.reject;

				if (typeof (reject) === 'function') {
					reject();
				}

				closeModal();
			});

			this.selectProduct.confirm.on('click', () => {
				let resolve = this.selectProduct.resolve;

				if (typeof (resolve) === 'function') {
					let fields;

					try {
						fields = JSON.parse($('#selected-product-json').val());
					} catch (exception) {
						return;
					}
					
					resolve(fields);
				}

				closeModal();
			});
		}

		onItemChange(item) {
			let values = {
				price: 0,
				discount_value: 0,
				base_price: 0,
				tax_value: 0,
				total: 0,
			}

			for (let key in this.items) {
				let numericValues = this.items[key].DOMNumericValues

				values['price'] += numericValues['item-price'];
				values['discount_value'] += numericValues['item-price'] * (numericValues['item-discount'] / 100);
				values['base_price'] += numericValues['item-base-price'];
				values['tax_value'] += numericValues['item-tax-value'];
				values['total'] += numericValues['item-final-price'];
			}

			for (let key in values) {
				$(`.${key}`).text(values[key].toFixed(2));
			}
		}
	}

	document.addEventListener('DOMContentLoaded', () => {
		// SELECT CLIENT AUTOFILL
		const overlayLoader = $('.cs-overlay-loader');
		const clientsModal = $('#select-client-modal');
		const clientsModalOpen = $('#select-client');
		const clientSelected = $('#selected-client-json');
		const clientModalConfirm = $('#select-client-confirm');

		clientsModalOpen.on('click', () => {
			clientsModal.modal('show');
		});

		clientModalConfirm.on('click', () => {
			let fields;

			try {
				fields = JSON.parse(clientSelected.val());
			} catch (exception) {
				return;
			}
			
			clientsModal.modal('hide');

			for (let id in fields) {
				$(`#${id}`)
					.val(fields[id])
					.trigger('change');
			}
		});
		// SELECT CLIENT AUTOFILL END

		overlayLoader.hide();

		const invoice = new Invoice({
			formSelector: '#invoice-form',
			fieldsContainerSelector: '#invoice-fields',
			fieldsData: JSON.parse(`{!! $fields !!}`),
			itemsContainerSelector: '#invoice-items',
			itemTemplate: $('#invoice-item-template').html()
		});

		$('#select-invoice-type').on('change', function () {
			let data = {
				invoice_type: $(this).val()
			};

			axios.post(`{{ route('admin::get-invoice-fields') }}`, data)
				.then((response) => {
					invoice.initFields(response.data);

					$('#invoice_type').val(data.invoice_type);
				})
				.catch((response) => {});
		});

		invoice.addItem('service', {});

		$('#add-item').on('click', () => {
			invoice.addItem('service', {});
		});

		const onErrorFieldClick = function (event) {
			$(this).removeClass('validation-error');
			$(this).siblings('.error-message').text('');
		};
		const redirectRoute = `{{ route('admin::invoice-view', [ 'invoice_id' ] ) }}`;

		$('#invoice-form').on('submit', function (event) {
			event.preventDefault();

			let serialized = $(this).serializeArray();
			let data = {
				items: {}
			};

			for (let key in invoice.items) {
				let item = invoice.items[key];
				let object = Object.assign({}, item.DOMValues);

				data.items[item.id] = Object.assign(object, item.DOMNumericValues);
			}

			serialized.forEach((field) => {
				data[field.name] = field.value;
			});

			overlayLoader.show();

			let start = Date.now();

			axios.post($(this).attr('action'), data)
				.then((response) => {
					window.location.href = redirectRoute.replace('invoice_id', response.data.id);
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