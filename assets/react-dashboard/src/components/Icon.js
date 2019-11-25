import React from 'react';
import Icons from '../../../react-icon/Icons';

const Icon = (props) => {
  const {fill, stroke, name, className = '', tabIndex = '', onClick = ''} = props;
  return (
    <span className={"wpcf-svg-icon " + className} {...(tabIndex !== '' && {tabIndex})} {...(onClick !== '' && {onClick})}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox={Icons[name].viewBox}>
                <path fillRule="nonzero" {...(fill && {fill})}  {...(name && {d: Icons[name].d})} {...(stroke && { stroke })}/>
            </svg>
        </span>
  )
}

export default Icon;
