import React from 'react';
import { NavLink } from 'react-router-dom';

/* global ashlinReact */

/**
 * To display Tab
 *
 * @return Element
 */
const Tabs = () => {
	return (
		<div className="ashlin-react-tab">
			<NavLink to={ `/` } key={ 'table' }>
				{ ashlinReact.tab.table_text }
			</NavLink>
			<NavLink to={ `/graph` } key={ 'graph' }>
				{ ashlinReact.tab.graph_text }
			</NavLink>
			<NavLink to={ `/settings` } key={ 'settings' }>
				{ ashlinReact.tab.settings_text }
			</NavLink>
		</div>
	);
};
export default Tabs;
