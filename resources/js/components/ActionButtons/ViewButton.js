
import React from 'react'
export function ViewButton(props) {
	return (
		<a className="cs-btn cs-view"
			href={props.url}
		><i className="fas fa-eye "></i></a>
	)
}