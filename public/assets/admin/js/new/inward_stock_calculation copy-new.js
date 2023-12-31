function setProfitCalulation(
    row_id,
    product_mrp,
    cost_rate,
    selling_price,
    product_quantity,
    noper_package,
    bonous
) {
    console.log("row_id", row_id);
    console.log("product_mrp", product_mrp);
    console.log("cost_rate", cost_rate);
    console.log("selling_price", selling_price);
    console.log("product_quantity", product_quantity);
    console.log("noper_package", noper_package);
    console.log("bonous", bonous);

    var net_price = 0;
    if (cost_rate > 0 && product_quantity > 0) {
        net_price =
            (Number(product_mrp) * Number(cost_rate) * product_quantity) /
            (Number(product_quantity) + Number(bonous));
        net_price = (Number(net_price) / Number(noper_package)).toFixed(2);
    }
    $("#net_price_" + row_id).val(net_price);

    var profitAmount = 0;
    if (selling_price > 0 && product_quantity > 0) {
        profitAmount = (Number(selling_price) - Number(net_price)).toFixed(2);
    }
    if (profitAmount <= 0) {
        $("#profit_amount_" + row_id).css("color", "#c9571b");
    } else {
        $("#profit_amount_" + row_id).css("color", "black");
    }
    $("#profit_amount_" + row_id).val(profitAmount);

    var profitPercent = 0;
    if (profitAmount > 0 && product_quantity > 0) {
        profitPercent = (
            (Number(profitAmount) / Number(net_price)) *
            Number(100)
        ).toFixed(2);
    }
    if (profitPercent <= 0) {
        $("#profit_percent_" + row_id).css("color", "#c9571b");
    } else {
        $("#profit_percent_" + row_id).css("color", "black");
    }
    $("#profit_percent_" + row_id).val(profitPercent);

    // console.log("net_price", net_price);
    // console.log("profitAmount", profitAmount);
    // console.log("profitPercent", profitPercent);
}

$(document).on("keyup", ".product_price", function () {
    var product_id = $(this).attr("id").split("product_price_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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
                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );

                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    setProfitCalulation(
                        tbl_row,
                        product_mrp,
                        cost_rate,
                        selling_price,
                        product_quantity,
                        noper_package,
                        bonous
                    );

                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".product_rate", function () {
    var product_id = $(this).attr("id").split("product_rate_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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
                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );

                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    console.log("product_mrp", product_mrp);
                    console.log("cost_rate", cost_rate);
                    console.log("selling_price", selling_price);
                    console.log("product_quantity", product_quantity);
                    console.log("noper_package", noper_package);
                    console.log("bonous", bonous);
                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".product_sellPrice", function () {
    var product_id = $(this).attr("id").split("product_sellPrice_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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

                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );

                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    console.log("product_mrp", product_mrp);
                    console.log("cost_rate", cost_rate);
                    console.log("selling_price", selling_price);
                    console.log("product_quantity", product_quantity);
                    console.log("noper_package", noper_package);
                    console.log("bonous", bonous);
                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".product_quantity", function () {
    var product_id = $(this).attr("id").split("product_quantity_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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

                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );

                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    console.log("product_mrp", product_mrp);
                    console.log("cost_rate", cost_rate);
                    console.log("selling_price", selling_price);
                    console.log("product_quantity", product_quantity);
                    console.log("noper_package", noper_package);
                    console.log("bonous", bonous);
                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".product_package", function () {
    var product_id = $(this).attr("id").split("product_package_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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

                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );

                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    console.log("product_mrp", product_mrp);
                    console.log("cost_rate", cost_rate);
                    console.log("selling_price", selling_price);
                    console.log("product_quantity", product_quantity);
                    console.log("noper_package", noper_package);
                    console.log("bonous", bonous);
                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".product_bonous", function () {
    var product_id = $(this).attr("id").split("product_bonous_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
                if (tbl_row != undefined) {
                    var product_mrp = Number(
                        $(this)
                            .find("#product_price_" + product_id)
                            .html()
                    );

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

                    var product_quantity = Number(
                        $(this)
                            .find("#product_quantity_" + product_id)
                            .html()
                    );
                    var noper_package = Number(
                        $(this)
                            .find("#product_package_" + product_id)
                            .html()
                    );
                    var bonous = Number(
                        $(this)
                            .find("#product_bonous_" + product_id)
                            .html()
                    );

                    console.log("product_mrp", product_mrp);
                    console.log("cost_rate", cost_rate);
                    console.log("selling_price", selling_price);
                    console.log("product_quantity", product_quantity);
                    console.log("noper_package", noper_package);
                    console.log("bonous", bonous);
                    //final_calculation();
                }
            });
    }, 100);
});

$(document).on("keyup", ".input-product_totalQuantity", function () {
    var product_id = $(this).attr("id").split("product_totalQuantity_")[1];
    var tbl_row = $(this).closest("tr").data("id");

    setTimeout(function () {
        $("#product_record_sec")
            .find("tr[data-id='" + tbl_row + "']")
            .each(function () {
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

function final_calculation() {
    $("#no_of_items").html("0");
    $("#qty_total").html("0");
    $("#sub_total").html("0");
    $("#gross_total_amount").html("0");

    var no_of_items = 0;
    var qty_total = 0;
    var sub_total = 0;
    var gross_total_amount = 0;

    $("#product_record_sec tr").each(function (index, e) {
        var product_id = $(this).attr("id").split("product_")[1];
        var tbl_row = $(this).data("id");
        no_of_items += Number(1);

        //console.log(product_id);

        $(this)
            .find("td")
            .each(function () {
                //console.log($(this).attr("id"));

                if (
                    $(this).attr("id") ==
                    "product_totalQuantity_" + product_id
                ) {
                    var totalqty = $(this).html();
                    if (totalqty == "") {
                        totalqty = 0;
                    }
                    qty_total += Number(totalqty);
                }
                if ($(this).attr("id") == "product_netPrice_" + product_id) {
                    var netPrice = $(this).html();
                    if ($.isNumeric(netPrice)) {
                        sub_total += Number(netPrice);
                    }
                }
                if ($(this).attr("id") == "product_sellPrice_" + product_id) {
                    var sellPrice = $(this).html();
                    if ($.isNumeric(sellPrice)) {
                        gross_total_amount += Number(sellPrice);
                    }
                }
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
