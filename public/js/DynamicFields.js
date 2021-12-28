const nooperation = () => {};

class DynamicField {
	static ID = 0;

	constructor(container, data) {
		this.container = container;
		this.data = data;
		this.id = ++DynamicField.ID
		this.template = document.createElement('template');
		this.content = null;
		this.node = null;
		this.value = data.default_value;
		this.onChange = nooperation;
	}

	append() {
		this.template.innerHTML = this.render()
			.trim()
			.replace(/\s+/g, ' ')
			.replace(/(\t)|(\n)|(\r\n)/g, '');

		this.content = this.template.content;
		this.node = this.content.firstChild;

		this.connected();

		this.container.appendChild(this.content.firstChild);
	}

	render() {}
	connected() {}
	disconnected() {}
}

class Select extends DynamicField {
	constructor(container, data) {
		super(container, data);

		this.options = [];
	}

	render() {
		this.createOptions();

		return `
			<div>
				<label>${this.data.label}</label>
				<select id="select-${this.id}" ${this.data.name}>
					${this.options.join('')}
				</select>
			<div>
		`;
	}

	connected() {
		let select = this.content.getElementById(`select-${this.id}`);

		select.addEventListener('change', (event) => {
			this.value = select.value;

			this.onChange.call(this, this, event);
		});
	}

	createOptions() {
		if (!this.data.source) {
			return;
		}

		for (let value in this.data.source) {
			let text = this.data.source[value];
			let selected = (value == this.value);


			this.options.push(`
				<option value="${value}" ${selected ? 'selected' : ''}>
					${text}
				</option>
			`);
		}
	}
}

class RadioGroup extends DynamicField {
	constructor(container, data) {
		super(container, data);

		this.options = [];
	}

	render() {
		this.createOptions();

		return `
			<div>
				<label>${this.data.label}</label>
				${this.options.join('')}
			<div>
		`;
	}

	connected() {
		let options = this.content.querySelectorAll(`.option-${this.id}`);

		options.forEach((option) => {
			option.addEventListener('change', (event) => {
				this.value = option.value;

				this.onChange.call(this, this, event);
			});
		})
	}

	createOptions() {
		if (!this.data.source) {
			return;
		}

		for (let value in this.data.source) {
			let text = this.data.source[value];
			let checked = (value == this.value);

			this.options.push(`
				<input
					class="option-${this.id}"
					type="radio"
					name="${this.data.name}"
					value="${value}"
					${checked ? 'checked' : ''}
				/>
				<label>${text}</label>
			`);
		}
	}
}

class Input extends DynamicField {
	constructor(container, data) {
		super(container, data);

		this.options = [];
	}

	render() {

		return `
			<div>
				<label>${this.data.label}</label>
				<input id="input-${this.id}" name="${this.data.name}" value="${this.value}"/>
			<div>
		`;
	}

	connected() {
		let input = this.content.getElementById(`input-${this.id}`);

		input.addEventListener('input', (event) => {
			this.value = input.value;

			this.onChange.call(this, this, event);
		})
	}
}