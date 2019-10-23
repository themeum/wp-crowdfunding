import React, { useState } from 'react';
import { Field, FieldArray } from 'redux-form';
import { required, notRequred  } from '../../Helper';
import RenderField from './Single';
import RenderRepeatableFields from './Repeatable';

export default (props) => {
    const { rewards, rewardTypes, onChangeType, selectedItem, rewardFields, uploadFile, removeArrValue, fields:{name} } = props;
    const [ changeType, setChangeType ] = useState(false);
    return (
        <div className="">
            { rewards[selectedItem] &&
                <div className='wpcf-form-field'>
                    <div className='wpcf-field-title'>
                        {rewardTypes[rewards[selectedItem].type].title}
                        <span onClick={() => setChangeType(true)}>Change</span>
                    </div>
                    { changeType && rewardTypes.map((item, index) =>
                        <label key={index} className="radio-inline">
                            <input type='radio' name="type" value={index} onClick={(e) => { onChangeType(e); setChangeType(false) }}/> {item.title}
                        </label>
                    )}
                </div>
            }
            {Object.keys(rewardFields).map( key => {
                const field = rewardFields[key];
                const fname = `${name}[${selectedItem}].${key}`;
                return (
                    <div key={key} className='wpcf-form-field'>
                        <div className='wpcf-field-title'>{field.title}</div>
                        <div className='wpcf-field-desc'>{field.desc}</div>
                        { field.type == 'form_group' ?
                            <div className="form-group">
                                {Object.keys(field.fields).map( key => {
                                    const gField = field.fields[key];
                                    const gName = `${name}[${selectedItem}].${key}`;
                                    return (
                                        <Field
                                            key={key}
                                            name={gName}
                                            item={gField}
                                            uploadFile={props.uploadFile}
                                            removeArrValue={props.removeArrValue}
                                            validate={[gField.required ? required : notRequred]}
                                            component={RenderField}/>
                                    )
                                })}
                            </div>

                        : field.type == 'repeatable' ?
                            <FieldArray
                                item={field}
                                name={fname}
                                component={RenderRepeatableFields}/>
                        :
                            <Field
                                item={field}
                                name={fname}
                                uploadFile={uploadFile}
                                removeArrValue={removeArrValue}
                                validate={[field.required ? required : notRequred]}
                                component={RenderField}/>
                        }
                    </div>
                )
            })}
        </div>
    )
}
