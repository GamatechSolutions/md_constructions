import React from 'react';
import Popup from 'reactjs-popup';

export const ConfirmationPopup = (props) => (
	<Popup
		open={props.isOpen}
		modal
		nested
		className='cs-modal-container'
	>

		{close => (
			<div className="cs-modal">
				<button className="close" onClick={() => {
					props.onCancel()
				}}>
					<i className="fas fa-times"></i>
				</button>
				<div className="header mb-2"> <h3>{props.title}</h3> </div>
				<div className="content my-3">
					{props.message}
				</div>
				<div className="actions">
					<button className="btn btn-danger" onClick={props.onConfirm}>Izbriši</button>
					<button className="btn btn-secondary" onClick={props.onCancel}>Otakaži</button>
				</div>
			</div>
		)}
	</Popup>
);


