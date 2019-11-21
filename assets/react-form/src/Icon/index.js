import React from 'react';

const SVG = (props) => {
    const {width = 26, height = 26} = props;
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox={`0 0 ${width} ${height}`}>
            {props.children}
        </svg>
    );
};

const PATH = (props) => {
    const {fill = "#ADAECF", fillRule = "nonzero", d, stroke} = props;
    return (
        <path className={fill ? 'has-fill ' : '' + stroke ? 'has-stroke' : ''} {...(fill && {fill})} {...(fillRule && {fillRule})} {...(d && {d})} {...(stroke && { stroke })}/>
    );
};

const Icon = (props) => {
    const {fill, fillRule, d, stroke} = props
    return (
        <span className="wpcf-svg-icon">
            <SVG>
                <PATH {...(fill && {fill})} {...(fillRule && {fillRule})} {...(d && {d})} {...(stroke && { stroke })}/>
            </SVG>
        </span>
    )
}

export {Icon as default, SVG, PATH};

