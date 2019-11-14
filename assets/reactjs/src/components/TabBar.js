import React from "react";

const TabBar = (props) => {
    const { current, steps } = props;
    return (
        <div className="wpcf-form-tabs-menu">
            {Object.keys(steps).map((key, i) => {
                const className = (i < current) ? 'prev' : (i == current) ? "active" : '';
                return (
                    <button key={i} className={`wpcf-tab-title ${className}`}><span>{i+1}</span> {steps[key]}</button>
                );
            })}
        </div>
    );
}

export default TabBar;
