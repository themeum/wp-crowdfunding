import React, { Component } from 'react';
import InputRange from 'react-input-range';

const RenderField = (props) => {

    const { input, meta: { touched, error }, item } = props;

    switch(item.type) {
        case 'text':
            return (
                <input {...input} type={item.type} placeholder={item.placeholder}/>
            );
        case 'select':
            return (
                <div className="">
                    <select {...input}>
                        <option value="">{item.placeholder}</option>
                        {item.options.map( (option, index) =>
                            <option key={index} value={option.value}>{option.label}</option>
                        )}
                    </select>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'radio':
            return (
                <div className="">
                    {item.options.map( (option, index) =>
                        <label key={index} className="radio-inline">
                            <input {...input} type={item.type} /> {option.label} <span>{option.desc}</span>
                        </label>
                    )}
                </div>
            );
        case 'checkbox':
            return (
                <div className="">
                    {item.options.map( (option, index) =>
                        <label key={index} className="checkbox-inline">
                           <input {...input} type={item.type} /> {option.label}
                        </label>
                    )}
                </div>
            );
        case 'tags':
            return (
                <div className="">
                    <input type='text' defaultValue='Sample Sub Title' />
                    <div className=''>
                        {item.options.map( (option, index) =>
                            <span key={index}>+ {option.label}</span>
                        )}
                    </div>
                </div>
            );
        case 'file':
            return (
                <div className="">
                    <button dangerouslySetInnerHTML={{__html: item.button}} />
                </div>
            );
        case 'video_link':
            return (
                <div className="">
                    <input {...input} type="text"/>
                    <button dangerouslySetInnerHTML={{__html: item.button}} />
                </div>
            );
        case 'range':
            return (
                <div className="">
                    <InputRange
                        minValue={0}
                        maxValue={20}/>
                    <div className="">{}</div>
                </div>
            );
        case 'amount_range':
            return (
                <div className="">
                    <InputRange
                        step={2}
                        minValue={0}
                        maxValue={20}/>
                    <div className="">{}</div>
                </div>
            );
        case 'recommended_amount':
            return (
                <div className="">
                    <div>
                        <span>{}</span>
                        <span>{}</span>
                        <span>{}</span>
                        <span>{}</span>
                    </div>
                    <InputRange
                        step={2}
                        minValue={0}
                        maxValue={20}/>
                    <div className="">{}</div>
                </div>
            );
        default:
            return '';
    }
}

export default RenderField;
