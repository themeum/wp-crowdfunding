import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/projectlisting', {
	title: __('Project Listing'),
	icon: 'list-view',
    category: 'wp-crowdfunding',
    keywords: [__('Campaign Listing'), __('WPCF Project Listing')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  