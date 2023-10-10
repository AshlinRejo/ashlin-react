import React, { useEffect, useState } from 'react';
import axios from 'axios';
import ErrorMessage from './ErrorMessage';
import SuccessMessage from './SuccessMessage';

/* global ashlinReact */

/**
 * To display Form settings
 *
 * @return Element
 */
const FormSettings = () => {
	const defaultFieldValues = {
		number_of_rows_in_table: 5,
		date_format: 'human_readable',
		emails: '',
	};

	const [ fieldErrors, setFieldErrors ] = useState( {} );
	const [ inputFields, setInputFields ] = useState( defaultFieldValues );
	const [ message, setMessage ] = useState( <></> );
	const [ buttonDisabled, setButtonDisabled ] = useState( false );
	const [ loading, setLoading ] = useState( true );

	/**
	 * Handle on-change event for field number_of_rows_in_table.
	 *
	 * @param {object} event Object of number field.
	 * @return number
	 */
	function handleNumberOfRowsInputOnChange( event ) {
		let value = event.target.value;
		if ( '' === value.trim() || null === value.trim() ) {
			value = '';
			setFieldErrors( {
				...fieldErrors,
				number_of_rows_in_table:
					ashlinReact.settings
						.error_enter_number_between_one_and_five,
			} );
		} else if ( 1 > Number( value ) ) {
			value = 1;
		} else if ( 5 < Number( value ) ) {
			value = 5;
		}
		return value;
	}

	/**
	 * Handle on-change event.
	 *
	 * @param {object} event     Object of HTML element.
	 * @param {string} fieldName Field name.
	 */
	function handleOnChange( event, fieldName ) {
		let value = event.target.value;
		setFieldErrors( { ...fieldErrors, [ fieldName ]: '' } );
		if ( 'number_of_rows_in_table' === fieldName ) {
			value = handleNumberOfRowsInputOnChange( event );
		}
		const updateInputFields = { ...inputFields, [ fieldName ]: value };
		setInputFields( updateInputFields );
	}

	/**
	 * Handle on-change event for email fields.
	 *
	 * @param {object} event Object of HTML element.
	 * @param {number} index Index of email field.
	 */
	function handleEmailOnChange( event, index ) {
		const splitedEmails = inputFields.emails.split( ',' );
		const value = event.target.value;
		splitedEmails[ index ] = value;
		setFieldErrors( { ...fieldErrors, [ 'email_' + index ]: '' } );
		setInputFields( { ...inputFields, emails: splitedEmails.toString() } );
	}

	/**
	 * Add an email field.
	 */
	function addEmailField() {
		const splitedEmails = inputFields.emails.split( ',' );
		splitedEmails.push( '' );
		setInputFields( { ...inputFields, emails: splitedEmails.toString() } );
	}

	/**
	 * Remove an email fields.
	 *
	 * @param {number} index Index of email field.
	 */
	function removeEmailField( index ) {
		let splitedEmails = inputFields.emails.split( ',' );
		delete splitedEmails[ index ];
		splitedEmails = splitedEmails.filter( function () {
			return true;
		} );
		setInputFields( { ...inputFields, emails: splitedEmails.toString() } );
	}

	/**
	 * Save form
	 *
	 * @param {object} event Object of HTML element.
	 */
	function saveSettings( event ) {
		event.preventDefault();
		setMessage( <></> );
		const validationStatus = validateFormFields();
		if ( true === validationStatus ) {
			setButtonDisabled( true );
			const data = new FormData();
			data.append( 'action', 'ashlin_react_save_settings' );
			data.append( '_ajax_nonce', ashlinReact._ajax_nonce );
			data.append( 'settings', JSON.stringify( inputFields ) );
			setLoading( true );
			// Post request
			axios
				.post( wp.ajax.settings.url, data )
				.then( function ( response ) {
					if ( true === response.data.success ) {
						setMessage(
							<SuccessMessage
								message={ response.data.data.message }
							></SuccessMessage>
						);
					} else {
						setMessage(
							<ErrorMessage
								message={ response.data.data.message }
							></ErrorMessage>
						);
						setFieldErrors( response.data.data.errors );
					}
				} )
				.catch( function ( error ) {
					if (
						error?.response?.data &&
						'string' === typeof error.response.data
					) {
						setMessage(
							<ErrorMessage
								message={ error.response.data }
							></ErrorMessage>
						);
					} else {
						setMessage(
							<ErrorMessage
								message={ error.message }
							></ErrorMessage>
						);
					}
				} )
				.finally( function () {
					setButtonDisabled( false );
					setLoading( false );
				} );
		}
	}

	/**
	 * Validate form fields
	 *
	 * @return boolean
	 */
	function validateFormFields() {
		let status = true;
		let error = {};
		// Validate number_of_rows_in_table
		const numberOfRows = inputFields.number_of_rows_in_table;
		if ( 1 > Number( numberOfRows ) || 5 < Number( numberOfRows ) ) {
			status = false;
			error = {
				...error,
				number_of_rows_in_table:
					ashlinReact.settings
						.error_enter_number_between_one_and_five,
			};
		}

		// Validate date_format
		const dateFormat = inputFields.date_format;
		const dateFormatAcceptedValues = [ 'human_readable', 'unix_timestamp' ];
		if ( false === dateFormatAcceptedValues.includes( dateFormat ) ) {
			status = false;
			error = {
				...error,
				date_format: ashlinReact.settings.error_invalid_input,
			};
		}

		// Validate emails
		const emails = inputFields.emails;
		// Accepts only string, numbers and characters like ._-
		const validRegex =
			/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
		const splitedEmails = emails.split( ',' );
		splitedEmails.forEach( ( email, index ) => {
			if ( '' === email.trim() || null === email.trim() ) {
				status = false;
				error = {
					...error,
					[ 'email_' + index ]:
						ashlinReact.settings.error_enter_an_email,
				};
			} else if ( ! email.match( validRegex ) ) {
				status = false;
				error = {
					...error,
					[ 'email_' + index ]:
						ashlinReact.settings.error_enter_valid_email,
				};
			}
		} );
		setFieldErrors( error );

		return status;
	}

	/**
	 * Verify the field has any validation error.
	 *
	 * @param {string} fieldName Name of the field
	 * @return boolean
	 */
	function hasError( fieldName ) {
		if (
			'undefined' !== typeof fieldErrors[ fieldName ] &&
			'' !== fieldErrors[ fieldName ]
		) {
			return true;
		}
		return false;
	}

	/**
	 * To get settings from DB
	 */
	function getSettings() {
		axios
			.get( wp.ajax.settings.url, {
				params: {
					action: 'ashlin_react_get_settings',
					_ajax_nonce: ashlinReact._ajax_nonce,
				},
			} )
			.then( function ( response ) {
				if ( true === response.data.success ) {
					setInputFields( response.data.data.settings );
				}
			} )
			.catch( function ( error ) {
				if (
					error?.response?.data &&
					'string' === typeof error.response.data
				) {
					setMessage(
						<ErrorMessage
							message={ error.response.data }
						></ErrorMessage>
					);
				} else {
					setMessage(
						<ErrorMessage message={ error.message }></ErrorMessage>
					);
				}
			} )
			.finally( function () {
				setLoading( false );
			} );
	}

	useEffect( () => {
		window.onload = () => {
			getSettings();
		};
	}, [] );

	return (
		<form className="ashlin-react-settings-from">
			<div
				className={
					'ashlin-react-loader' +
					( true === loading ? ' loading' : '' )
				}
			>
				<span className="ashlin-react-loader-span"></span>
			</div>
			<div id="ashlin-react-message">{ message }</div>
			<div className="ashlin-react-form-row">
				<div className="ashlin-react-form-label">
					<label htmlFor="ashlin-react-number-of-rows-in-table">
						{ ashlinReact.settings.number_of_rows_in_table_text }
					</label>
					<p className="ashlin-react-form-label-desc">
						{
							ashlinReact.settings
								.number_of_rows_in_table_desc_text
						}
					</p>
				</div>
				<div className="ashlin-react-form-field">
					<input
						type="number"
						min="1"
						max="5"
						id="ashlin-react-number-of-rows-in-table"
						name="number_of_rows_in_table"
						className={
							'ashlin-react-form-input' +
							( true === hasError( 'number_of_rows_in_table' )
								? ' error'
								: '' )
						}
						value={ inputFields.number_of_rows_in_table }
						onChange={ ( event ) => {
							handleOnChange( event, 'number_of_rows_in_table' );
						} }
					/>
					{ true === hasError( 'number_of_rows_in_table' ) && (
						<p className="ashlin-react-form-field-error">
							{ fieldErrors.number_of_rows_in_table }
						</p>
					) }
				</div>
			</div>

			<div className="ashlin-react-form-row">
				<div className="ashlin-react-form-label">
					<label htmlFor="ashlin-react-date-format">
						{ ashlinReact.settings.date_format_text }
					</label>
					<p className="ashlin-react-form-label-desc">
						{ ashlinReact.settings.date_format_desc_text }
					</p>
				</div>
				<div
					className="ashlin-react-form-field"
					id="ashlin-react-date-format"
				>
					<label htmlFor="ashlin-react-date-format-1">
						<input
							type="radio"
							id="ashlin-react-date-format-1"
							name="date_format"
							value="human_readable"
							onChange={ ( event ) => {
								handleOnChange( event, 'date_format' );
							} }
							checked={
								'human_readable' === inputFields.date_format
							}
						/>
						{ ashlinReact.settings.date_format_human_readable_text }
					</label>{ ' ' }
					<span className="ashlin-react-preview">
						{
							ashlinReact.settings
								.human_readable_format_preview_text
						}
					</span>
					<br />
					<br />
					<label htmlFor="ashlin-react-date-format-2">
						<input
							type="radio"
							id="ashlin-react-date-format-2"
							name="date_format"
							value="unix_timestamp"
							onChange={ ( event ) => {
								handleOnChange( event, 'date_format' );
							} }
							checked={
								'unix_timestamp' === inputFields.date_format
							}
						/>
						{ ashlinReact.settings.date_format_unix_timestamp_text }
					</label>
					<span className="ashlin-react-preview">
						{ ashlinReact.settings.timestamp_preview_text }
					</span>
					{ true === hasError( 'date_format' ) && (
						<p className="ashlin-react-form-field-error">
							{ fieldErrors.date_format }
						</p>
					) }
				</div>
			</div>

			<div className="ashlin-react-form-row">
				<div className="ashlin-react-form-label">
					<label htmlFor="ashlin-react-email-0">
						{ ashlinReact.settings.email_text }
					</label>
					<p className="ashlin-react-form-label-desc">
						{ ashlinReact.settings.email_desc_text }
					</p>
				</div>
				<div className="ashlin-react-form-field">
					{ inputFields.emails.split( ',' ).map( ( email, index ) => (
						<div className="ashlin-react-email-block" key={ index }>
							<input
								type="text"
								id={ 'ashlin-react-email-' + index }
								name={ 'email_' + index }
								className={
									'ashlin-react-form-input' +
									( true === hasError( 'email_' + index )
										? ' error'
										: '' )
								}
								value={ email }
								onChange={ ( event ) => {
									handleEmailOnChange( event, index );
								} }
							/>
							{ 1 !== inputFields.emails.split( ',' ).length && (
								<button
									type="button"
									className="ashlin-react-remove-email-btn ashlin-react-btn"
									onClick={ () => {
										removeEmailField( index );
									} }
								>
									X
								</button>
							) }

							{ true === hasError( 'email_' + index ) && (
								<p className="ashlin-react-form-field-error">
									{ fieldErrors[ 'email_' + index ] }
								</p>
							) }
						</div>
					) ) }
					{ 5 > inputFields.emails.split( ',' ).length && (
						<button
							type="button"
							className="ashlin-react-add-email-btn ashlin-react-btn"
							onClick={ addEmailField }
						>
							{ ashlinReact.settings.add_email_button_text }
						</button>
					) }
				</div>
			</div>

			<div className="ashlin-react-form-btn-block">
				<button
					type="button"
					disabled={ buttonDisabled }
					className="ashlin-react-save-btn ashlin-react-btn"
					onClick={ ( event ) => {
						saveSettings( event );
					} }
				>
					{ ashlinReact.settings.save_button_text }
				</button>
			</div>
		</form>
	);
};
export default FormSettings;
