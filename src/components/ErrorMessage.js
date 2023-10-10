import React from 'react';

/**
 * To display error message
 *
 * @param {string} message Message to display.
 * @return Element
 */
const ErrorMessage = ( { message } ) => {
	return (
		<div className="notice notice-error">
			<p>{ message }</p>
		</div>
	);
};
export default ErrorMessage;
