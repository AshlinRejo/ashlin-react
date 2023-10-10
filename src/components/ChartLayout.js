import React, { useEffect, useState } from 'react';
import {
	Chart as ChartJS,
	CategoryScale,
	LinearScale,
	PointElement,
	LineElement,
	Title,
	Tooltip,
	Legend,
} from 'chart.js';
import { Line } from 'react-chartjs-2';
import axios from 'axios';
import Loader from './Loader';

/* global ashlinReact */

/**
 * To display Chart
 *
 * @return Element
 */
const ChartLayout = () => {
	const [ labels, setLabels ] = useState( [] );
	const [ dataValues, setDataValues ] = useState( [] );
	const [ loading, setLoading ] = useState( true );

	ChartJS.register(
		CategoryScale,
		LinearScale,
		PointElement,
		LineElement,
		Title,
		Tooltip,
		Legend
	);
	const options = {
		responsive: true,
	};
	// const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
	const data = {
		labels,
		datasets: [
			{
				label: ashlinReact.graph.value_text,
				data: dataValues,
				borderColor: 'rgb(255, 99, 132)',
				backgroundColor: 'rgba(255, 99, 132, 0.5)',
			},
		],
	};

	/**
	 * Get graph data from DB
	 */
	function getGraphData() {
		axios
			.get( wp.ajax.settings.url, {
				params: {
					action: 'ashlin_react_get_data',
					_ajax_nonce: ashlinReact._ajax_nonce,
				},
			} )
			.then( function ( response ) {
				if ( true === response.data.success ) {
					const dates = [];
					const values = [];
					Object.values( response.data.data.graph ).forEach(
						( graph ) => {
							dates.push( graph.date );
							values.push( graph.value );
						}
					);
					setLabels( dates );
					setDataValues( values );
				}
			} )
			.finally( function () {
				setLoading( false );
			} );
	}

	useEffect( () => {
		if ( 'undefined' === typeof wp.ajax ) {
			window.onload = () => {
				getGraphData();
			};
		} else {
			getGraphData();
		}
	}, [] );

	return (
		<div className="ashlin-react-graph-container">
			<Loader loading={ loading }></Loader>
			<Line options={ options } data={ data } />
		</div>
	);
};
export default ChartLayout;
