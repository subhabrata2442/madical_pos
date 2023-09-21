function setProfitCalulation() {
    var qty_total = 0;
    var sub_total = 0;
    var gross_total_amount = 0;
    var total_profit = 0;
    var payment_discount = $('#payment_discount').val();
    //console.log($('#payment_discount').val());

    $("#product_record_sec tr").each(function(index, e) {
        var product_id = $(this).attr("id").split("product_")[1];
        var tbl_row = $(this).data("id");
        var product_mrp = 0;
        var selling_type = Number(
            $(this)
            .find("#selling_type_" + product_id)
            .val()
        );
        var product_isChronic =
            $(this)
            .find("#is_chronic_" + product_id)
            .val();
        //console.log(product_isChronic);

        var product_price = Number(
            $(this)
            .find("#product_price_" + product_id)
            .html()
        );
        var chronic_amount = Number(
            $(this)
            .find("#chronic_amount_" + product_id)
            .html()
        );
        if (product_isChronic == 'Yes') {
            product_mrp = chronic_amount;
        } else {
            product_mrp = product_price;
        }
        var cost_rate = Number(
            $(this)
            .find("#product_rate_" + product_id)
            .html()
        );
        var selling_price = Number(
            $(this)
            .find("#product_sellPrice_" + product_id)
            .html()
        );

        gross_total_amount += Number(selling_price);
        var product_quantity = Number(
            $(this)
            .find("#product_quantity_" + product_id)
            .html()
        );
        qty_total += Number(product_quantity);

        var noper_package = Number(
            $(this)
            .find("#product_package_" + product_id)
            .html()
        );

        var total_quantity = 0;

        if (selling_type == 1) {
            total_quantity = product_quantity;
        } else {
            total_quantity = product_quantity * noper_package;
        }

        var bonous = Number(
            $(this)
            .find("#product_bonous_" + product_id)
            .html()
        );
        var product_discount = Number(
            $(this)
            .find("#product_discount_" + product_id)
            .html()
        );

        var net_price = 0;
        var chronic_amount_percentage = 0;
        if (
            product_mrp > 0 &&
            cost_rate > 0 &&
            product_quantity > 0 &&
            noper_package > 0
        ) {
            /* var net_price =
                (Number(product_mrp) * Number(cost_rate) * product_quantity) /
                (Number(product_quantity) + Number(bonous));
            net_price = (Number(net_price) / Number(noper_package)).toFixed(2); */
            var net_price =
                (Number(product_mrp) * Number(cost_rate) * product_quantity) /
                (Number(product_quantity) + Number(bonous));
            // var final_net_price = (Number(net_price) / Number(noper_package)).toFixed(2);
            // var discountAmount = (final_net_price * payment_discount) / 100;

            // net_price = (final_net_price - discountAmount).toFixed(2);
            var discountAmount = (net_price * payment_discount) / 100;

            net_price = (net_price - discountAmount).toFixed();


            if (product_isChronic == 'Yes') {
                // chronic_amount_percentage = (
                //     (Number(net_price) - (Number(chronic_amount)) / net_price) *
                //     Number(100)).toFixed(2);
                chronic_amount_percentage = (
                    ((Number(chronic_amount) - Number(net_price)) / net_price) *
                    Number(100)).toFixed(2);
                // console.log(net_price);
                // console.log("chronic_amount", chronic_amount);
            }
        }
        $("#product_totalNetPrice_" + product_id).val(net_price);
        if (product_discount > 0 && net_price > 0) {
            var discount_value = (net_price / 100) * product_discount;
            net_price = Number(net_price) - Number(discount_value);
            $("#product_discountCost_" + product_id).val(
                discount_value.toFixed(decimalpoints)
            );
        }

        $("#product_netPrice_" + product_id).html(Number(net_price).toFixed(2));
        sub_total += Number(net_price);

        var profitAmount = 0;
        if (selling_price > 0 && product_quantity > 0) {
            profitAmount = (Number(selling_price) - Number(net_price)).toFixed(
                2
            );
        }
        if (profitAmount <= 0) {
            $("#product_profit_" + product_id).css("color", "#c9571b");
        } else {
            $("#product_profit_" + product_id).css("color", "black");
        }
        $("#product_profit_" + product_id).html(profitAmount);
        total_profit += Number(profitAmount);
        var profitPercent = 0;
        if (profitAmount > 0 && product_quantity > 0) {
            profitPercent = (
                (Number(profitAmount) / Number(net_price)) *
                Number(100)
            ).toFixed(2);
        }
        if (profitPercent <= 0) {
            $("#product_profitPercent_" + product_id).css("color", "#c9571b");
        } else {
            $("#product_profitPercent_" + product_id).css("color", "black");
        }
        $("#product_profitPercent_" + product_id).html(profitPercent);
        $("#product_totalQuantity_" + product_id).html(total_quantity);


        $("#chronic_amount_percentage_" + product_id).html(chronic_amount_percentage);

        // console.log("net_price", net_price);

        // console.log("product_id", product_id);
        // console.log("product_mrp", product_mrp);
        // console.log("cost_rate", cost_rate);
        // console.log("selling_price", selling_price);
        // console.log("product_quantity", product_quantity);
        // console.log("noper_package", noper_package);
        // console.log("bonous", bonous);
    });

    //console.log("qty_total", qty_total);
    $("#qty_total").html(qty_total);
    $("#sub_total").html("$" + sub_total.toFixed(decimalpoints));
    $("#gross_total_amount").html(
        "$" + gross_total_amount.toFixed(decimalpoints)
    );

    $("#input-supplier_qty_total").val(qty_total);
    $("#input-supplier_sub_total").val(sub_total.toFixed(decimalpoints));
    $("#input-supplier_gross_amount").val(sub_total.toFixed(decimalpoints));
    $("#input-gross_total_amount").val(
        gross_total_amount.toFixed(decimalpoints)
    );
    $("#total_profit").html(
        "$" + total_profit.toFixed(decimalpoints)
    );
}

$(document).on("keyup", ".product_price", function() {
    var product_id = $(this).attr("id").split("product_price_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setProfitCalulation();
});

$(document).on("keyup", ".product_rate", function() {
    var product_id = $(this).attr("id").split("product_rate_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setProfitCalulation();
});

$(document).on("keyup", ".product_sellPrice", function() {
    var product_id = $(this).attr("id").split("product_sellPrice_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setProfitCalulation();
});

$(document).on("keyup", ".product_quantity", function() {
    var product_id = $(this).attr("id").split("product_quantity_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setProfitCalulation();
});

$(document).on("keyup", ".product_package", function() {
    var product_id = $(this).attr("id").split("product_package_")[1];
    var tbl_row = $(this).closest("tr").data("id");
    setProfitCalulation();
});

$(document).on("keyup", ".product_bonous", function() {
    var product_id = $(this).attr("id").split("product_bonous_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setProfitCalulation();
});

$(document).on("keyup", ".product_discount", function() {
    setProfitCalulation();
});

$(document).on("keyup", ".chronic_amount", function() {
    setProfitCalulation();
});

$(document).on("keyup", ".input-product_totalQuantity", function() {
    var product_id = $(this).attr("id").split("product_totalQuantity_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function() {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function() {
                if (tbl_row != undefined) {
                    var p_qty = Number(
                        $(this)
                        .find("#product_totalQuantity_" + product_id)
                        .html()
                    );

                    if (p_qty == "" || p_qty == 0 || isNaN(p_qty)) {
                        p_qty = 1;
                    }

                    // var total_qty = Number(freeqty) + Number(p_qty);
                    //var total_qty = Number(p_qty);
                    // var product_mrp = Number($(this).find("#product_mrp_" + product_id).html());

                    //console.log("p_qty", p_qty);
                    final_calculation();
                }
            });
    }, 500);
});

$(document).on('change', '.set_product_expiry_date', function() {
    var product_id = $(this).attr("id").split("set_product_expiry_date_")[1];
    var tbl_row = $(this).closest("tr").data("id");
    //console.log($(this).val());
    console.log('product_id', product_id);
    $('#product_expiry_date_' + product_id).val($(this).val());
    //console.log('tbl_row', tbl_row);
});

function final_calculation() {
    $("#no_of_items").html("0");
    $("#qty_total").html("0");
    $("#sub_total").html("0");
    $("#gross_total_amount").html("0");

    var no_of_items = 0;
    var qty_total = 0;
    var sub_total = 0;
    var gross_total_amount = 0;

    $("#product_record_sec tr").each(function(index, e) {
        var product_id = $(this).attr("id").split("product_")[1];
        var tbl_row = $(this).data("id");
        no_of_items += Number(1);

        //console.log(product_id);

        $(this)
            .find("td")
            .each(function() {
                //console.log($(this).attr("id"));

                if ($(this).attr("id") == "product_quantity_" + product_id) {
                    var totalqty = $(this).html();
                    if (totalqty == "") {
                        totalqty = 0;
                    }
                    qty_total += Number(totalqty);
                }
                // if ($(this).attr("id") == "product_netPrice_" + product_id) {
                //     var netPrice = $(this).html();
                //     if ($.isNumeric(netPrice)) {
                //         sub_total += Number(netPrice);
                //     }
                // }
                // if ($(this).attr("id") == "product_sellPrice_" + product_id) {
                //     var sellPrice = $(this).html();
                //     if ($.isNumeric(sellPrice)) {
                //         gross_total_amount += Number(sellPrice);
                //     }
                // }
                // if (
                //     $(this).attr("id") == "product_qty_" + product_id ||
                //     $(this).attr("id") == "free_qty_" + product_id
                // ) {
                //     var totalqty = $(this).html();
                //     if (totalqty == "") {
                //         totalqty = 0;
                //     }
                //     qty_total += Number(totalqty);
                // }
            });
    });

    //alert(qty_total);
    //var finalcost = (total_cost.toFixed(decimalpoints));
    // var gross_amount = (gross_amount.toFixed(decimalpoints));
    // var sub_total = (total_cost.toFixed(decimalpoints));

    // var tax_amount = ((Number(sub_total)) - (Number(gross_amount)));
    // tax_amount = (tax_amount.toFixed(decimalpoints));

    //alert(qty_total);
    $("#no_of_items").html(no_of_items);
    $("#qty_total").html(qty_total);
    $("#sub_total").html("$" + sub_total.toFixed(decimalpoints));
    $("#gross_total_amount").html("$" + sub_total.toFixed(decimalpoints));
    // $("#sub_total").html('₹' + sub_total);

    $("#input-supplier_qty_total").val(qty_total);
    $("#input-supplier_sub_total").val(sub_total.toFixed(decimalpoints));
    $("#input-supplier_gross_amount").val(sub_total.toFixed(decimalpoints));
    $("#input-gross_total_amount").val(sub_total.toFixed(decimalpoints));
    // $("#input-supplier_tax_amount").val(tax_amount);

    // $('#inwardStockSubmitBtmSec').hide();
    // if (qty_total > 0) {
    //     $('#inwardStockSubmitBtmSec').show();
    // }
}