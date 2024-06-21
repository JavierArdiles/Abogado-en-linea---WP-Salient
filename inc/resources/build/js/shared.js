( function( $ ) {
    
	const ClazzyStudioShared = function() {

        this.selectors = {
			'demo': '.class',
		};

		this.blog = {
			'list': '.blog-grid__list',
			'item': '.blog-post-preview',
			'loadMore': '.blog-grid__load-more a',
		};
	};

	ClazzyStudioShared.prototype = {

		init: function(){
			
			this.initEvents();
		},

		initEvents: function(){
			/* Events */

			// Blog
			$(this.blog.loadMore).on('click', { that: this }, this.eventGetMorePosts);
		},

		eventGetMorePosts: function (e) {
			e.preventDefault();

			let that = e.data.that,
				button = $(this),
				blogList = $(that.blog.list);

			if (button.data('reset')) {
				that.setResetPosts(button, false);
				that.resetPosts(blogList);

				return false;
			}

			that.getMorePosts(blogList, button);
		},

		getMorePosts: function (blogList, button) {
			let that = this,
				offset = blogList.find(that.blog.item).length;

			that.setResetPosts(button, false);

			$.ajax({
				url: localize._ajax_url,
				type: 'post',
				data: {
					action: 'get_more_blog_posts',
					offset,
					_ajax_nonce: localize._ajax_nonce,
				},
				beforeSend: function () {
					button.addClass('loading');
				},
				success: function (xhr) {
					if (xhr.data.count < xhr.data.limit) {
						that.setResetPosts(button, true);
					}

					if (xhr.data.count == 0) {
						return;
					}

					blogList.append(xhr.data.items);
				},
				complete: function (res) {
					button.removeClass('loading');
				}
			});
		},

		resetPosts: function (blogList) {
			let blogPost = this.blog.item;

			blogList.find($(blogPost + ':nth-child(n+13)')).remove();

			let offset = blogList.first().offset().top;

			window.scrollTo({ top: offset - 300, behavior: 'smooth' });
		},

		setResetPosts: function (button, reset = false) {

			let inClass = '.more',
				outClass = '.less';

			if (reset) {
				outClass = '.more';
				inClass = '.less';
			}

			button.find(`${inClass}.d-n`).removeClass('d-n');
			button.find(outClass).addClass('d-n');
			button.data('reset', reset ? 1 : 0);
		},
	};

	$(function() {

		window.ClazzyStudioShared = new ClazzyStudioShared();
		window.ClazzyStudioShared.init();
	});
	
}( jQuery ) );
