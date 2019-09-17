
export default (props) => {
    const { key, item, onChange } = props;
    switch(item.type) {
        case 'text':
            return (
                <input type="text" className="form-control" name={key} value={item.value} onChange={onChange}/>
            )
        case 'select':
            return (
                <select name={key} defaultValue={item.value}>
                    {item.options.map( option, index =>
                        <option key={index} value={option.value}>{option.label}</option>
                    )}
                </select>
            )
        case 'radio':
            return (
                <div className="">
                    {item.options.map( option, index =>
                        <label key={index} className="radio-inline">
                            <input type="radio" name={key} value={option.value}/> {option.label} <span>{option.desc}</span>
                        </label>
                    )}
                </div>
            )
        case 'checkbox':
            return (
                <div className="">
                    {item.options.map( option, index =>
                        <label key={index} className="checkbox-inline">
                            <input type="checkbox" name={key} value={option.value}/> {option.label}
                        </label>
                    )}
                </div>
            )
        case 'tags':
            return (
                <div className="">
                    <input type='text' defaultValue='Sample Sub Title' />
                    <div className=''>
                        {item.options.map( option, index =>
                            <span key={index}>+ {option.label}</span>
                        )}
                    </div>
                </div>
            )
        case 'file':
            return (
                <div className="">
                    <button>Upload </button>
                </div>
            )
        default:
            return '';
    }
}
