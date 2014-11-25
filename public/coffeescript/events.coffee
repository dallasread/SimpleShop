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
	cart = $(".cart")
	
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
	
	cart.on "click", "#local", ->
		button = cart.find("button[type='submit']")
		button.prop "disabled", true
		
		$.post SimpleShopAjax.ajaxurl,
			action: "pickup_locally"
			local: $(this).is(":checked")
		, (response) ->
			json = JSON.parse response
			cart.find(".subtotal").text json.subtotal
			cart.find(".shipping").text json.shipping
			cart.find(".tax").text json.tax
			cart.find(".total").text json.total
			cart.find(".clean_total").text json.clean_total
			button.prop "disabled", false
	
	cart.on "change", ".quantity", ->
		tr = $(this).closest("tr")
		button = cart.find("button[type='submit']")
		button.prop "disabled", true
		
		$.post SimpleShopAjax.ajaxurl,
			action: "change_quantity"
			item_id: tr.attr("data-item-id")
			quantity: $(this).val()
		, (response) ->
			json = JSON.parse response
			cart.find(".subtotal").text json.subtotal
			cart.find(".shipping").text json.shipping
			cart.find(".tax").text json.tax
			cart.find(".total").text json.total
			cart.find(".clean_total").text json.clean_total
			tr.find(".price").text json.item_price
			button.prop "disabled", false
	
	$(document).on "click", ".simpleshop_swatch", ->
		colour = $(this).attr("data-colour")
		id = $(this).attr("data-id")
		$(".simpleshop_swatch.selected").removeClass "selected"
		$(this).addClass "selected"
		$("##{id}").val(colour).trigger "change"
		false

$(document).ready SimpleShop.events