jQuery(document).ready(function ($) {
	var DEBOUNCE_MS = 300;
	var MIN_LENGTH = 2;

	$('.wcpbsc-search').each(function () {
		var $root = $(this);
		var $input = $root.find('.wcpbsc-search-input');
		var $resultBox = $root.find('.wcpbsc-search-result');
		var $loader = $root.find('.wcpbsc-search-loader');
		var $magnifier = $root.find('.wcpbsc-search-magnifier');
		var $cross = $root.find('.wcpbsc-search-cross');
		var debounceTimer = null;
		var xhr = null;

		function showIdle() {
			$loader.hide();
			$magnifier.show();
		}

		function showLoading() {
			$magnifier.hide();
			$loader.show();
		}

		function abortPending() {
			clearTimeout(debounceTimer);
			debounceTimer = null;

			if (xhr && xhr.readyState !== 4) {
				xhr.abort();
			}
			xhr = null;
		}

		function clearResults() {
			$resultBox.empty();
		}

		function renderResults(items) {
			clearResults();

			if (!items || !items.length) {
				var noResults = (typeof WCPBSC !== 'undefined' && WCPBSC.i18n && WCPBSC.i18n.noResults)
					? WCPBSC.i18n.noResults
					: 'No results found.';
				$resultBox.text(noResults);
				return;
			}

			var $list = $('<ul class="wcpbsc-search-list"></ul>');

			$.each(items, function (i, item) {
				var $li = $('<li class="wcpbsc-search-item"></li>');
				var $link = $('<a></a>').attr('href', item.url || '#');

				if (item.image) {
					$('<img>').attr('src', item.image).attr('alt', '').appendTo($link);
				}

				$('<span></span>').text(item.title || '').appendTo($link);
				$li.append($link);
				$list.append($li);
			});

			$resultBox.append($list);
		}

		function runSearch(query) {
			abortPending();
			showLoading();

			xhr = $.ajax({
				url: WCPBSC.ajaxUrl,
				type: 'POST',
				data: {
					action: 'wcpbse_search',
					nonce: WCPBSC.nonce,
					query: query
				},
				success: function (response) {
					if ($.trim($input.val()) !== query) {
						return;
					}

					if (response && response.success) {
						renderResults(response.data && response.data.result ? response.data.result : []);
					} else {
						clearResults();
					}
					showIdle();
				},
				error: function (jqXHR, textStatus) {
					if (textStatus === 'abort') {
						return;
					}
					clearResults();
					showIdle();
				}
			});
		}

		$input.on('keyup input', function () {
			var query = $.trim($input.val());

			if (query.length) {
				$cross.show();
			} else {
				$cross.hide();
				abortPending();
				clearResults();
				showIdle();
			}

			clearTimeout(debounceTimer);

			if (query.length < MIN_LENGTH) {
				abortPending();
				clearResults();
				showIdle();
				return;
			}

			debounceTimer = setTimeout(function () {
				runSearch(query);
			}, DEBOUNCE_MS);
		});

		$cross.on('click', function () {
			abortPending();
			$input.val('');
			$cross.hide();
			clearResults();
			showIdle();
			$input.focus();
		});
	});
});
