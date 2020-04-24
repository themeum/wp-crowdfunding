import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('crowdfunding/crowdfunding-core-search', {
	title: __('Courses Search'),
	icon: 'search',
    category: 'wp-crowdfunding',
    keywords: [__('Latest Campaign Search'), __('Campaign Search')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  