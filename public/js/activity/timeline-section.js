'use strict';

var Activity = (function (Activity) {
	class TimelineSection {
		constructor(timeline, date) {
			this.timeline = timeline;
			this.date = date;
			this.id = ++TimelineSection.ID;
			this.content = TimelineSection.TEMPLATE;
			this.items = [];
		}

		async createItems() {
			try {
				let response = await this.serverGetItemsData();

				for (let data of response) {
					this.items.push(new Activity.TimelineSectionItem(this, data));
				}

				if (this.items.length === 0) {
					this.items.push(new Activity.TimelineSectionItem(
						this,
						{ 'message': 'Nije bilo nikakvih aktivnosti.' },
						Activity.TimelineSectionItem.TEMPLATE_DUMMY
					));
				}
			}
			catch (error) {
				console.error(`TimelineSection::createItems() error.`);
			}
		}

		render() {
			let items = this.items.map(
				(item) => {
					return item.render();
				}
			);

			return this.content
				.replace(/\{id\}/, this.id)
				.replace(/\{time\}/, this.date.format('D. MMM Y.'))
				.replace(/\{items\}/g, items.join(`\n`));
		}

		async serverGetItemsData() {
			let start = Date.now();

			return new Promise((resolve, reject) => {
				axios.post(this.timeline.getRoute(), { 'date': this.date.format('Y-M-D') })
					.then((response) => {
						let ellapsed = Date.now() - start;

						if (ellapsed < TimelineSection.LOAD_AT_LEAST) {
							setTimeout(() => {
								resolve(response.data);
							}, TimelineSection.LOAD_AT_LEAST - ellapsed);

							return;
						}

						resolve(response.data);
					})
					.catch((error) => {
						reject(error);
					});
			});
		}

		getOffsetTop() {
			return $(`#${this.getId()}`).offset().top;
		}

		getPositionTop() {
			return $(`#${this.getId()}`).position().top;
		}

		scrollDocumentTo(time) {
			$(document.documentElement).animate({
				scrollTop: this.getOffsetTop()
			}, time ?? 400);
		}

		getId() {
			return `timeline-section-${this.id}`;
		}

		static ID = 0;
		static LOAD_AT_LEAST = 750;
		static TEMPLATE = `
			<div id="timeline-section-{id}" class="row">
				<div class="col-md-12">
					<div class="timeline">
						<div class="time-label">
							<span class="bg-gray">{time}</span>
						</div>
						{items}
					</div>
				</div>
			</div>
		`;

	}

	Activity.TimelineSection = TimelineSection;

	return Activity;
}(Activity || (Activity = {})));
