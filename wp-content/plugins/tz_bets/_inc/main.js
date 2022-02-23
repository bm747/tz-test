jQuery(document).ready(function ($) {
	var form = $('#postbet');
	var action = form.attr('action');

	var form2 = $('#updbet');
	var action2 = form2.attr('action');

	form.on('submit', function (event) {
		var formData = {

		betText: $('#bet_name').val(),
		betDesc: $('#bet_text').val(),
		betType: $('#bet_type').val()

		};

		$.ajax({
			url: action,
			type: 'POST',
			data: formData,
			error: function (request, txtstatus, errorThrown) {
				console.log(request);
				console.log(txtstatus);
				console.log(errorThrown);
			},
			success: function() {
				form.html ("Ваша ставка отправлена");
			}
		});

		event.preventDefault();

	});


	form2.on('submit', function (event2) {
			
			var formData2 = {

				betVote: $('#bet_vote').val(),
				betId: $('#bet_id').val()

			};

			$.ajax({
				url: action2,
				type: 'POST',
				data: formData2,
				error: function (request, txtstatus, errorThrown) {
					console.log(request);
					console.log(txtstatus);
					console.log(errorThrown);
				},
				success: function() {
					form2.html ("Ваша ставка отправлена");
				}
			});

			event2.preventDefault();

	});

	
});