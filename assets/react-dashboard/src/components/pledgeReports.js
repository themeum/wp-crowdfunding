import React from 'react';

function getStatusColor(status) {
    switch( status ) {
        case 'cancelled':
        case 'failed':
            return '#c91818';
        case 'completed':
            return '#00a92f';
        default:
            return '#2b51a1';
    }
}

export default (props) => {
    const { pledges } = props;
    
    if( pledges.length === 0 ) {
        return <div>Data not found</div>
    }
    
    return (
        <div className="wpcf-dashboard-info-table-wrap">
            <table className="wpcf-dashboard-info-table">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Country</td>
                        <td>Date</td>
                        <td>Pledged</td>
                        <td>% of Pledged</td>
                        <td>Status</td>
                        <td>Email</td>
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
                            <td style={ {color: getStatusColor( item.status)} }>{ item.status__ }</td>
                            <td><i className="fa fa-envelope"></i></td>
                        </tr>
                    ) }
                </tbody>
            </table>
        </div>
    )
};