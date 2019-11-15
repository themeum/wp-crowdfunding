import { __ } from '@wordpress/i18n';

export const decodeEntities = str => {
    const element = document.createElement('div');
    if(str && typeof str === 'string') {
        // strip script/html tags
        str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
        str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
        element.innerHTML = str;
        str = element.textContent;
        element.textContent = '';
    }
    return str;
};

export const wcPice = (price=0) => {
    const { symbol, d_separator, t_separator, decimals } = WPCF.currency;
    price = parseFloat(price).toFixed(decimals);
    price = price.replace(".", d_separator);
    var splitPrice = price.split(d_separator);
    splitPrice[0] = splitPrice[0].replace(/\B(?=(\d{3})+(?!\d))/g, t_separator);
    price = splitPrice.join(d_separator);
    return symbol+price;
}

export const months = {
    jan: __('January', 'wp-crowdfunding'),
    feb: __('February', 'wp-crowdfunding'),
    mar: __('March', 'wp-crowdfunding'),
    apr: __('April', 'wp-crowdfunding'),
    may: __('May', 'wp-crowdfunding'),
    jun: __('June', 'wp-crowdfunding'),
    jul: __('July', 'wp-crowdfunding'),
    aug: __('August', 'wp-crowdfunding'),
    sep: __('September', 'wp-crowdfunding'),
    oct: __('October', 'wp-crowdfunding'),
    nov: __('November', 'wp-crowdfunding'),
    dec: __('December', 'wp-crowdfunding'),
};