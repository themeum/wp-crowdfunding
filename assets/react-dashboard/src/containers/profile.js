import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser, fetchCountries } from '../actions/userAction';
import { decodeEntities } from "../helper";
import Header from '../components/header';
import Skeleton from '../components/skeleton';

class Profile extends Component {
    componentDidMount() {
        const { user, countries } = this.props;
        if( !user.loaded ) {
            this.props.fetchUser();
        }

        if(!countries.loaded) {
            this.props.fetchCountries();
        }
    }

	render() {
        const { loading, data } = this.props.user;
        const cLoading = this.props.countries.loading;
        const countries = this.props.countries.data;

        if( loading || cLoading ) {
            return (
                <Skeleton />
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
                        <div className="wpcf-myprofile-data">
                            <div className="wpcf-myprofile-name-email">
                                <h4>{ data.display_name }</h4>
                                <span>{ data.user_email }</span>
                            </div>
                            <div className="wpcf-myprofile-country">
                                <h4>Country</h4>

                                {
                                    data.profile_country ? (
                                        <span>
                                        <img src={WPCF.assets + "images/flags/" + data.profile_country + ".png"} alt=""/>
                                            { decodeEntities(countries[data.profile_country]) }
                                    </span>
                                    ) : <i style={{color: "#D6D6E7"}}>Not added</i>
                                }
                            </div>
                        </div>

                    </div>

                    <div className="wpcf-myprofile-additional-info">
                        <ul>
                            <li><strong>Username: </strong> { data.username ? data.username : <i>Not added</i> }</li>
                            <li><strong>Address: </strong> { data.profile_address ? data.profile_address : <i>Not added</i> }</li>
                            <li><strong>City: </strong> { data.profile_city ? data.profile_city : <i>Not added</i> }</li>
                            <li><strong>Postal Code: </strong> { data.profile_post_code ? data.profile_post_code : <i>Not added</i> }</li>
                        </ul>
                    </div>
                </div>
            </div>
        )
	}
}

const mapStateToProps = state => ({
    user: state.user,
    countries: state.countries
})

export default connect( mapStateToProps, { fetchUser, fetchCountries } )(Profile);
