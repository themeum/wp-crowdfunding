import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { fetchUser, saveUserData, fetchCountries } from '../actions/userAction';
import { decodeEntities } from "../helper";
import ProfileEditForm from '../components/profileEditForm';
import SocialForm from '../components/socialForm';
import Header from '../components/header';
import Skeleton from "../components/skeleton";

class ProfileSettings extends Component {
	constructor (props) {
        super(props);
        this.state = { profileEdit: false };
        this.toggleEdit = this.toggleEdit.bind(this);
        this.onClickSaveData = this.onClickSaveData.bind(this);
    }

    componentDidMount() {
        const { user, countries } = this.props;
        if( !user.loaded ) {
            this.props.fetchUser();
        }
        if( !countries.loaded ) {
            this.props.fetchCountries();
        }
    }

    componentDidUpdate(prevProps, prevState) {
        const { saveReq, error } = this.props.user;
        if ( saveReq !== prevProps.user.saveReq ) {
            if( saveReq == 'complete' ) {
                alert( 'data saved' );
            }
            if( saveReq == 'error' ) {
                alert( error );
            }
        }
    }

    toggleEdit() {
        this.setState({
            profileEdit: !this.state.profileEdit
        });
    }

    onClickSaveData( data ) {
        this.props.saveUserData( data );
    }

	render() {
        const { profileEdit } = this.state;
        const { loading, data } = this.props.user;
        const cLoading = this.props.countries.loading;
        const countries = this.props.countries.data;

        if( loading || cLoading ) {
            return (
                <Skeleton />
            );
        };

        if( profileEdit ) {
            return(
                <ProfileEditForm
                    data={ data }
                    countries={ this.props.countries.data }
                    toggleEdit={ this.toggleEdit }
                    onClickSaveData={ this.onClickSaveData }/>
            );
        }

        return (
            <Fragment>
                <Header title="Profile" />
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-profile">
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
                                                {decodeEntities(countries[data.profile_country])}
                                            </span>
                                        ) : <i style={{color: "#D6D6E7"}}>Not added</i>
                                    }
                                </div>
                                <button className="wpcf-profile-edit-btn" aria-label="Edit Profile" onClick={ this.toggleEdit }><span className="fas fa-pen"></span></button>
                            </div>
                        </div>
                    </div>

                    {/*<div className="wpcf-dashboard-item-wraper wpcf-myprofile-deactive">
                        <div>
                            <h5>Deactivated your Account</h5>
                            <div className="wpcf-myprofile-country">
                                {
                                    data.profile_country ? (
                                        <span>
                                            <img src={WPCF.assets + "images/flags/" + data.profile_country + ".png"} alt=""/>
                                            {decodeEntities(countries[data.profile_country])}
                                        </span>
                                    ) : <i style={{color: "#D6D6E7"}}>Country not added</i>
                                }
                            </div>
                        </div>
                        <div>
                            <button className="wpcf-btn wpcf-btn-outline wpcf-btn-success wpcf-btn-round">Yes</button>
                        </div>
                    </div>*/}

                    <SocialForm data={ data } onClickSaveData={ this.onClickSaveData }/>
                </div>
            </Fragment>
        )
	}
}


const mapStateToProps = state => ({
    user: state.user,
    countries: state.countries
})

export default connect( mapStateToProps, { fetchUser, saveUserData, fetchCountries } )(ProfileSettings);
