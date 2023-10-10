import React from 'react';

/**
 * To display success message
 *
 * @param {string} message Message to display.
 * @return Element
 */
const SuccessMessage = ( { message } ) => {
	return (
		<div className="notice notice-success">
			<p>{ message }</p>
		</div>
	);
};
export default SuccessMessage;
