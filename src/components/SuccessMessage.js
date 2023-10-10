import React from "react";

const SuccessMessage = ({message}) => {
    return (
        <div className="notice notice-success">
            <p>
               { message }
            </p>
        </div>
    );
};
export default SuccessMessage;