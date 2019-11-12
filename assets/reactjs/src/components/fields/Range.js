import React, {Component} from 'react';

class Range extends Component{

    constructor(props){
        super(props);
        this.inputRef = React.createRef();
    }

    componentDidMount() {
        const {onChange, min = 0, max = 100, value = 0, values = [], step = 0} = this.props;
        const inputRef = this.inputRef.current;
        jQuery(inputRef).slider({
            step,
            min,
            max,
            ...(values.length ? {values, range: true} : {value}),
            slide: function (event, ui) {
                console.log(">>> Event >>>" + event);
                console.log(">>> UI >>>" + ui);
            }
        });
    }

    render() {
        const {name} = this.props
        return (
            <input
                name={name}
                type="text"
                ref={this.inputRef}
            />
        )
    }
}

export default Range;
