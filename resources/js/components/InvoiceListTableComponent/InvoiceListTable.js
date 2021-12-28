import React, { useMemo, useState, useEffect } from 'react';
import { RenderComponent } from '../../render';
import {
	useTable,
	useSortBy,
	useGlobalFilter,
	useFilters,
	usePagination,
	useRowSelect,
	useRowState
} from 'react-table';

import { INVOICE_LIST_COLUMNS } from './InvoiceListColumns';

import { DeleteButton } from '../ActionButtons/DeleteButton'
import { ViewButton } from '../ActionButtons/ViewButton'
import { ConfirmationPopup } from '../popup/ConfirmationPopup'
import { GlobalFilter } from '../Filters/GlobalFilter'

import toastr from 'toastr'

import {
	isMobile
} from "react-device-detect";


function InvoiceListTable(props) {
	const [columnData, setColumnData] = useState([])
	const [popupIsOpen, setPopupIsOpen] = useState(false);
	const [clickedId, setClickedId] = useState(0);

	const onConfirm = () => {
		let newData = columnData.slice()
		let id = columnData[clickedId].id
		let url = props.deleteurl.replace('id', id)
		axios.post(url)
			.then((response) => {
				newData.splice(clickedId, 1)
				setColumnData(newData)
				setPopupIsOpen(false);
				toastr[response.data.type](response.data.message);

			})
			.catch((error) => {

			})
	}

	const onCancel = () => {
		setPopupIsOpen(false);
	}

	//let selectedRowInput = document.getElementById('selected-product-json')
	useEffect(() => {

		const getData = async () => {
			const result = await axios.post(props.jsonurl)
			setColumnData(result.data)
		}
		getData();

	}, [])





	const data = useMemo(() => columnData, [columnData])
	const columns = useMemo(() => INVOICE_LIST_COLUMNS, [])




	const tableInstance = useTable(
		{
			data,
			columns,
		},
		useGlobalFilter,
		useFilters,
		useSortBy,
		usePagination,
		useRowSelect
	)
	const {
		getTableProps,
		getTableBodyProps,
		allColumns,
		setHiddenColumns,
		initialState,
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

	let hiddenCols = allColumns.filter((col) => col.hiddenOnMobile);

	const { globalFilter, pageIndex, pageSize } = state

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
										<th {...column.getHeaderProps(column.getSortByToggleProps({ title: 'sortiraj' }))}>
											{column.render('Header')}
											<span className="sort-icons">
												{/* Must setDisableSortBy in config file in order to prevent column sorting */}
												{column.canSort ? (column.isSorted
													? column.isSortedDesc
														? <i className="fas fa-sort-down"></i>
														: <i className="fas fa-sort-up"></i>
													: <i className="fas fa-sort"></i>) : ''}
											</span>

											<span>
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
						(page.length == 0) ? <tr className="cs-empty-table">
							<td >Nema rezultata...</td>
						</tr> : page.map((row) => {
							prepareRow(row)
							return (
								<tr {...row.getRowProps()}>
									{
										row.cells.map((cell) => {
											return <td {...cell.getCellProps()}>
												{(cell.column.id == 'delete') ?
													<div className="cs-actions">
														<ViewButton url={props.url.replace('id', row.original.id)} />
														<DeleteButton
															id={row.id}
															setPopupIsOpen={setPopupIsOpen}
															setClickedId={setClickedId}
														/>
													</div>
													: cell.render('Cell')}
											</td>
										})
									}
								</tr>
							)
						})
					}
				</tbody>
			</table >
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
			<ConfirmationPopup
				title={'Brisanje fakture'}
				message={`Jeste li sigurni da želite da izbrišete fakturu iz
							baze podataka?`
				}
				isOpen={popupIsOpen}
				onConfirm={onConfirm}
				onCancel={onCancel}
			/>


		</div >
	)

}

export default InvoiceListTable


RenderComponent(InvoiceListTable, 'invoice-list-table')
