import React, { useMemo } from 'react';
import { RenderComponent } from '../render';
import { useTable, useSortBy, useGlobalFilter, useFilters, usePagination, useRowSelect, useRowState } from 'react-table';
import MOCK_DATA from './MOCK_DATA.json'
import { TEST_COLUMNS } from './columns';
import { GlobalFilter } from './Filters/GlobalFilter'
import { Checkbox } from './Checkbox';

function Table() {
	const columns = useMemo(() => TEST_COLUMNS, [])
	const data = useMemo(() => MOCK_DATA, [])

	const tableInstance = useTable(
		{
			columns,
			data,
		},
		useGlobalFilter,
		useFilters,
		useSortBy,
		usePagination,
		useRowSelect,
		(hooks) => {
			hooks.visibleColumns.push((columns) => {
				return [
					{
						id: 'selection',
						Header: ({ getToggleAllRowsSelectedProps }) => (
							<Checkbox {...getToggleAllRowsSelectedProps()} />
						),
						Cell: ({ row }) => (
							<Checkbox {...row.getToggleRowSelectedProps()} />
						)
					},
					...columns
				]
			})
		})
	const {
		getTableProps,
		getTableBodyProps,
		headerGroups,
		page,
		nextPage,
		previousPage,
		canNextPage,

		canPreviousPage,
		pageOptions,
		gotoPage,
		pageCount,
		setPageSize,
		prepareRow,
		selectedFlatRows,
		state,
		setGlobalFilter,
	} = tableInstance

	const { globalFilter, pageIndex, pageSize } = state

	return (
		<div>
			<table className="cs-table table" {...getTableProps()}>
				<thead>
					{
						headerGroups.map((headerGroup) => (
							<tr {...headerGroup.getHeaderGroupProps()}>
								{

									headerGroup.headers.map((column) => (
										<th {...column.getHeaderProps(column.getSortByToggleProps())}>
											{column.render('Header')}
											<span className="sort-icons">
												{/* Must setDisableSortBy in config file in order to prevent column sorting */}
												{column.canSort ? (column.isSorted
													? column.isSortedDesc
														? <i className="fas fa-sort-down"></i>
														: <i className="fas fa-sort-up"></i>
													: '') : ''}
											</span>
											<span>

												{column.canFilter ? column.render(<i className="fas fa-search"></i>) : null}
												{column.canFilter ? column.render('Filter') : null}
											</span>
										</th>
									))
								}
							</tr>
						))
					}
				</thead>

				<tbody {...getTableBodyProps()}>
					{
						page.map((row) => {
							prepareRow(row)
							return (
								<tr {...row.getRowProps()}>
									{
										row.cells.map((cell) => {
											return <td {...cell.getCellProps()}>
												{cell.render('Cell')}
											</td>
										})
									}
								</tr>
							)
						})
					}
				</tbody>
			</table>
			<div>
				<span>
					Strana{' '}
					<strong>
						{pageIndex + 1} od {pageOptions.length}
					</strong>
				</span>
				<span>
					Idi na stranu: {' '}
					<input
						type="number"
						defaultValue={pageIndex + 1}
						onChange={e => {
							const pageNumber = e.target.value ? Number(e.target.value) - 1 : 0
							gotoPage(pageNumber)
						}}
						style={{ width: '50px' }}
					/>
				</span>
				<button onClick={() => gotoPage(0)} disabled={!canPreviousPage}>Prva</button>
				<button onClick={() => previousPage()} disabled={!canPreviousPage}>Prethodna</button>
				<button onClick={() => nextPage()} disabled={!canNextPage}>Sledeća</button>
				<button onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}>Poslednja</button>
				<select value={pageSize} onChange={e => setPageSize(Number(e.target.value))}>
					{
						[5, 10, 20, 50, 100].map(pageSize => {
							return <option key={pageSize} value={pageSize}>Pokazi: {pageSize}</option>
						})
					}
				</select>
			</div>
			<pre>
				<code>
					{
						JSON.stringify(
							{
								selectedFlatRows: selectedFlatRows.map((row) => row.original)
							},
							null,
							2
						)
					}
				</code>
			</pre>
		</div>
	)

}

export default Table


RenderComponent(Table, 'test')
