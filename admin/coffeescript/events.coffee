@SimpleShop ||= {}

SimpleShop.init = ->
	if $(".post-type-product").length
		SimpleShop.events()
		SimpleShop.initVariants()
		SimpleShop.initPricing()

SimpleShop.events = ->
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

$(document).ready SimpleShop.init