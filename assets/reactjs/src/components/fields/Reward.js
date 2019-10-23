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
            {Object.keys(rewardFields).map( key =>
                <div key={key} className='wpcf-form-field'>
                    <div className='wpcf-field-title'>{rewardFields[key].title}</div>
                    <div className='wpcf-field-desc'>{rewardFields[key].desc}</div>
                    { rewardFields[key].type == 'form_group' ?
                        <div className="form-group">
                            {Object.keys(rewardFields[key].fields).map( field =>
                                <Field
                                    key={field}
                                    name={`${name}[${selectedItem}].${field}`}
                                    item={rewardFields[key].fields[field]}
                                    uploadFile={props.uploadFile}
                                    removeArrValue={props.removeArrValue}
                                    component={RenderField}
                                    validate={[rewardFields[key].fields[field].required ? required : notRequred]}/>
                            )}
                        </div>

                    : rewardFields[key].type == 'repeatable' ?
                        <FieldArray
                            name={`${name}[${selectedItem}].${key}`}
                            item={rewardFields[key]}
                            component={RenderRepeatableFields}/>
                    :
                        <Field
                            name={`${name}[${selectedItem}].${key}`}
                            item={rewardFields[key]}
                            uploadFile={uploadFile}
                            removeArrValue={removeArrValue}
                            component={RenderField}
                            validate={[rewardFields[key].required ? required : notRequred]}/>
                    }
                </div>
            )}
        </div>
    )
}
