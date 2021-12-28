import React from 'react'

export const InputColumnFilter = ({ column }) => {
	const { filterValue, setFilter } = column
	return (
		<div>
			<input
				value={filterValue || ''}
				onChange={(e) => setFilter(e.target.value)}
				onClick={(e) => e.stopPropagation()}
				className="cs-search-filter"
			/>
		</div>
	)
}


export function SelectColumnFilter({
	column: { filterValue, setFilter, preFilteredRows, id },
}) {
	// Calculate the options for filtering
	// using the preFilteredRows
	const options = React.useMemo(() => {
		const options = new Set()
		preFilteredRows.forEach(row => {
			options.add(row.values[id])
		})
		return [...options.values()]
	}, [id, preFilteredRows])

	// Render a multi-select box
	return (
		<select
			value={filterValue}
			onChange={e => {
				setFilter(e.target.value || undefined)
			}}
			className="cs-select-filter"
		>
			<option value="">Sve</option>
			{options.map((option, i) => (
				<option key={i} value={option}>
					{option}
				</option>
			))}
		</select>
	)
}

