import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/campaignbox', {
	title: __('Campaign Box'),
	icon: 'format-aside',
    category: 'wp-crowdfunding',
    keywords: [__('Campaign Box'), __('WPCF Project Campaign Box')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  