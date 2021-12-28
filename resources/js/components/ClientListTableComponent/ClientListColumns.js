
import { InputColumnFilter, SelectColumnFilter } from '../Filters/ColumnFilter'
import React from 'react'


export const CLIENT_LIST_COLUMNS = [
	{
		Header: 'Id',
		accessor: 'id',
		Filter: InputColumnFilter,
		disableSortBy: true,
		disableFilters: true,
	},
	{
		id: 'client_name',
		Header: 'Ime firme/klijenta',
		Filter: InputColumnFilter,
		accessor: (row) => {
			return (row.firm_name) ? row.firm_name : row.individual_name
		},
		disableFilters: true,
	},
	{
		id: 'id_number',
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
	{
		Header: 'Akcije',
		id: 'delete',
		Filter: InputColumnFilter,
		disableFilters: true,
		disableSortBy: true,
	},
]
