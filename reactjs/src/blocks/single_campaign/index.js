import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/singlecampaign', {
	title: __('Single Campaign'),
	icon: 'editor-kitchensink',
    category: 'wp-crowdfunding',
    keywords: [__('Single Campaign'), __('WPCF Single Campaign')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  