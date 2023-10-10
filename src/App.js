import React from 'react';
import { HashRouter as Router, Routes, Route } from 'react-router-dom';
import Tabs from './components/Tabs';
import Table from './pages/Table';
import Graph from './pages/Graph';
import Settings from './pages/Settings';

/* global ashlinReact */

/**
 * App main file.
 *
 * @return Element
 */
const App = () => {
	return (
		<Router>
			<div className="ashlin-react-container">
				<h1 className="wp-heading-inline">
					{ ashlinReact.title_text }
				</h1>
				<Tabs />
				<div className="ashlin-react-content">
					<Routes>
						<Route exact path="/" element={ <Table /> } />
						<Route path="graph" element={ <Graph /> } />
						<Route path="settings" element={ <Settings /> } />
					</Routes>
				</div>
			</div>
		</Router>
	);
};
export default App;
