@extends('layouts.admin')

@push('style')
@endpush

@push('script')
@endpush

@section('content')
<section class="content p-4">
	<section class="content-header">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1>Fakture</h1>
			</div>
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
			  <li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
				<li class="breadcrumb-item active">Fakture</li>
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
						<h3 class="card-title"><i class="fas fa-copy"></i> Lista faktura</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
					<div class="cs-react-table" 
						 id="invoice-list-table" 
						 data-url="{{ route('admin::invoice-view', [ 'id' ]) }}" 
						 data-jsonurl="{{ route('admin::get-invoices') }}"
						 data-deleteurl="{{ route('admin::delete-invoice', [ 'id' ]) }}"
						>
					</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
				<a href="{{route('admin::create-invoice')}}" class="cs-btn-primary">Nova fakutra <i class="fas fa-plus"></i></a>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection