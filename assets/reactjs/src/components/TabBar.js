import React from "react";
export default (props) => {
    const { current, steps } = props;
    return (
        <div className="row">
            <div className="col-md-12">
                <div className="wpcf-form-tab">
                    {steps.map((step, i) => {
                        return (
                            <div key={i} className={"wpcf-tab-title " + (i == current ? "active" : "")}>{step}</div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
