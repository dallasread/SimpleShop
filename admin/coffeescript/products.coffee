@SimpleShop ||= {}

SimpleShop.variantSelects = []

SimpleShop.initPricing = ->
	pricing = JSON.parse $(".pricing").attr("data-pricing")
	
	for tier in pricing
		SimpleShop.addPricingTier tier

SimpleShop.initVariants = ->
	variants = JSON.parse $(".variants").attr("data-variants")
	
	for variant in variants
		SimpleShop.addVariant variant

SimpleShop.setSelectize = (obj) ->
	type = if obj.closest(".pricing").length then "pricing" else "variants"
	
	if type == "variants"
		obj.find(".variants_selectize").selectize
			plugins: ['remove_button']
			create: true
			persist: false
			onItemRemove: (value) ->
				permalink = obj.find(".permalink").val()
				$("input.pricing_selectize").each ->
					$(this)[0].selectize.removeItem "#{permalink};#{value}"
					$(this).closest("tr").remove() unless $(this)[0].selectize.getValue().length
			onItemAdd: (value) ->
				permalink = obj.find(".permalink").val()
				attribute = obj.find(".attribute").val()
				option =
					text: "#{attribute} - #{value}"
					value: "#{permalink};#{value}"
				$("input.pricing_selectize").each ->
					select = $(this)[0].selectize
					select.addOption option
				
	else if type == "pricing"
		select = obj.find("input.pricing_selectize")
		select.selectize
			plugins: ['remove_button']
			create: false
			persist: false
			options: SimpleShop.getVariants()

SimpleShop.getVariants = ->
	options = []

	$(".variants .variant").each ->
		permalink = $(this).find(".permalink").val()
		label = $(this).find(".attribute").val()
		opts = $(this).find(".options")[0].selectize.getValue().split(",")
		
		opts.forEach (option) ->
			if option != ""
				options.push
					text: "#{label} - #{option}"
					value: "#{permalink};#{option}"

	options

SimpleShop.getTemplate = (table) ->
	template = table.find(".template").clone()
	[$(template.html()), table.find("tbody:last")]

SimpleShop.addPricingTier = (data = {}) ->
	[item, tbody] = SimpleShop.getTemplate $(".pricing")

	if data == {} && !$(".variant").length
		alert "You haven't added any variants yet. You can do so in the Variants box."
		return false
	
	for k,v of data
		if k == "options"
			options = []
			for option in v
				options.push [option.attribute, option.value].join(";")
			item.find(".#{k}").val options.join(",")
		else
			item.find(".#{k}").val v
	
	SimpleShop.updateRowNames tbody, item
	item.appendTo tbody
	SimpleShop.setSelectize item

SimpleShop.addVariant = (data = {}) ->
	[item, tbody] = SimpleShop.getTemplate $(".variants")
	
	for k,v of data
		if k == "options"
			item.find(".#{k}").val v.join(",")
		else
			item.find(".#{k}").val v
	
	SimpleShop.updateRowNames tbody, item
	item.appendTo tbody
	SimpleShop.setSelectize item

SimpleShop.updateRowNames = (tbody, row) ->
	index = tbody.find("tr").length
	row.find("[name]").each ->
		name = $(this).attr "name"
		name = name.replace(/\[\]/g, "[#{index}]")
		$(this).attr "name", name