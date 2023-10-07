import React, { useEffect, useState} from "react";

const FormSettings = () => {
    const defaultFieldValues = [];
    defaultFieldValues['number_of_rows_in_table'] = 5;
    defaultFieldValues['date_format'] = 'human_readable';
    defaultFieldValues['emails'] = 'ashlinrejo1@gmail.com'//ashlinReact.settings.email;

    const [fieldErrors, setFieldErrors] = useState([]);
    const [inputFields, setInputFields] = useState(defaultFieldValues);

    /**
     * Handle on-change event for field number_of_rows_in_table.
     *
     * @param event object
     * @return number
    * */
    function handleNumberOfRowsInputOnChange(event) {
        let value   = event.target.value;
        if( '' === value.trim() || null === value.trim() ){
            value   = '';
            updateError( 'number_of_rows_in_table', ashlinReact.settings.error_enter_number_between_one_and_five );
        } else if( Number(value) < 1 ) {
            value   = 1;
        } else if(Number(value) > 5) {
            value   = 5;
        }
        return value;
    }

    /**
     * Handle on-change event.
     *
     * @param event object
     * @param fieldName string
     * */
    function handleOnChange(event, fieldName) {
        let value = event.target.value;
        updateError( fieldName, '' );
        switch (fieldName){
            case 'number_of_rows_in_table':
                value = handleNumberOfRowsInputOnChange(event);
                break;
            default:
                break;
        }
        let updateInputFields = { ...inputFields, [fieldName]: value };
        setInputFields(updateInputFields);
    }

    /**
     * Handle on-change event for email fields.
     *
     * @param event object
     * @param index number
     * */
    function handleEmailOnChange(event, index) {
        let splitedEmails = inputFields['emails'].split(',');
        let value = event.target.value;
        if( '' === value.trim() || null === value.trim() ){
            value   = '';
            updateError( 'email_' + index, ashlinReact.settings.error_enter_an_email );
        } else {
            updateError( 'email_' + index, '' );
        }
        splitedEmails[index] = value;
        updateInputFieldState( 'emails', splitedEmails.toString() );
    }

    /**
     * Add an email field.
     * */
    function addEmailField(){
        let splitedEmails = inputFields['emails'].split(',');
        splitedEmails.push('');
        updateInputFieldState( 'emails', splitedEmails.toString() );
    }

    /**
     * Remove an email fields.
     *
     * @param index number
     * */
    function removeEmailField(index){
        let splitedEmails = inputFields['emails'].split(',');
        delete splitedEmails[index];
        splitedEmails = splitedEmails.filter( ( function() { return true; } ) );
        updateInputFieldState( 'emails', splitedEmails.toString() );
    }

    /**
     * Update state for Input fields.
     *
     * @param key string
     * @param value string
     * */
    function updateInputFieldState(key, value){
        let updateInputFields = { ...inputFields, [key]: value };
        setInputFields(updateInputFields);
    }

    /**
     * Save form
    * */
    function saveSettings(){}

    /**
     * Update error state
     *
     * @param fieldName string
     * @param value any
    * */
    function updateError(fieldName, value) {
        let updatedErrors = { ...fieldErrors, [fieldName]: value };
        setFieldErrors(updatedErrors);
    }

    /**
     * Verify the field have validation error
     *
     * @param fieldName string
     * @return boolean
     * */
    function hasError(fieldName) {
        if(undefined !== fieldErrors[fieldName] && '' !== fieldErrors[fieldName]){
            return true;
        }
        return false;
    }

    useEffect(() => {}, []);

    return (
        <form className="ashlin-react-settings-from">
            <div className="ashlin-react-form-row">
                <div className="ashlin-react-form-label">
                    <label htmlFor="ashlin-react-number-of-rows-in-table">{ ashlinReact.settings.number_of_rows_in_table_text }</label>
                    <p className="ashlin-react-form-label-desc">{ ashlinReact.settings.number_of_rows_in_table_desc_text }</p>
                </div>
                <div className="ashlin-react-form-field">
                    <input type="number" min="1" max="5" id="ashlin-react-number-of-rows-in-table" name="number_of_rows_in_table"
                           className={ "ashlin-react-form-input" + ( true === hasError( 'number_of_rows_in_table' ) ? ' error': '' ) }
                           value={ inputFields['number_of_rows_in_table'] }
                           onChange={ (event) => { handleOnChange( event, 'number_of_rows_in_table'); } } />
                    { true === hasError( 'number_of_rows_in_table' ) && (
                        <p className="ashlin-react-form-field-error">{ fieldErrors['number_of_rows_in_table'] }</p>
                    ) }
                </div>
            </div>

            <div className="ashlin-react-form-row">
                <div className="ashlin-react-form-label">
                    <label htmlFor="ashlin-react-date-format">{ ashlinReact.settings.date_format_text }</label>
                    <p className="ashlin-react-form-label-desc">{ ashlinReact.settings.date_format_desc_text }</p>
                </div>
                <div className="ashlin-react-form-field" id="ashlin-react-date-format">
                    <label htmlFor="ashlin-react-date-format-1">
                        <input type="radio" id="ashlin-react-date-format-1" name="date_format" value="human_readable"
                               onChange={ (event) => { handleOnChange( event, 'date_format'); } }
                               checked={ 'human_readable' === inputFields['date_format'] } />
                        { ashlinReact.settings.date_format_human_readable_text }
                    </label> <span className="ashlin-react-preview">{ ashlinReact.settings.human_readable_format_preview_text }</span>
                    <br /><br />
                    <label htmlFor="ashlin-react-date-format-2">
                        <input type="radio" id="ashlin-react-date-format-2" name="date_format" value="unix_timestamp"
                               onChange={ (event) => { handleOnChange( event, 'date_format'); } }
                               checked={ 'unix_timestamp' === inputFields['date_format'] } />
                        { ashlinReact.settings.date_format_unix_timestamp_text }
                    </label>
                    <span className="ashlin-react-preview">{ ashlinReact.settings.timestamp_preview_text }</span>
                    { true === hasError( 'date_format' ) && (
                        <p className="ashlin-react-form-field-error">{ fieldErrors['date_format'] }</p>
                    ) }
                </div>
            </div>

            <div className="ashlin-react-form-row">
                <div className="ashlin-react-form-label">
                    <label htmlFor="ashlin-react-email-0">{ ashlinReact.settings.email_text }</label>
                    <p className="ashlin-react-form-label-desc">{ ashlinReact.settings.email_desc_text }</p>
                </div>
                <div className="ashlin-react-form-field">
                    {inputFields['emails'].split(',').map((email,  index) => (
                        <div className="ashlin-react-email-block">
                            <input type="text" id={ "ashlin-react-email-" + index } name={ "email_" + index }
                                   className={ "ashlin-react-form-input" + ( true === hasError( "email_" + index ) ? ' error': '' ) }
                                   value={ email }
                                   onChange={ (event) => { handleEmailOnChange( event, index); } } />
                            { 1 !== inputFields['emails'].split(',').length && (
                                <button type="button" className="ashlin-react-remove-email-btn ashlin-react-btn" onClick={ (event) => { removeEmailField( index ); } }>X</button>
                            ) }
                            { true === hasError( "email_" + index ) && (
                                <p className="ashlin-react-form-field-error">{ fieldErrors["email_" + index] }</p>
                            ) }
                        </div>
                    ))
                    }
                    { inputFields['emails'].split(',').length < 5 && (
                        <button type="button" className="ashlin-react-add-email-btn ashlin-react-btn" onClick={addEmailField}>{ashlinReact.settings.add_email_button_text}</button>
                    ) }
                </div>
            </div>

            <div className="ashlin-react-form-btn-block">
                <button type="button" className="ashlin-react-save-btn ashlin-react-btn" onClick={saveSettings}>{ashlinReact.settings.save_button_text}</button>
            </div>
        </form>
    );
};
export default FormSettings;