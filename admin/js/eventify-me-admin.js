/*
* script file for main functions for admin area
*/
(function( $ ) {
	'use strict';

	const   $metaboxContainer = $('.eventify-me-metabox-container'),
			$body = $('body');

	$metaboxContainer.on('change', '.checkbox-group .checkbox-group-child input', function (){
		const   $this = $(this),
				isChecked = $this.prop('checked');
		if(isChecked) switchParentsCheckboxForTerms($this, isChecked)
	})

	$metaboxContainer.on('change', '.checkbox-group input', function (){
		const   $this = $(this),
			isChecked = $this.prop('checked');
		if(!isChecked) unsetChildCheckboxForTerms($this, isChecked)
	})

	function switchParentsCheckboxForTerms($el, value){
		if($el.closest('div').hasClass('checkbox-group-child')){
			const $input = $el.closest('div').prev('label').find('input');
			$input.prop('checked', value);
			switchParentsCheckboxForTerms($input, value);
		}
	}

	function unsetChildCheckboxForTerms($el, value){
		const $div = $el.closest('label').next('div');
		if($div.hasClass('checkbox-group-child')){
			$div.find('input').prop('checked', value)
		}
	}

	$metaboxContainer.on('click', '.input-group-image .upload_image_button', function() {
		const $this = $(this);

		const custom_uploader = wp.media({
			title: 'Insert image', /* popup title */
			library : {type : 'image'},
			button: {text: 'Save'}, /* "Insert" button text */
			multiple: false
		}).on('select', function() {

			var attachments = custom_uploader.state().get('selection').map(function( a ) {
					a.toJSON();
					return a;
				});

			const imgurl = attachments[0]['attributes']['url'];

			$this.siblings('input[type=text]').val(imgurl)
			$this.css({'background-image': 'url(' + imgurl + ')', 'background-size': 'cover'})
			$this.closest('.img-wrap').addClass('active')
		}).open();

		return false;
	});

	$metaboxContainer.on('click', '.input-group-image .remove-image', function(e) {
		e.preventDefault();
		const $this = $(this);

		$this.siblings('input[type=text]').val('');
		$this.siblings('input[type=button]').css({'background-image': 'unset',});
		$this.closest('.img-wrap').removeClass('active')
		return false;
	})


	// timesession field
	function flatpickrInit(fieldName){
		$.each($('input.flatpickr-input[data-field-name=' + fieldName + ']'), function (index, i){
			if($(this).attr('data-flatpickr-type') === 'date') {
				flatpickr($(this), {
					dateFormat: 'd/m/Y',
					onChange: function (selectedDates, dateStr, instance){
						timesessionFieldValueSet(fieldName);
					}
				});
			} else if ($(this).attr('data-flatpickr-type') === 'time'){
				flatpickr($(this), {
					enableTime: true,
					dateFormat: 'H:i',
					time_24hr: true,
					noCalendar: true,
					onChange: function (selectedDates, dateStr, instance){
						timesessionFieldValueSet(fieldName);
					},
					onReady (_, __, fp) {
						fp.calendarContainer.classList.add('flatpickr-type-time');
					}
				});

				if($(this).siblings('a.clear-input').length) {
					$(this).siblings('a.clear-input').on('click', function (e){
						e.preventDefault()
						$(this).siblings('input[data-field-name=' + fieldName + ']').val('')
						timesessionFieldValueSet(fieldName);
					})
				}
			}
		})
	}

	function timesessionFieldValueSet(fieldName){
		let res = [];

		// $.each($('input.flatpickr-input[data-field-name=' + fieldName + ']'), (index, i) => res[index] = $(i).val())

		$.each($('.session-item[data-field-name=' + fieldName + ']'), (index, i) => {
			// console.log($(i).find('[data-input-type=is_booking_enabled]'), $(i).find('[data-input-type=is_booking_enabled]').is(':checked'));
			res[index] = {};
			res[index]['id'] = $(i).find('[data-input-type=id]').val()
			res[index]['date'] = $(i).find('[data-input-type=date]').val()
			res[index]['time_start'] = $(i).find('[data-input-type=time_start]').val()
			res[index]['time_end'] = $(i).find('[data-input-type=time_end]').val()
			res[index]['is_booking_enabled'] = $(i).find('[data-input-type=is_booking_enabled]').is(':checked') ? 'yes' : 'no'
		})

		$('input[name=' + fieldName + ']').val(JSON.stringify(Object.assign({}, res)))
	}

	$metaboxContainer.on('click', '.timesession-field-add', function (e){
		e.preventDefault();
		$body.addClass('loader');

		const   $this = $(this),
				currentCount = $this.attr('data-current-count'),
				fieldName = $this.attr('data-field-name');

		if($this.closest('.sessions-wrap').find('.session-item').length == 0) $this.text($this.attr('data-another-session-text'))

		$.ajax({
			url: ajax.ajaxurl,
			method: 'POST',
			data: {action: 'eventify_me_timesession_field_item_ajax', currentCount: currentCount, fieldName: fieldName},
			success: function(data){
				$this.attr('data-current-count', data.newCount);
				$this.closest('.timesession-field').find('.sessions-wrap').append(data.html);
				flatpickrInit(fieldName);
			},
			error: function(data){
				//
			},
			complete: function(data){
				$body.removeClass('loader');
			},
		});
		return false;
	})

	$metaboxContainer.on('click', '.timesession-field-remove', function (e) {
		e.preventDefault()
		if(confirm($(this).closest('.sessions-wrap').attr('data-confirm-text-for-remove-session'))) {
			const $this = $(this),
				$addBtn = $this.closest('.timesession-field').find('.timesession-field-add'),
				currentCount = $this.closest('.sessions-wrap').find('.session-item').length,
				sessionId = $this.siblings('input[data-input-type=id]').val();

			if(currentCount == 1) $addBtn.text($addBtn.attr('data-first-session-text'))

			if(!sessionId.length) {
				$body.addClass('loader')
				$this.closest('.session-item').remove();
				timesessionFieldValueSet($this.attr('data-field-name'))
				$body.removeClass('loader')
			} else {
				$.ajax({
					url: ajax.ajaxurl,
					method: 'POST',
					data: {action: 'remove_session_ajax', session_id: sessionId},
					success: function(data){
						// console.log(data);
						if(data.status === 'error') alert(data.message);
						else {
							$this.closest('.session-item').remove();
							timesessionFieldValueSet($this.attr('data-field-name'))
						}
					},
					error: function(data){
						// console.log(data);
					},
					complete: function(data){
						$body.removeClass('loader')
					},
				});
			}
		}
		return false;
	})

	$metaboxContainer.on('change', '.sessions-wrap .session-item .checkbox-group-session input[type=checkbox]', function (){
		timesessionFieldValueSet($(this).attr('data-field-name'))
	})

	flatpickrInit('event_sessions')
	timesessionFieldValueSet('event_sessions')

	// filter event in admin
	flatpickr('input.filter-by-date', {
		mode: "range",
		dateFormat: 'd/m/Y',
	});

	//move custom filters to another place
	let $customFilters;
	if($('.wp-admin.post-type-eventify-me-events:not(.eventify-me-events_page_eventify-me-bookings) .eventify-me-custom-admin-filters').length){
		$customFilters = $('.wp-admin.post-type-eventify-me-events:not(.eventify-me-events_page_eventify-me-bookings) .eventify-me-custom-admin-filters');
		$customFilters
			.prependTo("#posts-filter");

		$('.wp-admin.post-type-eventify-me-events #post-query-submit')
			.appendTo($customFilters)
			.css({'display':'block!important'})

		$customFilters
			.find('[name=filter_action]')
			.text('Apply filter')
			.attr('value', 'Apply filter')
			.before('<a href="#" class="button eventify-me-custom-admin-filters-hide">Hide filters</a>')

		$customFilters.after('<a href="#" class="button eventify-me-custom-admin-filters-show">Show filters</a>')
	} else if ($body.hasClass('eventify-me-events_page_eventify-me-bookings')) {
		$customFilters = $('.eventify-me-events_page_eventify-me-bookings .eventify-me-custom-admin-filters');
	}


	if($customFilters !== undefined && $customFilters.length) {
		$('body').on('click', '.eventify-me-custom-admin-filters-hide', function (e){
			e.preventDefault();
			$customFilters.css({'display':'none'});
			$('.eventify-me-custom-admin-filters-show').show();
			return false;
		})

		$('body').on('click', '.eventify-me-custom-admin-filters-show', function (e){
			e.preventDefault();
			$customFilters.css({'display':'flex'});
			$(this).hide();
			return false;
		})
	}

//	 booking list page
	$('.eventify-me-booking-table-form #search-submit').on('click', function (e){
		e.preventDefault();
		var $this = $(this),
			$form = $this.closest('form'),
			page = 'eventify-me-bookings',
			post_type = 'eventify-me-events';

		var url_string = 'edit.php?post_type=' + post_type + '&page=' + page + '&' +
			$form.find("" +
				"[name=event_comments], " +
				"[name=start_date], " +
				"[name=start_time_from], " +
				"[name=start_minute_from], " +
				"[name=start_time_to], " +
				"[name=start_minute_to], " +
				"[name=end_date], " +
				"[name=end_time_from], " +
				"[name=end_minute_from], " +
				"[name=end_time_to], " +
				"[name=end_minute_to], " +
				"[name=user_email], " +
				"[name=user_first_name], " +
				"[name=user_last_name], " +
				"[name=user_phone], " +
				"[name=order_identifier], " +
				"[name=created_from], " +
				"[name=created_to]"
			).serialize();

		document.location.href = url_string;
		return false;
	})

	flatpickr('.eventify-me-booking-table-form .datetime', {
		dateFormat: 'd/m/Y H:i',
		enableTime: true,
		time_24hr: true,
		mode: "range",
	});

	flatpickr('.eventify-me-booking-table-form .date_created', {
		dateFormat: 'd/m/Y',
		enableTime: false,
	});

	flatpickr('.eventify-me-booking-table-form .daterange', {
		dateFormat: 'd/m/Y',
		enableTime: false,
		mode: "range",
	});

	flatpickr('.eventify-me-booking-table-form .timehour', {
		dateFormat: 'H',
		enableTime: true,
		noCalendar: true,
		time_24hr: true,
		onReady (_, __, fp) {
			fp.calendarContainer.classList.add('flatpickr-container-for-only-hour');
		}
	});

	flatpickr('.eventify-me-booking-table-form .timeminute', {
		dateFormat: 'i',
		enableTime: true,
		noCalendar: true,
		time_24hr: true,
		onReady (_, __, fp) {
			fp.calendarContainer.classList.add('flatpickr-container-for-only-minute');
		}
	});

	$('.eventify-me-booking-table-form input#order_identifier').inputmask("***-***-***");

	/*formats & thematics admin page*/
	if(localStorage.getItem('return-to-taxonomy-page') != null && localStorage.getItem('return-to-taxonomy-page') != 'null') {
		const redirectUrl = localStorage.getItem('return-to-taxonomy-page');
		localStorage.setItem('return-to-taxonomy-page', null);
		window.location = redirectUrl;
	}

	$('.taxonomy-event_formats #edittag #save-and-return, .taxonomy-event_thematics #edittag #save-and-return').on('click', function (e){
		e.preventDefault();
		localStorage.setItem('return-to-taxonomy-page', $(this).siblings('input[name=all-taxonomy-page-url]').val());
		$(this).closest('form').submit();
	})

	//event manager
	if($('.eventify-me-metabox-container__side').length) {
		$('.eventify-me-metabox-container__side .event-managers-wrap input[name=event_manager]').each(function (index, i) {
			setFieldAutocmopleteForManagers($(this))
		})
		setEventManagersIds()

		$('.eventify-me-metabox-container__side .add-event-manager').on('click', function (e){
			e.preventDefault()
			$body.addClass('loader')
			const $this = $(this)

			$.ajax({
				url: ajax.ajaxurl,
				method: 'POST',
				data: {action: 'eventify_me_event_manager_field_item_ajax'},
				success: function(data){
					$this.closest('.input-group-event-managers').find('.event-managers-wrap').append(data.html);
					setFieldAutocmopleteForManagers($this.closest('.input-group-event-managers').find('.event-managers-wrap .input-group:last-child input[name=event_manager]'))
				},
				error: function(data){
					//
				},
				complete: function(data){
					$body.removeClass('loader');
				},
			});
			return false
		})

		$body.on('click', '.eventify-me-metabox-container__side  .event-manager-field-remove', function (e){
			e.preventDefault()
			$(this).closest('.input-group').remove()
			setEventManagersIds()
			return false
		})

		function setFieldAutocmopleteForManagers(input) {
			const usersForAutocomplete = JSON.parse($('.eventify-me-metabox-container .wp-users-for-autocomplete').text())
			//console.log(usersForAutocomplete);
			const $inputGroup = $(input).closest('.input-group')
			$(input).autocomplete({
				minLength: 0,
				source: function (request, response) {
					response($.map(usersForAutocomplete, function (val, i) {
						const fullName = `${val.first_name} ${val.last_name} (${val.user_login})`;
						if (fullName.toLowerCase().indexOf(request.term.toLocaleLowerCase()) > -1) {
							return {
								user_login: val.user_login,
								ID: val.ID,
								first_name: val.first_name,
								last_name: val.last_name,
								// user_email: val.user_email,
								// user_nicename: val.user_nicename,
							};
						}
					}));
				},
				focus: function( event, ui ) {
					$( event.target ).val( `${ui.item.first_name} ${ui.item.last_name} (${ui.item.user_login})` )
					$inputGroup.find('input[type=hidden]').val(ui.item.ID)
					setEventManagersIds()
					// $inputGroup.find('strong.user_email').text(ui.item.user_email)
					// $inputGroup.find('strong.user_nicename').text(ui.item.user_nicename)
					return false;
				},
				select: function( event, ui ) {
					$( event.target ).val( `${ui.item.first_name} ${ui.item.last_name} (${ui.item.user_login})` );
					return false;
				}
			}).focus(function (){
				$(input).data("uiAutocomplete").search($(input).val());
			}).autocomplete( 'instance' )._renderItem = function( ul, item ) {
				return $( '<li>' )
					.append( `<div>${item.first_name} ${item.last_name} (${item.user_login})</div>` )
					.appendTo( ul );
			}

			$(input).on('keyup', function (){
				if($(this).val().length < 1) {
					$( this ).val( '' )
					$inputGroup.find('input[type=hidden]').val('')
					// $inputGroup.find('strong.user_email').text('')
					// $inputGroup.find('strong.user_nicename').text('')
				}
			})
		}

		function setEventManagersIds(){
			let ids = {}
			$('.eventify-me-metabox-container__side .event-managers-wrap input[name=event_manager_id]').each(function (index, i) {
				ids[index] = $(this).val()
			})
			$('.eventify-me-metabox-container__side input[name=event_managers_id]').val(JSON.stringify(ids))
		}
	}


	$('.bookings-list-page .wp-list-table .column-event_id .row-actions .trash a').on('click', function (e){
		e.preventDefault()
		const $this = $(this)
		if(confirm($this.attr('data-confirm'))) window.location = $this.attr('href')
		return false
	})

	let clickboardInterval;
	$('.copy-shortcode-to-clickboard').on('click', function (e){
		e.preventDefault()
		const $this = $(this)
		const text = $this.siblings('span').text();
		navigator.clipboard.writeText(text).then(function() {
			let textToMessage = ''
			if($this.attr('data-type') === 'event-page') textToMessage = $body.find('.message-about-copy').attr('data-event-page-text')
			else textToMessage = $body.find('.message-about-copy').attr('data-booking-page-text')

			$body.find('.message-about-copy')
				.text(textToMessage)
				.fadeIn()

			clearInterval(clickboardInterval)

			clickboardInterval = setTimeout(function (){
				$body.find('.message-about-copy').fadeOut()
			}, 5000)
			// console.log('Async: Copying to clipboard was successful!');
		}, function(err) {
			console.error('Async: Could not copy text: ', err);
		});
	})

	$('.custom-tooltip').on('click', function (e){
		e.preventDefault()
	})

	$('.custom-tooltip').on('mouseenter', function (){
		$(this).find('div').fadeIn()
	})

	$('.custom-tooltip').on('mouseleave', function (){
		$(this).find('div').fadeOut()
	})

	if($('.notice_editor_copy_past').length) {
		setTimeout(function (){
			$('.notice_editor_copy_past')
				.prependTo('#postdivrich')
				.show()
		}, 200)
	}


})( jQuery );