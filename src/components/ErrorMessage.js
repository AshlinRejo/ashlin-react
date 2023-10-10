import React from "react";

const ErrorMessage = ({message}) => {
    return (
        <div className="notice notice-error">
            <p>
               { message }
            </p>
        </div>
    );
};
export default ErrorMessage;