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
    $("#sub_total").html(sub_total.toFixed(decimalpoints));
    $("#gross_total_amount").html(gross_total_amount.toFixed(decimalpoints));
    // $("#sub_total").html('₹' + sub_total);

    // $("#input-supplier_qty_total").val(qty_total);
    // $("#input-supplier_gross_amount").val(gross_amount);
    // $("#input-supplier_tax_amount").val(tax_amount);
    // $("#input-supplier_sub_total").val(sub_total);

    // $('#inwardStockSubmitBtmSec').hide();
    // if (qty_total > 0) {
    //     $('#inwardStockSubmitBtmSec').show();
    // }
}
