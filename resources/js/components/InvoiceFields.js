import React, { Component } from 'react'

const capitalize = (string) => {
	if (typeof string !== 'string') {
		return '';
	}

	return string.charAt(0).toUpperCase() + string.slice(1);
}

export class InvoiceFields extends Component {
	constructor(props) {
		super(props);

		this.data = JSON.parse(this.props.data);
		this.fields = [];

		for (let key in this.data) {
			const data = this.data[key];
			const method = this[`create${capitalize(data.type)}`];

			if (method) {
				method.call(this, data);
			}
		}
	}

	createInput(data) {
		this.fields.push(
			<input key={data.id} name={data.name} defaultValue={data.value} />
		);
	}

	createSelect(data) {
		this.fields.push(
			<select key={data.id} name={data.name} defaultValue={data.value}>
				{data.options.map((data) => {
					return (
						<option key={data.id} value={data.id}> {}
							{data.value}
						</option>
					);
				})}
			</select>
		);
	}

	render() {
		return (
			<>
				{this.fields}
			</>
		)
	}
}
