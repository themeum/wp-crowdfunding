import React from 'react';
import Icons from '../Icons';

const Icon = (props) => {
    const {fill, stroke, name, className = ''} = props;
    return (
        <span className={"wpcf-svg-icon " + className}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox={Icons[name].viewBox}>
                <path fillRule="nonzero" {...(fill && {fill})}  {...(name && {d: Icons[name].d})} {...(stroke && { stroke })}/>
            </svg>
        </span>
    )
}

export default Icon;
