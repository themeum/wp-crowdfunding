import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { removeArrValue  } from '../Helper';
import { FieldArray, reduxForm,  getFormValues, change as changeFieldValue } from 'redux-form';
import { RenderTeamFields } from './renderItems/Field';
import PageControl from './PageControl';

const formName = "campaignForm";
const sectionName = "team";
class Team extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedItem: 0,
		}
        this._addMember = this._addMember.bind(this);
        this._deleteMember = this._deleteMember.bind(this);
	}

	_addMember() {
		const { formValues: {team} } = this.props;
		this.props.changeFieldValue(formName, sectionName, [...team, {}]);
		this.setState({selectedItem: team.length-1});
	}
	
	_deleteMember(index) {
		const { formValues: {team} } = this.props;
		const selectedItem = (index==0) ? 0 : index-1;
		const values = removeArrValue(team, index);
		this.setState({selectedItem});
		this.props.changeFieldValue(formName, sectionName, values);
	}

	render() {
		const { selectedItem } = this.state;
		const { teamFields, formValues: {team}, handleSubmit, current, prevStep } = this.props;
		return (
			<div className="row">
                <div className='col-md-7'>
					<form onSubmit={handleSubmit}>
						<div className="wpcf-accordion-wrapper">
							<div className="wpcf-accordion">
								<div className="wpcf-accordion-title active">
									Create Rewards
								</div>
								<div className='wpcf-accordion-details'>
									<form>
										<FieldArray
											name={sectionName}
											values={team}
											teamFields={teamFields}
											selectedItem={selectedItem}
											component={RenderTeamFields}/>
										<button type="button" onClick={() => this._addMember()}><span className="fa fa-plus"/> Add More Member</button>
									</form>
								</div>
							</div>
						</div>
						<div className="wpcf-team-members">
							<h3>Team Members</h3>
							{team && team.map((item, index) =>
								<div key={index} className={`wpcf-reward-item ${(selectedItem == index) ? 'active':''}`}>
									<p>{item.name}</p>
									<p>{item.email}</p>
									<span className="fa fa-trash" onClick={ () => this._deleteMember(index)}/>
									<span className="fa fa-pencil" onClick={ () => this.setState({selectedItem:index})}/>
								</div>
							)}
						</div>
						
						<PageControl 
							current={current}
							prevStep={prevStep}/>
					</form>
				</div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">Preview</div>
						<p>Nothing to See here</p>
                    </div>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    teamFields: state.data.teamFields,
    formValues: getFormValues(formName)(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        getFormValues,
        changeFieldValue
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
    destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Team));
