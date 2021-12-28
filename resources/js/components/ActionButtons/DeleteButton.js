import React from 'react';
import { ACTIONS } from '../ClientListTableComponent/ClientListTable'

export function DeleteButton({ ...props }) {
	// let { id, dispatch, onOpen } = props;
	let { id, setPopupIsOpen, setClickedId } = props;
	let clickedId = parseInt(id);
	return (
		<button className="cs-btn cs-delete"
			onClick={(e) => {
				setClickedId(clickedId);
				setPopupIsOpen(true);
			}}
		><i className="fas fa-trash "></i></button>
	)
}