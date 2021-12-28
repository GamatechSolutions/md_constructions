
import { InputColumnFilter, SelectColumnFilter } from '../Filters/ColumnFilter'
import React from 'react'

export const INVOICE_LIST_COLUMNS = [
	{
		Header: 'Id',
		accessor: 'unique_number',
		Filter: InputColumnFilter,
		disableSortBy: true,
		disableFilters: true,
		hiddenOnMobile: false,
	},
	{
		id: 'client_name',
		Header: 'Ime firme/klijenta',
		Filter: InputColumnFilter,
		accessor: (row) => {
			return (row.firm_name) ? row.firm_name : row.individual_name
		},
		disableFilters: true,
		hiddenOnMobile: false,
	},
	{
		Header: 'Iznos',
		accessor: 'price',
		Filter: InputColumnFilter,
		disableFilters: true,
		hiddenOnMobile: true,
	},
	{
		Header: 'Tip',
		accessor: 'type',
		Filter: SelectColumnFilter,
		disableSortBy: true,
		hiddenOnMobile: true,
	},

	{
		Header: 'Status',
		accessor: 'status',
		Filter: SelectColumnFilter,
		disableSortBy: true,
		hiddenOnMobile: false,
	},
	{
		Header: 'Datum',
		accessor: 'issue_date',
		Filter: InputColumnFilter,
		disableFilters: true,
		hiddenOnMobile: true,
	},
	{
		Header: 'Akcije',
		id: 'delete',
		Filter: InputColumnFilter,
		disableFilters: true,
		disableSortBy: true,
		hiddenOnMobile: true,
	},
]
