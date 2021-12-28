import React from 'react';
import ReactDOM from 'react-dom';

export function RenderComponent(Component, id) {
	const container = document.getElementById(id);

	if (container) {
		ReactDOM.render(
		<Component
			{...container.dataset}
		/>, container);
	}
}