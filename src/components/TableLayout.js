import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Loader from './Loader';

/* global ashlinReact */

/**
 * To display Table
 *
 * @return Element
 */
const TableLayout = () => {
	const [ loading, setLoading ] = useState( true );
	const [ title, setTitle ] = useState( '' );
	const [ tableHeaders, setTableHeaders ] = useState( [] );
	const [ tableRows, setTableRows ] = useState( [] );
	const [ emails, setEmails ] = useState( [] );

	/**
	 * Get table data from DB
	 */
	function getTableData() {
		axios
			.get( wp.ajax.settings.url, {
				params: {
					action: 'ashlin_react_get_data',
					_ajax_nonce: ashlinReact._ajax_nonce,
					type: 'table',
				},
			} )
			.then( function ( response ) {
				if ( true === response.data.success ) {
					const data = response.data.data;
					setTitle( data.title );
					setTableHeaders( data.data.headers );
					setTableRows( data.data.rows );
					setEmails( data.emails );
				}
			} )
			.finally( function () {
				setLoading( false );
			} );
	}

	useEffect( () => {
		if ( 'undefined' === typeof wp.ajax ) {
			window.onload = () => {
				getTableData();
			};
		} else {
			getTableData();
		}
	}, [] );

	return (
		<div className="ashlin-react-table-container">
			<Loader loading={ loading }></Loader>
			<h3>{ title }</h3>
			<table className="ashlin-react-table">
				<thead>
					<tr>
						{ tableHeaders.map( ( header, index ) => (
							<th key={ index } className="ashlin-react-table-th">
								{ header }
							</th>
						) ) }
					</tr>
				</thead>
				<tbody className="ashlin-react-table-body">
					{ tableRows.map( ( row, index ) => (
						<tr key={ index }>
							<td className="ashlin-react-table-td">
								{ row.id }
							</td>
							<td className="ashlin-react-table-td">
								{ row.url }
							</td>
							<td className="ashlin-react-table-td">
								{ row.title }
							</td>
							<td className="ashlin-react-table-td">
								{ row.pageviews }
							</td>
							<td className="ashlin-react-table-td">
								{ row.date }
							</td>
						</tr>
					) ) }
				</tbody>
			</table>
			{ false === loading && (
				<>
					<h3>{ ashlinReact.table.emails_title_text }</h3>
					<div className="ashlin-react-table-emails-block">
						<ul>
							{ emails.map( ( email, index ) => (
								<li key={ index }>{ email }</li>
							) ) }
						</ul>
					</div>
				</>
			) }
		</div>
	);
};
export default TableLayout;
