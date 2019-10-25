import React from 'react';
import DatePicker from './DatePicker';
import InputRange from 'react-input-range';
import 'react-input-range/lib/css/index.css';

const defaultProps = {
    addTag: () => {},
    uploadFile: () => {},
    onChangeSelect: () => {},
    removeArrValue: () => {},
    fieldValue: '',
};

export default (_props) => {
    const props = {...defaultProps, ..._props};
    const { input, meta: { touched, error }, item, addTag, onChangeSelect, uploadFile, removeArrValue, fieldValue} = props;
    
    switch (item.type) {
        case 'text':
        case 'email':
        case 'number':
            return (
                <div className={item.class}>
                    <input {...input} type={item.type} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'textarea':
            return (
                <div className={item.class}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'select':
            return (
                <div className={item.class}>
                    <select {...input} onChange={(e) => { input.onChange(e); onChangeSelect(e); }}>
                        <option value="">{item.placeholder}</option>
                        {item.options.map((option, index) =>
                            <option key={index} value={option.value} dangerouslySetInnerHTML={{ __html: option.label }}/>
                        )}
                    </select>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'radio':
            return (
                <div className={item.class}>
                    {item.options.map((option, index) =>
                        <label key={index} className="radio-inline">
                            <input {...input} onChange={(e) => { input.onChange(e); if(input.name==`basic.goal_type`) {props.onChangeGoalType(e)} }} type={item.type} value={option.value} checked={option.value==fieldValue}/> {option.label} <span>{option.desc}</span>
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'checkbox':
            return (
                <div className={item.class}>
                    {item.options.map((option, index) =>
                        <label key={index} className="checkbox-inline">
                            <input {...input} type={item.type} value={option.value} checked={option.value==fieldValue}/> {option.label}
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'tags':
            return (
                <div className={item.class}>
                    {input.value && input.value.map( (item, index) =>
                        <div key={index} onClick={() => removeArrValue(index, input.name, input.value)}>{item.label}</div>
                    )}
                    <input 
                        type='text' 
                        onKeyDown={(e) => {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                                const value = e.target.value.toLowerCase();
                                const tag = { value, label: e.target.value };
                                addTag(tag, input.name, input.value);
                                e.target.value = '';
                            }
                        }} />
                    <div className={item.class}>
                        {item.options.map((tag, index) =>
                            <span key={index} onClick={() => addTag(tag, input.name, input.value)}>+ {tag.label}</span>
                        )}
                    </div>
                </div>
            );
        case 'image':
        case 'video':
            return (
                <div className={item.class}>
                    <div className="wpcf-form-attachments">
                        {input.value && input.value.map( (item, index) =>
                            <div key={index}>{item.name} <span onClick={() => removeArrValue(index, input.name, input.value)} className="fa fa-times"/></div>
                        )}
                    </div>
                    <button type="button" dangerouslySetInnerHTML={{ __html: item.button }} onClick={() => uploadFile(item.type, input.name, input.value, item.multiple)}/>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'range':
            const rangeVal = (typeof fieldValue == 'object') ? `${fieldValue.min} - ${fieldValue.max}` : fieldValue;
            return (
                <div className={item.class}>
                    <InputRange
                        minValue={item.minVal}
                        maxValue={item.maxVal}
                        value={input.value}
                        onChange={input.onChange}/>
                    <div>{rangeVal} <span dangerouslySetInnerHTML={{ __html: WPCF.currency }}/></div>
                </div>
            );
        case 'date':
            return (
                <div className={item.class}>
                    <DatePicker
                        name={input.name}
                        value={input.value}
                        onChange={input.onChange}
                        placeholder={item.placeholder}
                        format="yy-mm-dd"
                    />
                    {touched && error && <span>{error}</span>}
                </div>
            )
        default:
            return '';
    }
}

