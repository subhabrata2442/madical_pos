$(".onclickselect").click(function () {
    $(this).select();
});

$(document).on("click", ".add_more_size_btn", function () {
    var count = $(".add_more_size_section").find(".card").length;

    var size_html = $("#size-sec-0").html();

    var html =
        '<div class="card" id="add_more_size_row_' +
        count +
        '"><div class="row">';
    html +=
        '<div class="col-md-12" id="size-sec-' +
        count +
        '">' +
        size_html +
        "</div>";
    html +=
        '<div class="col-md-3"><div class="form-group"><label for="cost_rate_' +
        count +
        '" class="form-label">Cost Rate</label><input type="text" class="form-control admin-input cost_rate" id="cost_rate_' +
        count +
        '" name="cost_rate[]" value="0" required autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-3"><div class="form-group"><label for="cost_gst_percent_' +
        count +
        '" class="form-label">Cost GST %</label><input type="text" class="form-control admin-input cost_gst_percent" id="cost_gst_percent_' +
        count +
        '" name="cost_gst_percent[]" value="0" required autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-3"><div class="form-group"><label for="cost_gst_amount_' +
        count +
        '" class="form-label">Cost GST &#8377; </label><input type="text" class="form-control admin-input notallowinput" id="cost_gst_amount_' +
        count +
        '" name="cost_gst_amount[]" value="0" autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-3"><div class="form-group"><label for="cost_price_' +
        count +
        '" class="form-label">Cost Price</label><input type="text" class="form-control admin-input notallowinput" id="cost_price_' +
        count +
        '" name="cost_price[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="extra_charge_' +
        count +
        '" class="form-label">Extra Charge</label><input type="text" class="form-control admin-input extra_charge" id="extra_charge_' +
        count +
        '" name="extra_charge[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="profit_percent_' +
        count +
        '" class="form-label">Profit %</label><input type="text" class="form-control admin-input profit_percent" id="profit_percent_' +
        count +
        '" name="profit_percent[]" value="0" autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="profit_amount_' +
        count +
        '" class="form-label">Profit &#8377; </label><input type="text" class="form-control admin-input notallowinput" id="profit_amount_' +
        count +
        '" name="profit_amount[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="selling_price_' +
        count +
        '" class="form-label">Selling Rate</label><input type="text" class="form-control admin-input notallowinput" id="selling_price_' +
        count +
        '" name="selling_price[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="sell_gst_percent_' +
        count +
        '" class="form-label">Sell GST %</label><input type="text" class="form-control admin-input sell_gst_percent" id="sell_gst_percent_' +
        count +
        '" name="sell_gst_percent[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="sell_gst_amount_' +
        count +
        '" class="form-label">Sell GST &#8377; </label><input type="text" class="form-control admin-input notallowinput" id="sell_gst_amount_' +
        count +
        '" name="sell_gst_amount[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="offer_price_' +
        count +
        '" class="form-label">Offer Price</label><input type="text" class="form-control admin-input number offer_price" id="offer_price_' +
        count +
        '" name="offer_price[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="product_mrp_' +
        count +
        '" class="form-label">Product MRP <a href="javascript:;" data-toggle="tooltip" class="pa-0 ma-0  bold" style="font-size:20px;" data-placement="top" title="Profit is calculated on Offer Price, and not on MRP.  If you do not have Offer Price then you can keep Offer Price same as MRP." data-content="" ><i class="fa fa-eye cursor"></i></a></label><input type="text" class="form-control admin-input number" id="product_mrp_' +
        count +
        '" name="product_mrp[]" value="0"  autocomplete="off"></div></div>';
    html +=
        '<div class="col-md-4"><div class="form-group"><label for="wholesale_price_' +
        count +
        '" class="form-label">Wholesale Price</label><input type="text" class="form-control admin-input number" id="wholesale_price_' +
        count +
        '" name="wholesale_price[]" value="0"  autocomplete="off"></div></div>';
    html += "</div></div>";

    $("#add_more_size_section_row").append(html);
});

$(document).on("click", ".addmoreoption", function () {
    var feature_title = $(this).data("title");
    var feature_type = $(this).data("type");

    $(".dnamic_feature_title").html("Add " + feature_title);
    $(".dnamic_feature_name").html(feature_title);
    $("#product_features_type").val(feature_type);
    $("#product_feature_data_value").val("");
    $("#addproducts_features").modal("show");
});

$(document).on("click", ".modal_close_btn", function () {
    $("#addproducts_features").modal("hide");
});

$(document).on("click", "#productfeaturessave", function () {
    var product_type = $("#product_features_type").val();
    var feature_title = $("#product_feature_data_value").val();

    $.ajax({
        url: prop.ajaxurl,
        type: "post",
        dataType: "json",
        data: {
            product_type: product_type,
            feature_title: feature_title,
            action: "set_feature_option",
            _token: prop.csrf_token,
        },
        beforeSend: function () {},
        success: function (response) {
            if (response.status == 0) {
                toastr.error(response.msg);
            } else {
                if (product_type == "brand") {
                    $("#product_name").html(
                        '<option value="">Select Product</option>'
                    );
                }
                $("#" + product_type).append(
                    $("<option></option>")
                        .attr("value", response.val)
                        .attr("selected", "selected")
                        .text(response.title)
                );

                toastr.success(response.msg);
                $("#addproducts_features").modal("hide");
            }
        },
    });
});

$(document).on("keyup", "#product_barcode", function () {
    var product_barcode = $(this).val();
    var product_id = $("#product_id").val();
    if (product_barcode != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: "post",
            dataType: "json",
            data: {
                product_barcode: product_barcode,
                product_id: product_id,
                action: "check_product_barcode",
                _token: prop.csrf_token,
            },
            beforeSend: function () {},
            success: function (response) {
                if (response.status == 0) {
                    toastr.error(response.msg);
                }
            },
        });
    }
});

$(document).on("change", "#brand", function () {
    var brand_id = $(this).val();
    if (brand_id != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: "post",
            dataType: "json",
            data: {
                brand_id: brand_id,
                action: "get_product_by_brandId",
                _token: prop.csrf_token,
            },
            beforeSend: function () {},
            success: function (response) {
                //alert(response.result.length);
                var html = "";
                if (response.result.length > 0) {
                    for (var i = 0; i < response.result.length; i++) {
                        var id = response.result[i]["id"];
                        var name = response.result[i]["product_name"];
                        html +=
                            '<option value="' +
                            name +
                            '"> ' +
                            name +
                            "</option>";
                    }
                    $("#product_name").html(html);
                } else {
                    toastr.error("No Product Found. Plz add product name");
                }
            },
        });
    }
});

$(document).on("change", "#drugstore", function () {
    var product_barcode = $("#product_barcode").val();
    var drugstore_id = $(this).val();
    if (product_barcode != "" && drugstore_id != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: "post",
            dataType: "json",
            data: {
                product_barcode: product_barcode,
                drugstore_id: drugstore_id,
                action: "check_product_barcode",
                _token: prop.csrf_token,
            },
            beforeSend: function () {},
            success: function (response) {
                if (response.status == 0) {
                    toastr.error(response.msg);
                }
            },
        });
    }
});

function setProfitCalulation(
    row_id,
    product_mrp,
    cost_rate,
    selling_price,
    product_quantity,
    noper_package,
    bonous
) {
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

$(document).on("keyup", ".product_mrp", function () {
    var row_id = $(this).attr("id").split("product_mrp_")[1];
    var product_mrp = $(this).val();
    var cost_rate = $("#cost_rate_" + row_id).val();
    var selling_price = $("#selling_price_" + row_id).val();
    var product_quantity = $("#product_quantity_" + row_id).val();
    var noper_package = $("#noper_package_" + row_id).val();
    var bonous = $("#bonous_" + row_id).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("keyup", ".cost_rate", function () {
    var row_id = $(this).attr("id").split("cost_rate_")[1];
    var product_mrp = $("#product_mrp_" + row_id).val();
    var cost_rate = $(this).val();
    var selling_price = $("#selling_price_" + row_id).val();
    var product_quantity = $("#product_quantity_" + row_id).val();
    var noper_package = $("#noper_package_" + row_id).val();
    var bonous = $("#bonous_" + row_id).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("keyup", ".selling_price", function () {
    var row_id = $(this).attr("id").split("selling_price_")[1];
    var product_mrp = $("#product_mrp_" + row_id).val();
    var cost_rate = $("#cost_rate_" + row_id).val();
    var selling_price = $(this).val();
    var product_quantity = $("#product_quantity_" + row_id).val();
    var noper_package = $("#noper_package_" + row_id).val();
    var bonous = $("#bonous_" + row_id).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("keyup", ".product_quantity", function () {
    var row_id = $(this).attr("id").split("product_quantity_")[1];
    var product_mrp = $("#product_mrp_" + row_id).val();
    var cost_rate = $("#cost_rate_" + row_id).val();
    var selling_price = $("#selling_price_" + row_id).val();
    var product_quantity = $(this).val();
    var noper_package = $("#noper_package_" + row_id).val();
    var bonous = $("#bonous_" + row_id).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("keyup", ".noper_package", function () {
    var row_id = $(this).attr("id").split("noper_package_")[1];
    var product_mrp = $("#product_mrp_" + row_id).val();
    var cost_rate = $("#cost_rate_" + row_id).val();
    var selling_price = $("#selling_price_" + row_id).val();
    var product_quantity = $("#product_quantity_" + row_id).val();
    var noper_package = $(this).val();
    var bonous = $("#bonous_" + row_id).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("keyup", ".bonous", function () {
    var row_id = $(this).attr("id").split("bonous_")[1];
    var product_mrp = $("#product_mrp_" + row_id).val();
    var cost_rate = $("#cost_rate_" + row_id).val();
    var selling_price = $("#selling_price_" + row_id).val();
    var product_quantity = $("#product_quantity_" + row_id).val();
    var noper_package = $("#noper_package_" + row_id).val();
    var bonous = $(this).val();
    setProfitCalulation(
        row_id,
        product_mrp,
        cost_rate,
        selling_price,
        product_quantity,
        noper_package,
        bonous
    );
});

$(document).on("click", ".fetch_image", function (e) {
    $("#upload_photo").click();
});
function preview_image(files) {
    var input = document.getElementById("upload_photo");
    var files = !!input.files ? input.files : [];
    if (!files.length || !window.FileReader) return;
    if (/^image/.test(files[0].type)) {
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files[0]); // read the local file
        reader.onloadend = function () {
            // set image data as background of div
            //$("#form-image-upload").submit()
            //$("#view_image_"+index).attr("src",this.result);
            $("#thumb-image").find("img").attr("src", this.result);
        };
    }
}
