(function() {
  this.SimpleShop || (this.SimpleShop = {});

  SimpleShop.checkout = function() {
    if ($(".checkout").length) {
      Stripe.setPublishableKey(SimpleShopAjax.stripe_publishable);
      $(document).on("submit", ".checkout", function() {
        var form;
        form = $(this);
        form.find('button').prop('disabled', true);
        if (!$("#card_token_is_set").length) {
          Stripe.card.createToken(form, function(status, response) {
            if (response.error) {
              alert(response.error.message);
              return form.find('button').prop('disabled', false);
            } else {
              form.append($('<input type="hidden" name="card_token">').val(response.id));
              form.append($('<input type="hidden" name="last_four">').val(response.card.last4));
              return form.get(0).submit();
            }
          });
          return false;
        }
      });
      return $(".checkout").on("click", ".change_card", function() {
        $("#card_token_is_set").remove();
        $(".no_card_fields").hide();
        $(".card_fields").show();
        return false;
      });
    }
  };

  $(document).ready(SimpleShop.checkout);

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
        cart.find(".clean_total").text(json.clean_total);
        return button.prop("disabled", false);
      });
    });
    cart.on("change", ".quantity", function() {
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
        cart.find(".clean_total").text(json.clean_total);
        tr.find(".price").text(json.item_price);
        return button.prop("disabled", false);
      });
    });
    return $(document).on("click", ".simpleshop_swatch", function() {
      var colour, id;
      colour = $(this).attr("data-colour");
      id = $(this).attr("data-id");
      $(".simpleshop_swatch.selected").removeClass("selected");
      $(this).addClass("selected");
      $("#" + id).val(colour).trigger("change");
      return false;
    });
  };

  $(document).ready(SimpleShop.events);

}).call(this);
