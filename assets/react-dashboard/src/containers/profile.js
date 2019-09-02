import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';

class Profile extends Component {
	constructor (props) {
        super(props);
    }

    componentDidMount() {
        const { loaded } = this.props.user;
        if( !loaded ) {
            this.props.fetchUser();
        }
    }
    
	render() {
        const { loading, data } = this.props.user;
        if( loading ) { 
            return (
                <div>
                    Loading...
                </div>
            ) 
        };
        return (
            <div className="wpcf-dashboard-content">
                <h3>Profile</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div>
                        <img className="profile-form-img" src={ data.profile_image } alt="Profile Image" />
                        <p>{ data.profile_email1 } </p>
                        <p>{ data.profile_country } </p>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div className="wpcf-dashboard-profile-item">
                            <p>Username</p>
                            <p>{ data.username }</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>Address</p>
                            <p>{data.profile_address}</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>City</p>
                            <p>{data.profile_city}</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>Postal Code</p>
                            <p>{data.profile_post_code}</p>
                        </div>
                    </div>
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    user: state.user
})

export default connect( mapStateToProps, { fetchUser } )(Profile);