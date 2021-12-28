'use strict';

var Activity = (function (Activity) {
	class TimelineSectionItem {
		constructor(section, data, content) {
			this.section = section;
			this.data = data;
			this.id = ++TimelineSectionItem.ID;
			this.content = content ?? TimelineSectionItem.TEMPLATE;
		}

		render() {
			let icon = TimelineSectionItem.ICON_MAP[this.data['action']] ?? '';
			let time = this.data['time'] ?? '';
			let message = this.data['message'] ?? '';

			return this.content
				.replace(/\{id\}/, this.id)
				.replace(/\{class\}/, icon)
				.replace(/\{time\}/, time)
				.replace(/\{message\}/, message);
		}

		getId() {
			return `timeline-section-item-${this.section.id}-${this.id}`;
		}

		static ID = 0;
		static TEMPLATE = `
			<div id="timeline-section-item-{id}" class="">
				<i class="fas {class}"></i>
				<div class="timeline-item">
					<span class="time">
						<i class="fas fa-clock"></i>
						{time}
					</span>
					<h3 class="timeline-header no-border">
						{message}
					</h3>
				</div>
			</div>
		`;
		static TEMPLATE_DUMMY = `
			<div id="timeline-section-item-{id}" class="">
				<i class="fas fa-database bg-dark"></i>
				<div class="timeline-item">
					<h3 class="timeline-header no-border">
						{message}
					</h3>
				</div>
			</div>
		`;
		static ICON_MAP = {
			'product-create': 'fas fa-box-open bg-green',
			'product-edit': 'fas fa-edit bg-danger',
			'product-delete': 'fas fa-trash bg-danger',
			'product-increase-quantity': 'fas fa-plus bg-green',
			'product-decrease-quantity': 'fas fa-minus bg-danger',
			'invoice-create': 'fas fa-file-invoice bg-green',
			'client-create': 'fas fa-user-tie bg-success',
			'invoice-delete': 'fas fa-file-invoice bg-danger',
			'client-delete': 'fas fa-user-tie bg-danger',
			'invoice-email': 'fas fa-envelope-open-text bg-info',
			'invoice-change-status': 'fas fa-file-invoice-dollar bg-info',
		};
	}

	Activity.TimelineSectionItem = TimelineSectionItem;

	return Activity;
}(Activity || (Activity = {})));
