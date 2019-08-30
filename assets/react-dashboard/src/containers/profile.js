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
        const { loading, data, data:{ profile } } = this.props.user;
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
                        <img className="profile-form-img" src={ profile.image } alt="Profile Image" />
                        <p>{profile.email} </p>
                        <p>{profile.country} </p>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div className="wpcf-dashboard-profile-item">
                            <p>Username</p>
                            <p>{data.username}</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>Address</p>
                            <p>{profile.address}</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>City</p>
                            <p>{profile.city}</p>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <p>Postal Code</p>
                            <p>{profile.post_code}</p>
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