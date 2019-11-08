
import React from 'react';
import { Field } from 'redux-form';
import { required, isEmail  } from '../../Helper';
import RenderField from './Single';

export default (props) => {
    const { selectedItem, teamFields, values, fields:{name} } = props;
    return (
        <div className="">
            {Object.keys(teamFields).map( key => {
                const field = teamFields[key];
                const fName = `${name}[${selectedItem}].${key}`;
                const value = values.length && values[selectedItem] ? values[selectedItem][key] : '';
                const validate = field.required ? [required] : [];
                if(field.type=='email') {
                    validate.push(isEmail);
                }
                return (
                    <div key={key} className='wpcf-form-field'>
                        <div className='wpcf-field-title'>{field.title}</div>
                        <div className='wpcf-field-desc'>{field.desc}</div>
                        <Field
                            item={field}
                            name={fName}
                            fieldValue={value}
                            validate={validate}
                            component={RenderField}/>
                    </div>
                )
            })}
        </div>
    )
}