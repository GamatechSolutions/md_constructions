import React from 'react'
import { format } from 'date-fns'
import { InputColumnFilter } from './Filters/ColumnFilter'

export const TEST_COLUMNS = [
	{
		Header: 'Id',
		accessor: 'id',
		Filter: InputColumnFilter,
		disableSortBy: true,
	},
	{
		Header: 'First Name',
		accessor: 'first_name',
		Filter: InputColumnFilter,
		disableSortBy: true,
	},
	{
		Header: 'Last Name',
		accessor: 'last_name',
		Filter: InputColumnFilter,
	},
	{
		Header: 'Email',
		accessor: 'email',
		Filter: InputColumnFilter,
	},
	{
		Header: 'Gender',
		accessor: 'gender',
		Filter: InputColumnFilter,
		disableFilters: true,
	},
	{
		Header: 'Date',
		accessor: 'date',
		cell: ({ value }) => { return format(new Date(value, 'dd/MM/YYYY')) },
		Filter: InputColumnFilter,
	},
]