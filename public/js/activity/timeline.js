'use strict';

var Activity = (function (Activity) {
	class Timeline {
		constructor(container, route, date, step) {
			this.container = $(container);
			this.route = route;
			this.date = (typeof (date) === 'undefined') ? moment() : moment(date);
			this.step = step ?? 1;
			this.sections = [];
		}

		async createSection() {
			let section = new Activity.TimelineSection(this, this.date.clone());

			this.date.subtract(this.step, 'days');

			await section.createItems();

			let content = section.render();

			this.sections.push(section);
			this.container.append(content);

			return section;
		}

		getRoute() {
			return this.route;
		}
	}

	Activity.Timeline = Timeline;

	return Activity;
}(Activity || (Activity = {})));
