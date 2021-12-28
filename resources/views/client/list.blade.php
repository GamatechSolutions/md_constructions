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
					<h1>Klijenti</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{route('admin::index')}}">Početna</a></li>
						<li class="breadcrumb-item active">Klijenti</li>
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
						<h3 class="card-title"><i class="fas fa-copy"></i> Lista klijenata</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<div id="client-list-table" 
							 data-url="{{route('admin::get-clients')}}"
							 data-editurl="{{ route('admin::edit-client', [ 'id' ]) }}"
							 data-deleteurl="{{ route('admin::delete-client', [ 'id' ]) }}"
							 class="cs-react-table"
							 ></div>
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
@endsection

