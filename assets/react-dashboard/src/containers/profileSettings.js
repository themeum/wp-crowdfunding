import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';

class ProfileSettings extends Component {
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
                        { ( data.img_src ) &&
                            <img className="profile-form-img" src={ data.img_src } alt="Profile Image" />
                        }
                        { ( data.profile_email1 ) &&
                            <p>{ data.profile_email1[0] } </p>
                        }
                        { ( data.profile_country ) &&
                            <p>{ data.profile_country[0] } </p>
                        }
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Username</span>
                            </div>
                            <div className="content">
                                { ( data.display_name ) &&
                                    <p>{ data.display_name }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Address</span>
                            </div>
                            <div className="content">
                                { ( data.profile_address ) &&
                                    <p>{ data.profile_address[0] }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>City</span>
                            </div>
                            <div className="content">
                                { ( data.profile_city ) &&
                                    <p>{ data.profile_city[0] }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Postal Code</span>
                            </div>
                            <div className="content">
                                { ( data.profile_postal_code ) &&
                                    <p>{ data.profile_postal_code[0] }</p>
                                }
                            </div>
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

export default connect( mapStateToProps, { fetchUser } )(ProfileSettings);