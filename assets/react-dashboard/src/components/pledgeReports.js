import React from 'react';

function getStatusColor(status) {
    switch( status ) {
        case 'cancelled':
        case 'failed':
            return '#c91818';
        case 'completed':
            return 'var(--wpcf-success-color)';
        default:
            return 'var(--wpcf-primary-color)';
    }
}

export default (props) => {
    const { pledges } = props;

    if( typeof pledges == "undefined" || pledges.length === 0 ) {
        return <div>Data not found</div>
    }

    return (
        <table className="wpcf-report-table">
            <thead>
            <tr>
                <td>Name</td>
                <td>Country</td>
                <td>Date</td>
                <td>Pledged</td>
                <td>Pledged (%)</td>
                <td>Status</td>
                {/* <td>Email</td> */}
            </tr>
            </thead>
            <tbody>
            { pledges.map( (item, index) =>
                <tr key={index}>
                    <td>{ item.name }</td>
                    <td>{ item.country }</td>
                    <td>{ item.date }</td>
                    <td dangerouslySetInnerHTML={{__html: item.pledge}} />
                    <td>{ item.percent }%</td>
                    <td
                        style={{
                            color: getStatusColor( item.status),
                            textTransform: 'uppercase'
                        }}>{ item.status__ }</td>
                    {/* <td><i className="fa fa-envelope"></i></td> */}
                </tr>
            ) }
            </tbody>
        </table>
    )
};
