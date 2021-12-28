import React, { Component } from 'react';
import { RenderComponent } from '../render';
import { InvoiceFields } from './InvoiceFields';
import { InvoiceItem } from './InvoiceItem';
export class Invoice extends Component {
	constructor(props) {
		super(props);

		this.index = 0;
		this.state = {
			items: {}
		};

		this.addItem = this.addItem.bind(this);
		this.onItemDelete = this.onItemDelete.bind(this);
	}

	addItem() {
		this.setState((state) => {
			let items = Object.assign({}, state.items);

			items[++this.index] = <InvoiceItem
				key={this.index}
				index={this.index}
				onDelete={this.onItemDelete}
			/>;

			return { items };
		});
	}

	onItemDelete(index) {
		this.setState((state) => {
			let items = Object.assign({}, state.items);

			delete items[index];

			return { items };
		});
	}

	render() {
		return (
			<>
				<div>
					<InvoiceFields data={this.props.fields} />
				</div>
				<div>
					{Object.keys(this.state.items).map((key) => {
						return this.state.items[key];
					})}
				</div>
				<div>
					<button onClick={this.addItem}>Add</button>
				</div>
			</>
		)
	}
}

RenderComponent(Invoice, 'invoice');
