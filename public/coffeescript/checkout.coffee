@SimpleShop ||= {}

SimpleShop.checkout = ->
	if $(".checkout").length
		Stripe.setPublishableKey( SimpleShopAjax.stripe_publishable );
		
		$(document).on "submit", ".checkout", ->
			form = $(this)
			form.find('button').prop 'disabled', true

			unless $("#card_token_is_set").length
				Stripe.card.createToken form, (status, response) ->
					if response.error
						alert response.error.message
						form.find('button').prop 'disabled', false
					else
						form.append $('<input type="hidden" name="card_token">').val response.id
						form.append $('<input type="hidden" name="last_four">').val response.card.last4
						form.get(0).submit()
				false
		
		$(".checkout").on "click", ".change_card", ->
			$("#card_token_is_set").remove()
			$(".no_card_fields").hide()
			$(".card_fields").show()
			false

$(document).ready SimpleShop.checkout