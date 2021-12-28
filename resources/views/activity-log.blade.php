@extends('layouts.admin')

@push('script')
<script src="{{ asset('js/activity/moment.min.js') }}" defer></script>
<script src="{{ asset('js/activity/timeline.js') }}" defer></script>
<script src="{{ asset('js/activity/timeline-section.js') }}" defer></script>
<script src="{{ asset('js/activity/timeline-section-item.js') }}" defer></script>
@endpush

@push('style')
@endpush

@section('content')
<div class="content p-4">
	<div id="timeline-container" class="container-fluid">
		<!-- TIMELINE -->
	</div>
	<div class="d-flex justify-content-center" style="height : 50px !important;">
		<a id="show-more" class="text-primary mb-2 mt-2" style="cursor:pointer">Prikazi prethodni dan</a>
		<div id="show-more-loader" class="spinner-border text-primary" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', async () => {
		let timeline = new Activity.Timeline(
			`#timeline-container`,
			`{{ route('activity::get-daily') }}`,
		);
		
		let button = $('#show-more');
		let loader = $('#show-more-loader');
		
		async function onClick()
		{
			button.hide();
			loader.show();
			
			let section = await timeline.createSection()
			
			section.scrollDocumentTo(700);
			
			loader.hide();
			button.show();
		}
		
		button.on('click', () => {
			onClick();
		});
		
		onClick();
	});
</script>
@endsection