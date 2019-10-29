import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import Header from '../components/contentHeader'

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
            <div>
                <Header title="My Profile" />
                <div className="wpcf-dashboard-content-inner">

                    <div className="wpcf-myprofile-primary-info">
                        <div className="wpcf-myprofile-avatar">
                            <img className="profile-form-img" src={ data.profile_image } alt="Profile Image" />
                        </div>
                        <div className="wpcf-myprofile-name-email">
                            <h4>{ data.profile_email1 }</h4>
                            { data.display_name }
                        </div>
                        <div className="wpcf-myprofile-country">
                            <h4>Country</h4>
                            { data.profile_country }
                        </div>
                    </div>

                    <div className="wpcf-myprofile-additional-info">
                        <ul>
                            <li><strong>Username: </strong> { data.username }</li>
                            <li><strong>Address: </strong> { data.profile_address }</li>
                            <li><strong>City: </strong> { data.profile_city }</li>
                            <li><strong>Postal Code: </strong> { data.profile_post_code }</li>
                        </ul>
                    </div>

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
