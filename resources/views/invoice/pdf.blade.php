<!DOCTYPE html>
<html>

<head>

	<title>Invoice PDF</title>

	<style type="text/css" media="all">
		table {
			margin: 0 initial;
			position: relative;
			width: 703px;
			border-collapse: collapse;
		}

		ul {
			padding: 0;
			list-style-type: none;
		}

		h1,
		h2,
		h3,
		h4,
		li,
		span,
		ul,
		p {
			margin: 0;
			padding: 0;
		}

		.pdf-top tr,
		.pdf-top td {
			margin: 0;
			padding: 0;
		}

		.pdf-top tr {
			position: relative;
			width: 100%;
		}

		.pdf-top .logo {
			position: relative;
			width: 55%;
		}

		.pdf-top .logo img {
			width: 80px;
			height: auto;
		}

		.pdf-top .date {
			text-align: right;
			vertical-align: top;
		}

		.pdf-top .date h3 {
			margin: 0;
			padding: 0;
			margin-bottom: 6px;
			font-size: 48px !important;
		}

		/* product table */
		.product-table {
			border: 1px solid #ced4da;
			border-collapse: collapse;
			width: 100%;
		}

		.product-table th {
			border: 1px solid #ced4da;
			border-collapse: collapse;
			padding: 4px 3px;
			margin: 0;

		}

		.product-table td {
			padding: 4px 3px;
		}

		.product-table tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		.product-table th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #58b3cc;
			color: white;
			text-align: left;
		}

		.product-table tr th:nth-child(2),
		.product-table tr td:nth-child(2) {
			width: 200px;
		}

		/* .product-table .h-name, .product-table .c-name{
			width: 100%;
		} */



		.contact-info {
			width: 400px;
		}

		.contact-info p {
			position: relative;
			width: 100%;
		}

		.contact-info p img {
			margin-right: 100px;
		}

		/* UTILITY */
		.test-border {
			border: 1px solid red;
		}

		.hr-line {
			margin: 0;
			padding: 0;
			margin: 12px 0;
			color: #ced4da;
		}

		.hr-line-small {
			margin: 0;
			padding: 0;
			margin: 4px 0;
			color: #ced4da;
		}

		.row {
			position: relative;
			width: 703px;

		}

		.row .col-2 {
			width: 48%;
			height: 180px;
			float: left;
		}

		.row::after {
			content: " ";
			display: block;
			clear: both;
		}

		.text-bold {
			color: #000;
			font-weight: bold;
		}

		.text-muted {
			color: #1f1f20;
		}

		.contact-info {
			margin-top: 10px;
		}

		.contact-info .icon {
			width: 30px;
		}

		.transaction-info {
			margin-bottom: 16px;
		}

		.transaction-info .card {
			border-radius: 5px;
			border: 1px solid #ced4da;
			padding: 10px 0;
			height: 300px;
			border-top: 2px solid #58b3cc;
		}

		.transaction-info .card-name {
			font-size: 28px;
			font-weight: 500;
			padding: 4px 5px;
		}

		.transaction-info .card-header {
			font-size: 22px;
			font-weight: 400;
			padding: 4px 5px;
			border-bottom: 1px solid #ced4da;
		}

		.sender-table,
		.receiver-table {
			width: 310px;
			padding: 5px 10px;
		}

		.t-table td {
			padding-left: 10px;
		}

		.t-table .table-label {
			font-weight: bold;
			width: 35%;
		}

		/* BOTTOM SECTION */
		.bottom-section {
			position: relative;
			margin-top: 32px;
			bottom: 0;
			vertical-align: bottom;
		}

		.row.bottom-section .final-note {
			position: relative;
			width: 50%;
		}

		.row.bottom-section .final-count {
			width: 50%;
		}


		.invoice-note-item {
			position: absolute;
			width: 80%;
		}

		.final-count table {
			width: 100%;
		}

		.final-count ul li table tr td {
			font-size: 16px;
		}

		.final-count ul li table tr td:nth-child(1) {
			font-weight: bold;
		}

		.final-count ul li table tr td:nth-child(2) {
			text-align: right;
		}

		.final-count .deadline {
			font-size: 18px;
			font-weight: bold;
		}

		.final-price {
			background-color: #58b3cc;
		}

		.final-count ul .final-price table tr td {
			font-size: 20px;
			color: #fff;
		}

		.bottom-section .left{
			width: 40%;
		}
		.bottom-section .right{
			width: 60%;
		}
		.bottom-section .footer {
			width: 100%;
			text-align: right;
		}
	</style>

</head>
<body>
	<div class="pdf-invoice">
		<table class="pdf-top">
			<tr>
				<td class="logo">
					<img src="{{ asset('assets/images/logo1.png') }}" width="120" alt="">
				</td>
				<td class="date">
					<h3>@lang("alias.invoice_type.{$invoice->type}") broj: {{ $fields->unique_number ?? '' }}</h3>

					<div>Datum izdavanja: <strong>{{ $fields->issue_date ?? '' }}</strong></div>
					<div>Mesto izdavanja: <strong>{{ $fields->issue_place ?? '' }}</strong></div>
					@isset($fields->delivery_date)
						<div>Datum prometa robe: <strong>{{ $fields->delivery_date ?? '' }}</strong></div>
					@endisset
				</td>
			</tr>
		</table>
		<table class="contact-info">
			<tr>
				<td class="icon"><img width="20" src="{{ asset('assets/icons/pin2.png')}}" alt=""></td>
				<td class="text">{{$invoice_issuer->address}}</td>
			</tr>
			<tr>
				<td class="icon"><img width="20" src="{{ asset('assets/icons/phone2.png')}}" alt=""></td>
				<td class="text">{{$invoice_issuer->phone}}</td>
			</tr>
			<tr>
				<td class="icon"><img width="20" src="{{ asset('assets/icons/email2.png')}}" alt=""></td>
				<td class="text">{{ $invoice_issuer->email ?? ''}}</td>
			</tr>
			<tr>
				<td class="icon"><img width="20" src="{{ asset('assets/icons/globe2.png')}}" alt=""></td>
				<td class="text">{{ $invoice_issuer->website ?? ''}}</td>
			</tr>
		</table>
		<hr class="hr-line">
		<div class="row transaction-info">
			<div class="col-2 card sender-card">
				<div class="card-header">
					Od:
				</div>
				<div class="card-name">
					{{ $invoice_issuer->name ?? ''}}
				</div>
				<table class="t-table sender-table">
					<tr>
						<td class="table-label">PIB:</td>
						<td class="table-value">{{ $invoice_issuer->pib ?? '' }}</td>
					</tr>
					<tr>
						<td class="table-label">Matični br.:</td>
						<td class="table-value">{{ $invoice_issuer->firm_id ?? '' }}</td>
					</tr>
					<tr>
						<td class="table-label">Račun:</td>
						<td class="table-value">{{ $invoice_issuer->account_number ?? '' }}</td>
					</tr>
				{{-- 	<tr>
						<td class="table-label">IBAN:</td>
						<td class="table-value">{{ $invoice_issuer->IBAN ?? '' }}</td>
					</tr> --}}
				</table>
			</div>
			<div class="col-2 card receiver-card" style="float: right">
				<div class="card-header">
					Za:
				</div>
				<div class="card-name">
					{{ $fields->firm_name ?? $fields->individual_name }}
				</div>
				<table class="t-table receiver-table">
					<tr>
						<td class="table-label">Adresa:</td>
						<td class="table-value">{{ $fields->street ?? ''}} | {{ $fields->city ?? ''}} | {{ $fields->state ?? ''}}</td>
					</tr>
					<tr>
						<td class="table-label">PIB:</td>
						<td class="table-value">{{ $fields->pib ?? $fields->JMBG }}</td>
					</tr>
					<tr>
						<td class="table-label">Matični br.:</td>
						<td class="table-value">{{ $fields->firm_id ?? '/' }}</td>
					</tr>
				</table>
			</div>
		</div>

		<table class="product-table">
			<thead>
				<tr>
					<th>Id</th>
					<th class="h-name">Naziv</th>
					<th>Jed. mere</th>
					<th>Kol.</th>
					<th>Cena bez PDV</th>
					<th>PDV (%)</th>
					<th>Cena sa PDV</th>
					<th>Rabat (%)</th>
					<th>Ukupan PDV</th>
					<th>Ukupno</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($invoice->items as $key => $item)
				<tr>
					<td>{{ $key + 1 }}</td>
					<td class="c-name">{{ $item->name }}</td>
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
		<div class="row bottom-section">
			<div class="col-2 final-note left">
				<div class="invoice-note-item">
					<span class="text-bold">Napomena:</span>
					<div class="text-muted">{{ $invoice->note ?? '' }}</div>
				</div>
			</div>
			<div class="col-2 final-count right">
				<p class="deadline">Rok za uplatu: {{ $fields->payment_deadline ?? '' }}</p>
				<hr class="hr-line-small">
				<ul>
					<li>
						<table>
							<tr>
								<td class="table-label">Ukupan iznos bez PDV:</td>
								<td class="table-value">{{ number_format((float)$fields->base_price, 2, '.', '') }}</td>
							</tr>
							<tr>
								<td class="table-label">Ukupan rabat:</td>
								<td class="table-value">{{ number_format((float)$fields->discount_value, 2, '.', '') }}
								</td>
							</tr>
						</table>
					</li>
					<hr class="hr-line-small">
					<li>
						<table>
							<tr>
								<td class="table-label">Ukupno sa rabatom:</td>
								<td class="table-value">
									{{ number_format((float)($fields->base_price - $fields->discount_value), 2, '.', '') }}
								</td>
							</tr>
							<tr>
								<td class="table-label">Ukupan  PDV:</td>
								<td class="table-value">{{ number_format((float)$fields->tax_value, 2, '.', '') }}</td>
							</tr>
						</table>
					</li>
					<hr class="hr-line-small">
					<li class="final-price">
						<table>
							@if($fields->type == "Avansni Račun")
							<tr>
								<td class="table-label">Ukupno:&nbsp;</td>
								<td class="table-value">
									{{ number_format((float)$fields->price, 2, '.', '') }}
									RSD
								</td>
							</tr>
							<tr>
								<td class="table-label">Uplaćeno Avansno:&nbsp;</td>
								<td class="table-value">
									{{ number_format((float)$fields->advance_price, 2, '.', '') }}
									RSD
								</td>
							</tr>
							<tr>
								<td class="table-label">Ostalo za uplatu:&nbsp;</td>
								<td class="table-value">
									{{ number_format((float)$fields->price - $fields->advance_price, 2, '.', '') }}
									RSD
								</td>
							</tr>
							@else
								@isset($parent)
								@if ($parent->type == 'advance_invoice')	
								<tr>
									<td class="table-label">Ukupno:&nbsp;</td>
									<td class="table-value">
										{{ number_format((float)$fields->price, 2, '.', '') }}
										RSD
									</td>
								</tr>
								<tr>
									<td class="table-label">Uplaćeno Avansno:&nbsp;</td>
									<td class="table-value">
										{{ number_format((float)$fields->advance_price, 2, '.', '') }}
										RSD
									</td>
								</tr>
								<tr>
									<td class="table-label">Ostalo za uplatu:&nbsp;</td>
									<td class="table-value">
										{{ number_format((float)$fields->price - $fields->advance_price, 2, '.', '') }}
										RSD
									</td>
								</tr>
								@else
								<tr>
									<td class="table-label">Ukupno:&nbsp;</td>
									<td class="table-value">
										{{ number_format((float)$fields->price, 2, '.', '') }}
										RSD
									</td>
								</tr>
								@endif
								@else
								<tr>
									<td class="table-label">Ukupno:&nbsp;</td>
									<td class="table-value">
										{{ number_format((float)$fields->price, 2, '.', '') }}
										RSD
									</td>
								</tr>
							@endisset
							@endif
						</table>
					</li>
				</ul>
			</div>
			<hr class="hr-line">
			<div class="footer">
				<span class="text-bold">Iznos u slovima:</span>
				<span class="text-muted">{{ $invoice->price_as_word ?? '' }}</span>
			</div>
		</div>
	</div>
</body>

</html>