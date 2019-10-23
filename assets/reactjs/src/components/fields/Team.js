
import React from 'react';
import { Field } from 'redux-form';
import { required, notRequred  } from '../../Helper';
import RenderField from './Single';

export default (props) => {
    const { selectedItem, teamFields, values, fields:{name} } = props;
    return (
        <div className="">
            {Object.keys(teamFields).map( key =>
                <div key={key} className='wpcf-form-field'>
                    <div className='wpcf-field-title'>{teamFields[key].title}</div>
                    <div className='wpcf-field-desc'>{teamFields[key].desc}</div>
                    <Field
                        name={`${name}[${selectedItem}].${key}`}
                        item={teamFields[key]}
                        fieldValue={ values.length && values[selectedItem] ? values[selectedItem][key] : ''}
                        validate={[teamFields[key].required ? required : notRequred]}
                        component={RenderField}/>
                </div>
            )}
        </div>
    )
}