@SimpleShop ||= {}

SimpleShop.serializeObject = (form) ->
  obj = {}
  $.each form.serializeArray(), (i, o) ->
    n = o.name
    v = o.value
    obj[n] = (if obj[n] is `undefined` then v else (if $.isArray(obj[n]) then obj[n].concat(v) else [
      obj[n]
      v
    ]))
    return

  obj

SimpleShop.events = ->
	add_to_cart = $(".add_to_cart")
	
	add_to_cart.on "change", "input, select", ->
		form = $(this).closest("form")
		button = form.find("button[type='submit']")
		button.prop "disabled", true
		data = SimpleShop.serializeObject form
		data.action = "calculate_product_price"
		$.post SimpleShopAjax.ajaxurl, data, (response) ->
			json = JSON.parse response
			form.find(".price").text json.clean_price
			button.prop "disabled", false

$(document).ready SimpleShop.events