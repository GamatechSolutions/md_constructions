<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
	</ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/logoSmall1.png') }}" alt="User Image">
        </div>
        <div class="info">
          <h2 class="d-block text-light">Zavarivac<span class="cs-color-primary">KG</span></h2>
        </div>
      </div>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="info">
				@guest
				<a href="#" class="d-block">Niste ulogovani</a>
				@else
				<a href="#" class="d-block">{{ Auth::user()->name }}</a>
				@endguest
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			@auth
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				@guest
				<li class="nav-item">
					<a class="nav-link" href="{{ route('login') }}">
						<i class="fas fa-sign-in-alt"></i>
						<p>Prijavi se</p>
					</a>
				</li>
				@if(Route::has('register'))
				<li class="nav-item">
					<a class="nav-link" href="{{ route('register') }}">
						<i class="fas fa-user-shield"></i>
						<p>Prijavi se</p>
					</a>
				</li>
				@endif
				@else
				<li class="nav-item">
					<a class="nav-link" href="{{ route('admin::index') }}">
						<i class="fas fa-home"></i>
						<p>Početna</p>
					</a>
				</li>
				@endguest

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-warehouse"></i>
						<p>
							Proizvodi
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@can ('product.create')
						<li class="nav-item">
							<a class="nav-link" href="{{ route('product::create-view') }}">
								<i class="fas fa-plus"></i>
								<p>Dodaj proizvod</p>
							</a>
						</li>
						@endcan
						@can ('product.view')
						<li class="nav-item">
							<a class="nav-link" href="{{ route('product::list-view') }}">
								<i class="fas fa-clipboard-list"></i>
								<p>Lista proizvoda</p>
							</a>
						</li>
						@endcan
					</ul>
				</li>

				@role ('Administrator')
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-user-cog"></i>
						<p>
							Korisnici
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::create-user') }}">
								<i class="fas fa-plus"></i>
								<p>Napravi korisnika</p>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::list-users') }}">
								<i class="fas fa-clipboard-list"></i>
								<p>Lista korisnika</p>
							</a>
						</li>
					</ul>
				</li>
				@endrole

				@if (Auth::user()->hasRole([ 'Administrator', 'Računovođa' ]))
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-copy"></i>
						<p>
							Fakture
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::create-invoice') }}">
								<i class="fas fa-plus"></i>
								<p>Dodaj fakturu</p>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::invoice-list') }}">
								<i class="fas fa-clipboard-list"></i>
								<p>Lista faktura</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-user-tie"></i>
						<p>
							Klijenti
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::client') }}">
								<i class="fas fa-plus"></i>
								<p>Dodaj klijenta</p>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="{{ route('admin::client-list') }}">
								<i class="fas fa-clipboard-list"></i>
								<p>Lista klijenata</p>
							</a>
						</li>
					</ul>
				</li>
				@endif

				@can ('activity.view')
				<li class="nav-item">
					<a class="nav-link" href="{{ route('activity::view') }}">
						<i class="fas fa-history"></i>
						<p>Istorija dešavanja</p>
					</a>
				</li>
				@endcan
				
				<li class="nav-item">
					<a class="nav-link" href="{{ route('logout') }}">
						<i class="fas fa-sign-out-alt"></i>
						<p>Odjavi se</p>
					</a>
				</li>
			</ul>
			@endauth
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>