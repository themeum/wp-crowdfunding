/*
    SocialShare - jQuery plugin
*/
(function ($) {
	function get_class_list(elem) {
		if (elem.classList) {
			return elem.classList;
		} else {
			return $(elem).attr('class').match(/\S+/gi);
		}
	}

	$.fn.ShareLink = function (options) {
		var defaults = {
			title: '',
			text: '',
			image: '',
			url: window.location.href,
			class_prefix: 's_',
		};

		var options = $.extend({}, defaults, options);

		var class_prefix_length = options.class_prefix.length;

		var templates = {
			twitter: 'https://twitter.com/intent/tweet?url={url}&text={text}',
			facebook: 'https://www.facebook.com/sharer.php?s=100&p[title]={title}&u={url}&t={title}&p[summary]={text}&p[url]={url}',
			pinterest: 'https://www.pinterest.com/pin/create/button/?media={image}&url={url}&description={text}',
			linkedin: 'https://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}&summary={text}&source={url}',
			tumblr: 'https://tumblr.com/share?s=&v=3&t={title}&u={url}',
			blogger: 'https://blogger.com/blog-this.g?t={text}&n={title}&u={url}',
			delicious: 'https://delicious.com/save?url={url}&title={title}',
			digg: 'https://digg.com/submit?url={url}&title={title}',
			reddit: 'http://reddit.com/submit?url={url}&title={title}',
			stumbleupon: 'https://www.stumbleupon.com/submit?url={url}&title={title}',
			pocket: 'https://getpocket.com/edit?url={url}&title={title}',
			wordpress: 'http://wordpress.com/wp-admin/press-this.php?u={url}&t={title}&s={text}&v=2',
			whatsapp: 'whatsapp://send?text={url} {title}',
		};

		function link(network) {
			var url = templates[network];
			url = url.replace(/{url}/g, encodeURIComponent(options.url));
			url = url.replace(/{title}/g, encodeURIComponent(options.title));
			url = url.replace(/{text}/g, encodeURIComponent(options.text));
			url = url.replace(/{image}/g, encodeURIComponent(options.image));
			return url;
		}

		return this.each(function (i, elem) {
			var classlist = get_class_list(elem);
			for (var i = 0; i < classlist.length; i++) {
				var cls = classlist[i];
				if (
					cls.substr(0, class_prefix_length) == options.class_prefix &&
					templates[cls.substr(class_prefix_length)]
				) {
					var final_link = link(cls.substr(class_prefix_length));
					$(elem)
						.attr('href', final_link)
						.click(function () {
							if (
								$(this).attr('href').indexOf('http://') === -1 &&
								$(this).attr('href').indexOf('https://') === -1
							) {
								return window.open($(this).attr('href')) && false;
							}
							var screen_width = screen.width;
							var screen_height = screen.height;
							var popup_width = options.width ? options.width : screen_width - screen_width * 0.2;
							var popup_height = options.height ? options.height : screen_height - screen_height * 0.2;
							var left = screen_width / 2 - popup_width / 2;
							var top = screen_height / 2 - popup_height / 2;
							var parameters =
								'toolbar=0,status=0,width=' +
								popup_width +
								',height=' +
								popup_height +
								',top=' +
								top +
								',left=' +
								left;
							return window.open($(this).attr('href'), '', parameters) && false;
						});
				}
			}
		});
	};

	$.fn.ShareCounter = function (options) {
		var defaults = {
			url: window.location.href,
			class_prefix: 'c_',
			display_counter_from: 0,
			increment: false,
		};

		var options = $.extend({}, defaults, options);

		var class_prefix_length = options.class_prefix.length;

		var social = {
			linkedin: linkedin,
			pinterest: pinterest,
			facebook: facebook,
		};

		return this.each(function (i, elem) {
			var classlist = get_class_list(elem);
			for (var i = 0; i < classlist.length; i++) {
				var cls = classlist[i];
				if (
					cls.substr(0, class_prefix_length) == options.class_prefix &&
					social[cls.substr(class_prefix_length)]
				) {
					social[cls.substr(class_prefix_length)](options.url, function (count) {
						count = parseInt(count);
						if (count >= options.display_counter_from) {
							var current_value = parseInt($(elem).text());
							if (options.increment && !isNaN(current_value)) {
								count += current_value;
							}
							$(elem).text(count);
						}
					});
				}
			}
		});

		function linkedin(url, callback) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: 'https://www.linkedin.com/countserv/count/share',
				data: { url: url, format: 'jsonp' },
			})
				.done(function (data) {
					callback(data.count);
				})
				.fail(function () {
					callback(0);
				});
		}

		function pinterest(url, callback) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: 'https://api.pinterest.com/v1/urls/count.json',
				data: { url: url },
			})
				.done(function (data) {
					callback(data.count);
				})
				.fail(function () {
					callback(0);
				});
		}

		function facebook(url, callback) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: 'https://graph.facebook.com',
				data: { id: url },
			})
				.done(function (data) {
					if (data.share != undefined && data.share.share_count != undefined) {
						callback(data.share.share_count);
					}
				})
				.fail(function () {
					callback(0);
				});
		}
	};
})(jQuery);
