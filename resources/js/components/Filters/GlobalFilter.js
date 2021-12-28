import React, { useState } from 'react'
import { useAsyncDebounce } from 'react-table'

export const GlobalFilter = ({ filter, setFilter }) => {
	const [value, setValue] = useState(filter)

	const callOnChange = useAsyncDebounce(value => {
		setFilter(value || undefined)
	}, 400)
	return (
		<div className="form-group form-inline">
			<label className="pr-2" htmlFor="">Pretraži:{' '}</label>
			<input

				value={value || ''}
				onChange={(e) => {
					setValue(e.target.value)
					callOnChange(e.target.value)
				}
				}
				className="form-control" />
		</div>
	)
}
