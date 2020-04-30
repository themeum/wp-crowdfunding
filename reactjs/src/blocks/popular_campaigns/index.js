import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/popularcampaigns', {
	title: __('Popular Campaigns'),
	icon: 'clipboard',
    category: 'wp-crowdfunding',
    keywords: [__('Popular Campaigns'), __('WPCF Project Popular Campaigns')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  