<div id="product-action-modal" class="cs-popup-window">
	<div id="action-increase-quantity" class="action-section">
		<form action="" method="POST">
			@csrf
			<h2>Dodaj na stanje</h2>

			<input type="number" name="quantity" value="1" min="1">

			<div class="cs-button-group">
				<button type="button" class="action-cancel btn btn-danger">Otkaži</button>
				<button type="submit" class="btn btn-success">Potvrdi</button>
			</div>

		</form>
	</div>

	<div id="action-decrease-quantity" class="action-section">
		<form action="" method="POST">
			@csrf
			<h2>Oduzmi sa stanja</h2>

			<input type="number" name="quantity" value="1" min="1">

			<div class="cs-button-group">
				<button type="button" class="action-cancel btn btn-danger">Otkaži</button>
				<button type="submit" class="btn btn-success">Potvrdi</button>
			</div>

		</form>
	</div>
</div>

<script>
	const ProductActions = {
		initialize: function () {
			this.modal = $('#product-action-modal');
			this.sections = this.modal.find('.action-section');

			this.modal.find('.action-cancel').on('click', ProductActions.onActionCancel.bind(this));

			this.hideModal();
		},

		hideModal: function () {
			this.modal.hide();
			this.sections.hide();
		},
		onClose() {
			this.hideModal();
		},

		onActionInvoke: function (event, target) {
			this.hideModal();

			let route = $(target).data('href');
			let action = $(target).data('action');
			let section = $(`#action-${action}`);
			let form = section.find('form');
			
			this.modal.show();
			section.show();
			form.attr('action', route);
			form.find('input[number]').val(1);
		},

		onActionCancel: function () {
			this.onClose();
		}
	};
</script>