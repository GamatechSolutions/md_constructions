@extends('layouts.app')

@section('layout')

@include('include.navigation')

<div class="content-wrapper">
	@yield('content')
</div>

@endsection