import React from "react";
export default (props) => {
    const { current, steps } = props;
    return (
        <div className="wpcf-form-tabs-menu">
            {steps.map((step, i) => {
                return (
                    <button key={i} className={"wpcf-tab-title " + (i < current ? "prev" : "") + (i == current ? "active" : "")}><span>{i+1}</span> {step}</button>
                );
            })}
        </div>
    );
}
