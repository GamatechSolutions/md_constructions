
import { InputColumnFilter, SelectColumnFilter } from '../Filters/ColumnFilter'
import React from 'react'

export const SELECT_CLIENT_COLUMNS = [
	{
		Header: 'Id',
		accessor: 'id',
		Filter: InputColumnFilter,
		disableSortBy: true,
		disableFilters: true,
	},
	{
		Header: 'Ime firme/klijenta',
		Filter: InputColumnFilter,
		accessor: (row) => {
			return (row.firm_name) ? row.firm_name : row.individual_name
		},
		disableFilters: true,
	},
	{
		id: 'unique_id',
		Header: 'PIB/JMBG',
		Filter: InputColumnFilter,
		accessor: (row) => {
			return (row.pib) ? row.pib : row.individual_id
		},
		disableFilters: true,
		disableSortBy: true,
	},
	{
		Header: 'Tip',
		accessor: 'client_type_label',
		Filter: SelectColumnFilter,
		disableSortBy: true,
	},
]
