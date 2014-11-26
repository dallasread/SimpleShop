(function() {
  this.SimpleShop || (this.SimpleShop = {});

  SimpleShop.init = function() {
    if ($(".post-type-product").length) {
      SimpleShop.products();
      SimpleShop.initVariants();
      SimpleShop.initPricing();
    }
    if ($(".carts").length) {
      return SimpleShop.carts();
    }
  };

  SimpleShop.products = function() {
    var tables;
    tables = $(".variants, .pricing");
    tables.on("click", ".add", function() {
      if ($(this).closest(".pricing").length) {
        SimpleShop.addPricingTier();
      } else {
        SimpleShop.addVariant();
      }
      return false;
    });
    tables.on("click", ".remove", function() {
      if (confirm("Are you sure you want to delete this row?")) {
        $(this).closest("tr").remove();
      }
      return false;
    });
    tables.on("keyup", ".attribute", function() {
      var permalink;
      permalink = SimpleShop.parameterize($(this).val());
      return $(this).closest("tr").find(".permalink").val(permalink);
    });
    return tables.on("blur", ".is_decimal", function() {
      var decimal;
      if ($(this).val().length) {
        decimal = parseFloat($(this).val());
        return $(this).val(decimal.toFixed(2));
      }
    });
  };

  SimpleShop.carts = function() {
    var carts;
    carts = $(".carts");
    carts.on("click", ".mark_complete", function() {
      var cart_id;
      cart_id = $(this).closest("tr").attr("data-id");
      $(this).prop("disabled", true);
      return $.post(ajaxurl, {
        action: "mark_complete",
        cart_id: cart_id,
        status: $(this).is(":checked") ? "complete" : "processing"
      }, function(response) {
        return carts.find(".cart[data-id='" + cart_id + "']").fadeOut();
      });
    });
    return carts.on("click", ".refund", function() {
      var token;
      token = $(this).closest("tr").attr("data-token");
      return $.post(ajaxurl, {
        action: "refund",
        cart_token: token
      }, function(response) {
        var json;
        json = JSON.parse(response);
        if (json.refunded_at !== "") {
          alert("Refund has been issued.");
          return carts.find(".cart[data-token='" + token + "']").find(".trash").hide();
        }
      });
    });
  };

  $(document).ready(SimpleShop.init);

  this.SimpleShop || (this.SimpleShop = {});

  SimpleShop.parameterize = function(str) {
    return str.trim().replace(/[^a-zA-Z0-9-\s]/g, '').replace(/[^a-zA-Z0-9-]/g, '_').toLowerCase();
  };

  this.SimpleShop || (this.SimpleShop = {});

  SimpleShop.variantSelects = [];

  SimpleShop.initPricing = function() {
    var pricing, tier, _i, _len, _results;
    pricing = $(".pricing").attr("data-pricing");
    if (pricing.length) {
      pricing = JSON.parse(pricing);
      _results = [];
      for (_i = 0, _len = pricing.length; _i < _len; _i++) {
        tier = pricing[_i];
        _results.push(SimpleShop.addPricingTier(tier));
      }
      return _results;
    }
  };

  SimpleShop.initVariants = function() {
    var variant, variants, _i, _len, _results;
    variants = $(".variants").attr("data-variants");
    if (variants.length) {
      variants = JSON.parse(variants);
      _results = [];
      for (_i = 0, _len = variants.length; _i < _len; _i++) {
        variant = variants[_i];
        _results.push(SimpleShop.addVariant(variant));
      }
      return _results;
    }
  };

  SimpleShop.setSelectize = function(obj) {
    var select, type;
    type = obj.closest(".pricing").length ? "pricing" : "variants";
    if (type === "variants") {
      return obj.find(".variants_selectize").selectize({
        plugins: ['remove_button'],
        create: true,
        persist: false,
        onItemRemove: function(value) {
          var permalink;
          permalink = obj.find(".permalink").val();
          return $("input.pricing_selectize").each(function() {
            $(this)[0].selectize.removeItem("" + permalink + ";" + value);
            if (!$(this)[0].selectize.getValue().length) {
              return $(this).closest("tr").remove();
            }
          });
        },
        onItemAdd: function(value) {
          var attribute, option, permalink;
          permalink = obj.find(".permalink").val();
          attribute = obj.find(".attribute").val();
          option = {
            text: "" + attribute + " - " + value,
            value: "" + permalink + ";" + value
          };
          return $("input.pricing_selectize").each(function() {
            var select;
            select = $(this)[0].selectize;
            return select.addOption(option);
          });
        }
      });
    } else if (type === "pricing") {
      select = obj.find("input.pricing_selectize");
      return select.selectize({
        plugins: ['remove_button'],
        create: false,
        persist: false,
        options: SimpleShop.getVariants()
      });
    }
  };

  SimpleShop.getVariants = function() {
    var options;
    options = [];
    $(".variants .variant").each(function() {
      var label, opts, permalink;
      permalink = $(this).find(".permalink").val();
      label = $(this).find(".attribute").val();
      opts = $(this).find(".options")[0].selectize.getValue().split(",");
      return opts.forEach(function(option) {
        if (option !== "") {
          return options.push({
            text: "" + label + " - " + option,
            value: "" + permalink + ";" + option
          });
        }
      });
    });
    return options;
  };

  SimpleShop.getTemplate = function(table) {
    var template;
    template = table.find(".template").clone();
    return [$(template.html()), table.find("tbody:last")];
  };

  SimpleShop.addPricingTier = function(data) {
    var item, k, option, options, tbody, v, _i, _len, _ref;
    if (data == null) {
      data = {};
    }
    _ref = SimpleShop.getTemplate($(".pricing")), item = _ref[0], tbody = _ref[1];
    if (data === {} && !$(".variant").length) {
      alert("You haven't added any variants yet. You can do so in the Variants box.");
      return false;
    }
    for (k in data) {
      v = data[k];
      if (k === "options") {
        options = [];
        for (_i = 0, _len = v.length; _i < _len; _i++) {
          option = v[_i];
          options.push([option.attribute, option.value].join(";"));
        }
        item.find("." + k).val(options.join(","));
      } else {
        item.find("." + k).val(v);
      }
    }
    SimpleShop.updateRowNames(tbody, item);
    item.appendTo(tbody);
    return SimpleShop.setSelectize(item);
  };

  SimpleShop.addVariant = function(data) {
    var item, k, tbody, v, _ref;
    if (data == null) {
      data = {};
    }
    _ref = SimpleShop.getTemplate($(".variants")), item = _ref[0], tbody = _ref[1];
    for (k in data) {
      v = data[k];
      if (k === "options") {
        item.find("." + k).val(v.join(","));
      } else {
        item.find("." + k).val(v);
      }
    }
    SimpleShop.updateRowNames(tbody, item);
    item.appendTo(tbody);
    return SimpleShop.setSelectize(item);
  };

  SimpleShop.updateRowNames = function(tbody, row) {
    var index;
    index = tbody.find("tr").length;
    return row.find("[name]").each(function() {
      var name;
      name = $(this).attr("name");
      name = name.replace(/\[\]/g, "[" + index + "]");
      return $(this).attr("name", name);
    });
  };

}).call(this);
