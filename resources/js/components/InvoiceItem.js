import React, { Component } from 'react'

export class InvoiceItem extends Component {
	constructor(props) {
		super(props);

		this.onDelete = this.onDelete.bind(this);
	}

	onDelete() {
		this.props.onDelete(this.props.index);
	}

	render() {
		return (
			<div>
				Hello!
				<div>
					<button onClick={this.onDelete}>Delete</button>
				</div>
			</div>
		)
	}
}
