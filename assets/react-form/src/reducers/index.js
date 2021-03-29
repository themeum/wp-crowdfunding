import { multiIndex } from '../Helper';
import {
	FETCH_REQUEST_PENDING,
	FETCH_FORM_FIELDS_COMPLETE,
	FETCH_SUB_CATEGORIES_COMPLETE,
	FETCH_FORM_VALUES_PENDING,
	FETCH_FORM_VALUES_COMPLETE,
	//FETCH_STATES_COMPLETE,
	FIELD_SHOW_HIDE,
	VALIDATE_RECAPTCHA,
	SAVE_CAMPAIGN_PENDING,
	SAVE_CAMPAIGN_COMPLETE,
} from '../actions';

const initialValues = {
	basic: {
		media: [],
		funding_goal: 1,
		pledge_amount: { min: 1, max: 50000 },
		recommended_amount: 20,
	},
	story: [],
	rewards: [],
	team: [],
};

export default function (
	state = {
		postId: 0,
		totalRaised: 0,
		totalBackers: 0,
		saveDate: '',
		initialValues,
		loading: true,
		loaded: false,
		valReceived: true,
		saveReq: false,
		submit: false,
		submitData: {},
		validateRecaptcha: false,
	},
	action
) {
	switch (action.type) {
		case FETCH_REQUEST_PENDING:
			return {
				...state,
				loading: true,
				loaded: false,
			};

		case FETCH_FORM_FIELDS_COMPLETE:
			return {
				...state,
				...action.payload,
				loading: false,
				loaded: true,
			};

		case FETCH_SUB_CATEGORIES_COMPLETE:
			//case FETCH_STATES_COMPLETE:
			const res = action.payload;
			let basic_fields = { ...state.basic_fields };
			basic_fields[res.section][res.field].options = res.options;
			return {
				...state,
				basic_fields,
			};

		case FETCH_FORM_VALUES_PENDING:
			return {
				...state,
				valReceived: false,
			};

		case FETCH_FORM_VALUES_COMPLETE:
			const {
				postId,
				saveDate,
				totalRaised,
				totalBackers,
				values,
			} = action.payload;
			return {
				...state,
				valReceived: true,
				postId,
				saveDate,
				totalRaised,
				totalBackers,
				initialValues: values,
			};

		case FIELD_SHOW_HIDE:
			basic_fields = { ...state.basic_fields };
			let { field, show } = action.payload;
			field = multiIndex(basic_fields, field);
			field.show = show;
			return {
				...state,
				basic_fields,
			};

		case VALIDATE_RECAPTCHA:
			return {
				...state,
				validateRecaptcha: action.payload,
			};

		case SAVE_CAMPAIGN_PENDING:
			return {
				...state,
				saveReq: true,
			};

		case SAVE_CAMPAIGN_COMPLETE:
			const { submit } = action.payload;
			if (submit) {
				const submitData = {
					message: action.payload.message,
					redirect: action.payload.redirect,
				};
				return {
					...state,
					submit: true,
					submitData,
				};
			} else {
				return {
					...state,
					postId: action.payload.postId,
					saveDate: action.payload.saveDate,
				};
			}
		default:
			return state;
	}
}
