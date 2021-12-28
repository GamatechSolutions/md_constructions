const capitalize = (string) => {
	if (typeof string !== 'string') {
		return '';
	}
	return string.charAt(0).toUpperCase() + string.slice(1);
}

class DynamicFieldFactory {
	static createText(field) {
		const DOMNode = $(`
			<input
				id="${field.name}"
				name="${field.name}"
				class="form-control"
				type="text"
				placeholder=""
				value="${field.default_value ?? ''}"
			>
		`);

		return DOMNode;
	}

	static createSelect(field) {
		const DOMNode = $(`
			<select
				id="${field.name}"
				name="${field.name}"
				class="form-control"
			>	
			</select>
		`);

		for (let key in field.source) {
			let value = field.source[key];
			let selected = (key == field.default_value);
			let option = $(`
				<option value="${key}" ${selected ? 'selected' : ''}>
					${value}
				</option>
			`);

			DOMNode.append(option);
		}

		return DOMNode;
	}

	static createDate(field) {
		let today = new Date();
		let day = String(today.getDate()).padStart(2, '0');
		let month = String(today.getMonth() + 1).padStart(2, '0');
		let year = today.getFullYear();

		const DOMNode = $(`
			<input
				id="${field.name}"
				name="${field.name}"
				class="form-control"
				type="date"
				value="${year}-${month}-${day}"
			/>
		`);

		return DOMNode;
	}

	static createCheckbox(field) {
		const DOMNode = $(`
			<input
				id="${field.name}"
				name="${field.name}"
				class="form-control"
				type="checkbox"
				placeholder=""
				value="true"
			>
		`);

		return DOMNode;
	}

	static createHidden(field) {
		const DOMNode = $(`
			<input
				id="${field.name}"
				name="${field.name}"
				class="form-control"
				type="hidden"
				placeholder=""
				value="${field.default_value ?? ''}"
			>
		`);

		return DOMNode;
	}
}

class DynamicFields {
	constructor(container, data) {
		this.container = container
		this.data = data;
		this.fields = {};

		this.createFields();
	}

	getFieldByName(name) {
		return this.fields[name];
	}

	createFields() {
		this.data.forEach((field) => {
			let callback = `create${capitalize(field.type)}`;

			if (DynamicFieldFactory.hasOwnProperty(callback)) {
				let DOMNode = DynamicFieldFactory[callback](field);
				let wrapper = $(`<div class="input-wrapper"></div>`)

				wrapper
					.append(DOMNode)
					.append(`<span class="error-message"></span>`);

				this.container.append(
					$(`
						<div class="form-group">
							<label>${field.label}</label>
						</div>
					`).append(wrapper)
				);
				this.fields[field.name] = {
					DOMNode,
					type: field.type
				};
			}
		});

		this.bindChangeEvents();
		this.checkFieldDependencies();
	}

	bindChangeEvents() {
		for (let key in this.fields) {
			let fieldDOMNode = this.fields[key].DOMNode;

			fieldDOMNode.on('change', (event) => {
				this.checkFieldDependencies();
			});
		}
	}

	checkFieldDependencies() {
		for (let id in this.data) {
			let data = this.data[id];
			let fieldDOMNode = this.fields[data.name].DOMNode;

			if (data.hasOwnProperty('dependencies')) {
				let show = true;

				fieldDOMNode.closest('.form-group').show();
				fieldDOMNode.removeClass('ignore-validation');
				fieldDOMNode.attr('name', data.name);

				for (let key in data.dependencies) {
					let {
						DOMNode,
						type
					} = this.fields[key];
					let value = data.dependencies[key];

					if (['text', 'select'].indexOf(type) >= 0) {
						show &= (DOMNode.val() == value);
					}
					else if (type === 'checkbox') {
						show &= DOMNode.is(':checked');
					}
					else {
						console.warn(`Field can only depend on "select", "text", "checkbox" type fields!`);
						continue;
					}
				}

				if (!show) {
					fieldDOMNode.closest('.form-group').hide();
					fieldDOMNode.addClass('ignore-validation');
					fieldDOMNode.removeAttr('name');
				}
			}
		}
	}
}