(function() {
  this.SimpleShop || (this.SimpleShop = {});

  SimpleShop.serializeObject = function(form) {
    var obj;
    obj = {};
    $.each(form.serializeArray(), function(i, o) {
      var n, v;
      n = o.name;
      v = o.value;
      obj[n] = (obj[n] === undefined ? v : ($.isArray(obj[n]) ? obj[n].concat(v) : [obj[n], v]));
    });
    return obj;
  };

  SimpleShop.events = function() {
    var add_to_cart, cart;
    add_to_cart = $(".add_to_cart");
    cart = $(".cart");
    add_to_cart.on("change", "input, select", function() {
      var button, data, form;
      form = $(this).closest("form");
      button = form.find("button[type='submit']");
      button.prop("disabled", true);
      data = SimpleShop.serializeObject(form);
      data.action = "calculate_product_price";
      return $.post(SimpleShopAjax.ajaxurl, data, function(response) {
        var json;
        json = JSON.parse(response);
        form.find(".price").text(json.clean_price);
        return button.prop("disabled", false);
      });
    });
    cart.on("click", "#local", function() {
      var button;
      button = cart.find("button[type='submit']");
      button.prop("disabled", true);
      return $.post(SimpleShopAjax.ajaxurl, {
        action: "pickup_locally",
        local: $(this).is(":checked")
      }, function(response) {
        var json;
        json = JSON.parse(response);
        cart.find(".subtotal").text(json.subtotal);
        cart.find(".shipping").text(json.shipping);
        cart.find(".tax").text(json.tax);
        cart.find(".total").text(json.total);
        return button.prop("disabled", false);
      });
    });
    return cart.on("change", ".quantity", function() {
      var button, tr;
      tr = $(this).closest("tr");
      button = cart.find("button[type='submit']");
      button.prop("disabled", true);
      return $.post(SimpleShopAjax.ajaxurl, {
        action: "change_quantity",
        item_id: tr.attr("data-item-id"),
        quantity: $(this).val()
      }, function(response) {
        var json;
        json = JSON.parse(response);
        cart.find(".subtotal").text(json.subtotal);
        cart.find(".shipping").text(json.shipping);
        cart.find(".tax").text(json.tax);
        cart.find(".total").text(json.total);
        tr.find(".price").text(json.item_price);
        return button.prop("disabled", false);
      });
    });
  };

  $(document).ready(SimpleShop.events);

}).call(this);
