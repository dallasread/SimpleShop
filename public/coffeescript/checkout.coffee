@SimpleShop ||= {}

SimpleShop.checkout = ->
	SimpleShop.geocode()

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
	
SimpleShop.geocode = ->
	full_address = $("#full_address")
	if full_address.length
		componentForm =
		  street_number: 'short_name',
		  route: 'long_name',
		  locality: 'long_name',
		  administrative_area_level_1: 'short_name',
		  country: 'long_name',
		  postal_code: 'short_name'

		autocomplete = new google.maps.places.Autocomplete full_address[0],
			types: ['geocode']
		
		google.maps.event.addListener autocomplete, 'place_changed', ->
			place = autocomplete.getPlace()
			components = {}
			i = 0
			
			while i < place.address_components.length
				addressType = place.address_components[i].types[0]
				if componentForm[addressType]
					val = place.address_components[i][componentForm[addressType]]
					components[addressType] = val
				i++
			
			if components != {}
				$("#address").val "#{components.street_number} #{components.route}"
				$("#city").val components.locality
				$("#province").val components.administrative_area_level_1
				$("#postal_code").val components.postal_code
				$("#country").val components.country
			

$(document).ready SimpleShop.checkout