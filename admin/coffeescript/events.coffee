@SimpleShop ||= {}

SimpleShop.init = ->
	if $(".post-type-product").length
		SimpleShop.products()
		SimpleShop.initVariants()
		SimpleShop.initPricing()
	
	if $(".carts").length
		SimpleShop.carts()

SimpleShop.products = ->
	tables = $(".variants, .pricing")
	
	tables.on "click", ".add", ->
		if $(this).closest(".pricing").length
			SimpleShop.addPricingTier()
		else
			SimpleShop.addVariant()
		false
	
	tables.on "click", ".remove", ->
		if confirm "Are you sure you want to delete this row?"
			$(this).closest("tr").remove()
		false
	
	tables.on "keyup", ".attribute", ->
		permalink = SimpleShop.parameterize $(this).val()
		$(this).closest("tr").find(".permalink").val permalink
		
	
	tables.on "blur", ".is_decimal", ->
		if $(this).val().length
			decimal = parseFloat $(this).val()
			$(this).val decimal.toFixed(2)

SimpleShop.carts = ->
	carts = $(".carts")
	
	carts.on "click", ".mark_complete", ->
		cart_id = $(this).closest("tr").attr("data-id")
		$(this).prop "disabled", true
		
		$.post ajaxurl,
			action: "mark_complete"
			cart_id: cart_id
			status: if $(this).is(":checked") then "complete" else "processing"
		, (response) ->
			carts.find(".cart[data-id='#{cart_id}']").fadeOut()
	
	carts.on "click", ".refund", ->
		token = $(this).closest("tr").attr("data-token")
		$.post ajaxurl,
			action: "refund"
			cart_token: token
		, (response) ->
			json = JSON.parse response
			if json.refunded_at != ""
				alert "Refund has been issued."
				carts.find(".cart[data-token='#{token}']").find(".trash").hide()

$(document).ready SimpleShop.init