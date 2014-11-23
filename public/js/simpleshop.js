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
    var add_to_cart;
    add_to_cart = $(".add_to_cart");
    return add_to_cart.on("change", "input, select", function() {
      var button, data, form;
      form = $(this).closest("form");
      button = form.find("button[type='submit']");
      button.prop("disabled", true);
      data = SimpleShop.serializeObject(form);
      return $.post(SimpleShopAjax.ajaxurl, data, function(response) {
        var json;
        json = JSON.parse(response);
        form.find(".price").text(json.clean_price);
        return button.prop("disabled", false);
      });
    });
  };

  $(document).ready(SimpleShop.events);

}).call(this);
