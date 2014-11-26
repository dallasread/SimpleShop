@SimpleShop ||= {}

SimpleShop.parameterize = (str) ->
	str.trim().replace(/[^a-zA-Z0-9-\s]/g, '').replace(/[^a-zA-Z0-9-]/g, '_').toLowerCase()