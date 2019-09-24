



const validate = values => {
    const errors = {}
    if (!values.category) {
        errors.category = 'Required'
    }
    return errors
}

export default validate