import React, { useMemo, useState, useEffect } from 'react';
import { RenderComponent } from '../../render';
import { useTable, useSortBy, useGlobalFilter, useFilters, usePagination, useRowSelect, useRowState } from 'react-table';
import { INVOICE_PRODUCT_COLUMNS } from './InvoiceProductColumns';

import { GlobalFilter } from '../Filters/GlobalFilter'

import axios from 'axios';

function InvoiceProductTable(props) {

	const [columnData, setColumnData] = useState([])
	let selectedRowInput = document.getElementById('selected-product-json')

	useEffect(() => {
		const getData = async () => {
			const result = await axios.post(props.url)
			setColumnData(result.data)
		}
		getData();

	}, [])

	const columns = useMemo(() => INVOICE_PRODUCT_COLUMNS, [])
	const data = useMemo(() => columnData, [columnData])

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
		toggleAllRowsSelected,
		state,
		setGlobalFilter } = useTable(
			{
				columns,
				data,
			},
			useGlobalFilter,
			useFilters,
			useSortBy,
			usePagination,
			useRowSelect,
		)




	const { globalFilter, pageIndex, pageSize, selectedRowsId } = state

	return (
		<div>
			<div className="cs-table-top">
				<div className="cs-select-show-number form-group form-inline">
					<label className="pr-2" htmlFor="">Pokazi: {' '}</label>
					<select className="form-control" value={pageSize} onChange={e => setPageSize(Number(e.target.value))}>
						{
							[5, 10, 20, 50, 100].map(pageSize => {
								return <option key={pageSize} value={pageSize}>{pageSize}</option>
							})
						}
					</select>
					<span className="pl-2" >proizvoda</span>
				</div>
				<div className="cs-global-filter">
					<GlobalFilter filter={globalFilter} setFilter={setGlobalFilter} />
				</div>
			</div>

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
													: <i className="fas fa-sort"></i>) : ''}
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
						(page.length == 0) ? <tr className="cs-empty-table">
							<td >Nema rezultata...</td>
						</tr> : page.map((row) => {
							prepareRow(row)
							return (
								<tr {...row.getRowProps()}

									className={'cs-table-row ' + (row.isSelected ? 'cs-row-active' : '')}
									onClick={e => {
										toggleAllRowsSelected(false)
										row.toggleRowSelected()
										selectedRowInput.value = JSON.stringify(
											row.original
											,
											null,
											2
										)
									}}>
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
			<div className="cs-table-pagination">
				<div className="d-flex justify-content-between">
					<div>
						Strana{' '}
						<strong>
							{pageIndex + 1} od {pageOptions.length}
						</strong>
					</div>
					<div className="d-flex cs-pagination-buttons">
						<button onClick={() => gotoPage(0)} disabled={!canPreviousPage}><i className="fas fa-step-backward"></i></button>
						<button onClick={() => previousPage()} disabled={!canPreviousPage}><i className="fas fa-chevron-left"></i></button>
						<button onClick={() => nextPage()} disabled={!canNextPage}><i className="fas fa-chevron-right"></i></button>
						<button onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}><i className="fas fa-step-forward"></i></button>
					</div>
				</div>
			</div>
		</div>
	)

}

export default InvoiceProductTable


RenderComponent(InvoiceProductTable, 'modal-product-table')
