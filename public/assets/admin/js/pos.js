$(document).ready(function() {
    $("#search_barcode_product").focus();
});

$(document).on('click', '.product_qty', function() {
    $(this).select();
});

$(document).ready(function() {
    $(".product_qty").click(function() {
        $(this).select();
    });
});


/*var _keybuffer = "";

$(document).on("keyup", function(e) {
    var code = e.keyCode || e.which;
    _keybuffer += String.fromCharCode(code).trim();
    // trim to last 13 characters
    _keybuffer = _keybuffer.substr(-13);


    if (_keybuffer.length == 13) {
        console.log('13');
        if (!isNaN(parseInt(_keybuffer))) {
            barcodeEntered(_keybuffer);
            _keybuffer = "";
        }
    } else if (_keybuffer.length == 8) {
        console.log('8');
        if (!isNaN(parseInt(_keybuffer))) {
            barcodeEntered(_keybuffer);
            _keybuffer = "";
        }

    }
});*/

$(document).scannerDetection({
    timeBeforeScanTest: 200, // wait for the next character for upto 200ms
    startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
    endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
    avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
    onComplete: function(barcode, qty) {
        //console.log(barcode);
        //console.log(qty);
        barcodeEntered(barcode);
    }
});

/*$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	//ignoreIfFocusOn: 'input', // turn off scanner detection if an input has focus
	onComplete: function(barcode, qty){
		console.log(barcode);
		console.log(qty);
		//barcodeEntered(barcode);
	}, // main callback function
	scanButtonKeyCode: 116, // the hardware scan button acts as key 116 (F5)
	//scanButtonLongPressThreshold: 5, // assume a long press if 5 or more events come in sequence
	//onScanButtonLongPressed: showKeyPad, // callback for long pressing the scan button
	onError: function(string){alert('Error ' + string);}
});*/





// event listener for keyup
function checkTabPress(e) {
    "use strict";
    // pick passed event or global event object if passed one is empty
    e = e || event;
    var activeElement;
    if (e.keyCode == 9) {
        $("#search_barcode_product").focus();
    } else if (e.keyCode == 13) {
        //$(".payBtn").trigger("click");
    }
}

var body = document.querySelector('body');
body.addEventListener('keyup', checkTabPress);


// event listener for keyup
function checkEnterPress(e) {
    "use strict";
    // pick passed event or global event object if passed one is empty
    e = e || event;
    var activeElement;
    if (e.keyCode == 13) {
        var total_quantity = $('#total_quantity-input').val();
        if (total_quantity > 0) {
            if ($(".payWrap.active")[0]) {
                var payment_method = $('#payment_method_type-input').val();
                if (payment_method == 'cash') {
                    $("#calculate_cash_payment_btn").trigger("click");
                } else if (payment_method == 'card') {
                    $("#calculate_card_payment_btn").trigger("click");
                } else if (payment_method == 'gPay') {
                    $("#calculate_gPay_payment_btn").trigger("click");
                }
                //console.log(payment_method);
            } else {
                setTimeout(function() {
                    $(".payBtn").trigger("click");
                    $("#tendered_amount").focus();
                }, 500);

            }
        }
    }
}

//$(document).on('keypress',

var body2 = document.querySelector('body');
body2.addEventListener('keypress', checkEnterPress);



$(function() {

    // open in fullscreen
    $('#fullscreen .requestfullscreen').click(function() {
        $('#fullscreen').fullscreen();
        return false;
    });

    // exit fullscreen
    $('#fullscreen .exitfullscreen').click(function() {
        $.fullscreen.exit();
        return false;
    });
    // document's event
    $(document).bind('fscreenchange', function(e, state, elem) {
        // if we currently in fullscreen mode
        if ($.fullscreen.isFullScreen()) {
            $('#fullscreen .requestfullscreen').hide();
            $('#fullscreen .exitfullscreen').show();
        } else {
            $('#fullscreen .requestfullscreen').show();
            $('#fullscreen .exitfullscreen').hide();
        }
    });
});


$(document).on('click', '.print_off_counter_bill', function() {
    print();
    return false;

    $.ajax({
        url: base_url + '/admin/pos/print_off_counter_invoice',
        type: 'get',
        data: {
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(response) {
            if (response.success == '1') {
                $('#off_counter_invoice-frame').attr('src', response.invoice_url);
                setTimeout(function() {
                    print();
                }, 500);
            } else {
                toastr.error("Something went wrong. Please try later.");
            }
        }
    });
});

function print() {
    var frame = document.getElementById('off_counter_invoice-frame');
    frame.contentWindow.focus();
    frame.contentWindow.print();
    $("#search_barcode_product").focus();


}


$(document).ready(function() {
    $(".payBtn").on('click', function(e) {
        
        //console.log(customer_id);

        var selectedValue = $("input[name='customertype']:checked").val();
        if (selectedValue=='regular') {
            var customer_id = $('#selected_customer_id').val();
            if (customer_id == '0') {
                toastr.error("Select/Add Customer Details!");
                return false;
            }
        }

        $('.payWrapLeft').show();
        $('.payWrapRight').removeClass('tabMenuHideWrapRight');

        var total_quantity = $('#total_quantity-input').val();
        var total_payble_amount = $('#total_payble_amount-input').val();
        $('#due_amount_tendering').val(total_payble_amount);
        $('#tendered_amount').val('');
        $('#tendered_change_amount').val('');

        $('#card_payble_amount').val('0.00');
        $('#upi_payble_amount').val('0.00');


        if (total_quantity > 0) {
            $(".payWrap").toggleClass('active');

            var due_amount_tendering = parseFloat($("#due_amount_tendering").val());
            $("#tendered_amount").val(due_amount_tendering);
            $('#card_payble_amount').val(due_amount_tendering);
            $('#upi_payble_amount').val(due_amount_tendering);
            $("#tendered_change_amount").val('0.00');
            $("#tendered_amount").focus();


        } else {
            toastr.error("Add minimum one product!");
        }
    });

    $(".paymentModalCloseBtn").on('click', function(e) {
        $(".payWrap").removeClass('active');
        $("#search_barcode_product").focus();
    });
});


$(document).on('click', '.paymentmethod_btn', function() {
    $('.paymentmethod_btn').removeClass('active');
    $(this).addClass('active');
    var paymentmethod = $(this).data('paymentmethod');
    $('#upi_paymentmethod_type').val(paymentmethod);
});


/*Start Payment*/
$(document).on('click', '.p-method-tab', function() {
    $('.p-method-tab').removeClass('active');
    var type = $(this).data('type');
    $('#payment_method_type-input').val(type);
    $('.tab_sec').hide();
    $('#' + type + '_payment_sec').show();
    if (type == 'cash') {
        $("#tendered_amount").focus();
    } else {
        $('#' + type + '_payment_sec').find('input').first().focus();
    }

    $(this).addClass('active');
    //console.log('#' + type + '_payment_sec');
});

$(document).on('click', '.tendered_number_btn', function() {
    var number = $(this).data('id');
    if (!$("#tendered_amount").val()) {
        $("#tendered_amount").val(0);
    }

    var tendered_amount = $("#tendered_amount").val();

    if (number == '.') {
        var tendered_amount = parseFloat($("#tendered_amount").val());
        var amount = tendered_amount + number;
        if (tendered_amount > 0) {
            amount = tendered_amount + number;
        }
        $("#tendered_amount").val(amount).trigger("keyup");
    } else if (number == -1) {
        if (tendered_amount.length != 1) {
            var amount = parseFloat($("#tendered_amount").val().substring(0, $("#tendered_amount").val().length - 1));
            $("#tendered_amount").val(amount).trigger("keyup");
        } else {
            $("#tendered_amount").val(0).trigger("keyup");
        }
    } else {
        var amount = number;
        if (tendered_amount > 0) {
            amount = tendered_amount + number;
        }
        $("#tendered_amount").val(amount).trigger("keyup");
    }

    $('#tendered_amount').focus();
});

$(document).on('click', '.tendered_plus_number_btn', function() {
    var number = $(this).data('id');
    if (!$("#tendered_amount").val()) {
        $("#tendered_amount").val(0);
    }

    var tendered_amount = parseFloat($("#tendered_amount").val());

    if (number == '.') {
        var amount = tendered_amount + number;
        if (tendered_amount > 0) {
            amount = tendered_amount + number;
        }
        $("#tendered_amount").val(amount).trigger("keyup");
    } else if (number == -1) {
        if (tendered_amount.length != 1) {
            var amount = parseFloat($("#tendered_amount").val().substring(0, $("#tendered_amount").val().length - 1));
            $("#tendered_amount").val(amount).trigger("keyup");
        } else {
            $("#tendered_amount").val(0).trigger("keyup");
        }
    } else {
        var amount = number;
        if (tendered_amount > 0) {
            amount = tendered_amount + number;
        }
        $("#tendered_amount").val(amount).trigger("keyup");
    }
    $('#tendered_amount').focus();
});

$(document).on('click', '.tendered_number_reset', function() {
    $("#tendered_amount").val("0").trigger("keyup");
    $('#tendered_amount').focus();
});

$(document).on('keyup', '#tendered_amount', function() {
    var due_amount_tendering = parseFloat($("#due_amount_tendering").val());
    var tendered_amount = parseFloat($("#tendered_amount").val());

    $("#tendered_change_amount").val((tendered_amount - due_amount_tendering).toFixed(2));


    console.log(tendered_amount);
});

$(document).ready(function() {
    $("#pos_create_order-form").validate({
        rules: {},
        messages: {},
        errorElement: "em",
        errorPlacement: function(error, element) {},
        highlight: function(element, errorClass, validClass) {},
        unhighlight: function(element, errorClass, validClass) {},
        submitHandler: function(form) {
            var formData = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                url: $('#pos_create_order-form').attr('action'),
                dataType: 'json',
                data: formData,
                success: function(data) {
                    $(".payWrap").removeClass('active');
                },
                beforeSend: function() {
                    $('#ajax_loader').fadeIn();
                },
                complete: function() {
                    $('#ajax_loader').fadeOut();
                    Swal.fire({
                        title: 'Order successfully submitted.',
                        icon: 'success',
                        showDenyButton: false,
                        showCancelButton: false,
                        allowOutsideClick: false
                    }).then((result) => {

                        if (result.isConfirmed) {
                            /* var redirect_url = prop.url + '/admin/pos/today-sales-product/download';
                            window.open(redirect_url, "_blank"); */
                            location.reload();

                        } else if (result.isDenied) {}
                    });
                }
            });
        }
    });
});

$(document).on('click', '#calculate_cash_payment_btn', function() {
    //alert('cash');
    //return false;
    var due_amount_tendering = $('#due_amount_tendering').val();
    var tendered_change_amount = $('#tendered_change_amount').val();
    var tendered_amount = $('#tendered_amount').val();
    if (tendered_change_amount > 0) {
        $('#rupee_due_amount_tendering-input').val(tendered_change_amount);
        $('#rupee_due_amount_tendering').html(tendered_change_amount);
        /*$('.tab_sec').hide();

        $('.payWrapLeft').hide();
        $('.payWrapRight').addClass('tabMenuHideWrapRight');
        $('#rupee_payment_sec').show();*/

        $('.note_coin_count_sec').append('<input type="hidden" name="rupee_type[]" value="coin"><input type="hidden" name="note[]" value="1"><input type="hidden" name="note_qty[]" value="' + tendered_change_amount + '">');

        var tendered_change_amount = $('#tendered_change_amount').val();
        var tendered_amount = $('#tendered_amount').val();
        $('#total_tendered_amount').val(tendered_amount);
        $('#total_tendered_change_amount').val(tendered_change_amount);
        $(".payWrap").toggleClass('active');
        $("#pos_create_order-form").submit();




    } else {

        if (due_amount_tendering > tendered_amount) {
            toastr.error("Make Full Payment");
        } else {
            $('.note_coin_count_sec').html('<input type="hidden" name="rupee_type[]" value="note"><input type="hidden" name="note[]" value="' + tendered_amount + '"><input type="hidden" name="note_qty[]" value="1">');

            var tendered_change_amount = $('#tendered_change_amount').val();
            var tendered_amount = $('#tendered_amount').val();
            $('#total_tendered_amount').val(tendered_amount);
            $('#total_tendered_change_amount').val(tendered_change_amount);
            $(".payWrap").toggleClass('active');
            $("#pos_create_order-form").submit();
        }
    }
});

$(document).on('click', '#calculate_gPay_payment_btn', function() {
    //alert('gPay');
    //return false;
    var upi_payble_amount = $('#upi_payble_amount').val();
    var due_amount_tendering = $('#due_amount_tendering').val();
    if (upi_payble_amount > 0) {
        if (due_amount_tendering != upi_payble_amount) {
            toastr.error("Make Full Payment");
        } else {
            $('#rupee_due_amount_tendering-input').val(upi_payble_amount);
            $('#rupee_due_amount_tendering').html(upi_payble_amount);
            $('.upi_payment_sec').append('<input type="hidden" name="online_payment[upi_payble_amount]" value="' + upi_payble_amount + '">');
            $(".payWrap").toggleClass('active');
            $("#pos_create_order-form").submit();
        }
    } else {
        toastr.error("Make Full Payment");
    }
});

$(document).on('click', '#calculate_card_payment_btn', function() {
    //alert('card');
    //return false;
    var upi_payble_amount = $('#card_payble_amount').val();
    var due_amount_tendering = $('#due_amount_tendering').val();

    var card_type = $('#card_type').val();
    var card_number = $('#card_number').val();
    var card_invoice_number = $('#card_invoice_number').val();

    if (card_type != 'other') {
        if (card_number == '') {
            toastr.error("Enter Card No.");
            return false;
        } else if (card_invoice_number == '') {
            toastr.error("Enter Invoice No.");
            return false;
        }
    }



    if (upi_payble_amount > 0) {
        if (due_amount_tendering != upi_payble_amount) {
            toastr.error("Make Full Payment");
        } else {
            $('#rupee_due_amount_tendering-input').val(upi_payble_amount);
            $('#rupee_due_amount_tendering').html(upi_payble_amount);
            $('.card_details_payment_sec').append('<input type="hidden" name="online_payment[upi_payble_amount]" value="' + upi_payble_amount + '"><input type="hidden" name="online_payment[card_type]" value="' + card_type + '"><input type="hidden" name="online_payment[card_number]" value="' + card_number + '"><input type="hidden" name="online_payment[card_invoice_number]" value="' + card_invoice_number + '">');
            $(".payWrap").toggleClass('active');
            $("#pos_create_order-form").submit();
        }
    } else {
        toastr.error("Make Full Payment");
    }
});

$(document).on('keyup', '.rupee_count_input', function() {
    var rupee_type = $(this).data('type');
    var rupee_qty = $(this).val();
    var rupee_id = '';
    var rupee_val = '';
    if (rupee_qty == '') {
        rupee_qty = 0;
    }
    if (rupee_type == 'note') {
        rupee_id = $(this).attr('id').split('rupee_')[1];
        rupee_val = rupee_id.split('-')[0];
        var total_amount = Number(rupee_val) * Number(rupee_qty);
        $('#rupee_' + rupee_val).val(total_amount);
        $('#amount_per_note-rupee_' + rupee_val).html(total_amount);
    } else {
        rupee_id = $(this).attr('id').split('coin_')[1];
        rupee_val = rupee_id.split('-')[0];
        var total_amount = Number(rupee_val) * Number(rupee_qty);
        $('#coin_' + rupee_val).val(total_amount);
        $('#amount_per_note-coin_' + rupee_val).html(total_amount);
    }

});

function backToLink(id, type) {
    $('.payWrapLeft').show();
    $('.payWrapRight').removeClass('tabMenuHideWrapRight');
    if (type == 'p_tap') {
        $('.tab_sec').hide();
        $('#' + id).show();
    }
}

function final_payment_submit(type) {
    var total_rupee_qty = 0;
    var total_rupee_amount = 0;
    if (type == 'cash') {
        $('#payment_method_type-input').val('cash');
        $('.note_coin_count_sec').html('');
        $(".rupee_count_input").each(function(index, e) {
            var rupee_type = $(this).data('type');
            var rupee_qty = $(this).val();
            var rupee_id = '';
            var rupee_val = '';

            if (rupee_qty != '') {
                if (rupee_qty > 0) {
                    if (rupee_type == 'note') {
                        rupee_id = $(this).attr('id').split('rupee_')[1];
                        rupee_val = rupee_id.split('-')[0];


                        if ($.isNumeric(rupee_qty)) {
                            total_rupee_qty += (Number(rupee_qty));
                        }
                        if ($.isNumeric(rupee_val)) {
                            total_rupee_amount += (Number(rupee_val) * Number(rupee_qty));
                        }
                        $('.note_coin_count_sec').append('<input type="hidden" name="rupee_type[]" value="note"><input type="hidden" name="note[]" value="' + rupee_val + '"><input type="hidden" name="note_qty[]" value="' + rupee_qty + '">');

                        //console.log(rupee_val);
                    } else {
                        rupee_id = $(this).attr('id').split('coin_')[1];
                        rupee_val = rupee_id.split('-')[0];
                        if ($.isNumeric(rupee_qty)) {
                            total_rupee_qty += (Number(rupee_qty));
                        }
                        if ($.isNumeric(rupee_val)) {
                            total_rupee_amount += (Number(rupee_val) * Number(rupee_qty));
                        }
                        $('.note_coin_count_sec').append('<input type="hidden" name="rupee_type[]" value="coin"><input type="hidden" name="note[]" value="' + rupee_val + '"><input type="hidden" name="note_qty[]" value="' + rupee_qty + '">');
                        //console.log(rupee_val);
                    }
                }
            }
        });

        var tendered_change_amount = $('#rupee_due_amount_tendering-input').val();
        if (total_rupee_amount > 0) {
            if (total_rupee_amount != tendered_change_amount) {
                toastr.error("Something error occurs!");
            } else {
                var tendered_change_amount = $('#tendered_change_amount').val();
                var tendered_amount = $('#tendered_amount').val();
                $('#total_tendered_amount').val(tendered_amount);
                $('#total_tendered_change_amount').val(tendered_change_amount);
                $("#pos_create_order-form").submit();
            }
        } else {
            toastr.error("Something error occurs!");
        }
    }
}

/*$('.note_coin_count_sec').html('');
	
$(".rupee_count_input").each(function(index, e) {
        var rupee_type	= $(this).data('type');
		var rupee_qty	= $(this).val();
		var rupee_id	= '';
		var rupee_val	= '';
		
		if(rupee_qty!=''){
			if(rupee_qty>0){
				if(rupee_type=='note'){
					rupee_id	= $(this).attr('id').split('rupee_')[1];
					rupee_val	= rupee_id.split('-')[0];
					$('.note_coin_count_sec').append('<input type="hidden" name="note[]" value="'+rupee_val+'"><input type="hidden" name="note_qty[]" value="'+rupee_qty+'">');
					
					//console.log(rupee_val);
				}else{
					rupee_id	= $(this).attr('id').split('coin_')[1];
					rupee_val	= rupee_id.split('-')[0];
					$('.note_coin_count_sec').append('<input type="hidden" name="note[]" value="'+rupee_val+'"><input type="hidden" name="note_qty[]" value="'+rupee_qty+'">');
					//console.log(rupee_val);
				}
			}
		}
    });
*/





/*End Payment*/

//allow only integer and one point in td editable
function check_character(event) {
    if ((event.which != 46 || $(event.target).text().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
}




$(document).on('click', '.addTopSellingProduct', function() {
    var product_id = $(this).attr('data-id');
    $.ajax({
        url: prop.ajaxurl,
        type: 'post',
        data: {
            product_id: product_id,
            action: 'get_sell_product_byId',
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == '1') {
                var html = '';
                var item_detail = response.product_result;

                var barcode = '';
                if (item_detail.product_barcode != null || item_detail.product_barcode != undefined) {
                    barcode = item_detail.product_barcode;
                }

                var product_name = '';
                if (item_detail.brand_name != null || item_detail.brand_name != undefined) {
                    product_name = item_detail.brand_name;
                }

                var product_id = item_detail.branch_stock_product_id;

                var item_prices_length = item_detail.item_prices.length;

                var w_stock = 0;
                var c_stock = 0;
                var product_mrp = 0;
                var product_price_id = 0;

                var option_html = '';

                if (item_prices_length > 1) {
                    w_stock = item_detail.item_prices[0].w_qty;
                    c_stock = item_detail.item_prices[0].c_qty;
                    product_mrp = item_detail.item_prices[0].product_mrp;
                    product_price_id = item_detail.item_prices[0].price_id;

                    for (var p = 0; item_detail.item_prices.length > p; p++) {
                        option_html += '<option value="' + item_detail.item_prices[p] + '">' + item_detail.item_prices[p] + '</option>';
                        option_html += '<option value="' + item_detail.item_prices[p] + '">' + item_detail.item_prices[p] + '</option>';
                    }
                } else {
                    w_stock = item_detail.item_prices[0].w_qty;
                    c_stock = item_detail.item_prices[0].c_qty;
                    product_mrp = item_detail.item_prices[0].product_mrp;
                    product_price_id = item_detail.item_prices[0].price_id;
                    option_html += '<option value="' + product_mrp + '">' + product_mrp + '</option>';

                }

                //alert(w_stock);

                var item_row = 0;
                if ($('#product_sell_record_sec tr').length == 0) {
                    item_row++;
                } else {
                    var max = 0;
                    $("#product_sell_record_sec tr").each(function() {
                        var value = parseInt($(this).data('id'));
                        max = (value > max) ? value : max;
                    });
                    item_row = max + 1;
                }

                if (stock_type == 'w') {
                    stock = w_stock;
                } else {
                    stock = c_stock;
                }

                if (stock > 0) {
                    var same_item = 0;
                    $("#product_sell_record_sec tr").each(function() {
                        var row_product_id = $(this).attr('id').split('_')[2];
                        //console.log(row_product_id);
                        if (row_product_id == product_id) {
                            same_item = 1;

                            var qty = $('#product_qty_' + product_id).val();
                            var item_qty = Number(qty) + 1;

                            if (Number(item_qty) > Number(stock)) {
                                $('#product_qty_' + product_id).val(stock);
                                toastr.error("Entered Qty should not be greater than Stock");
                                return false;
                            } else {
                                $('#product_qty_' + product_id).val(item_qty);
                                var unit_price = $("#product_unit_price_amount_" + product_id).val();
                                var discount_percent = $("#product_disc_percent_" + product_id).val();
                                var total_discount = (Number(unit_price) * Number(item_qty)) * Number(discount_percent) / 100;

                                if (Number(discount_percent) != 0 || discount_percent != '') {
                                    $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                                }
                                var total_selling_cost = Number(unit_price) * Number(item_qty);
                                var discount_amount = Number(total_selling_cost) - Number(total_discount);
                                var total_amount = Number(discount_amount)
                                $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                                $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                            }

                            total_cal();
                        }
                    });

                    if (same_item == 0) {
                        html += '<tr id="sell_product_' + product_id + '" data-id="' + item_row + '">' +
                            '<input type="hidden" name="product_id[]" value="' + product_id + '">' +
                            '<input type="hidden" id="product_w_stock_' + product_id + '" value="' + w_stock + '">' +
                            '<input type="hidden" id="product_c_stock_' + product_id + '" value="' + c_stock + '">' +
                            '<input type="hidden" name="product_total_amount[]" id="input-product_total_amount_' + product_id + '" value="' + product_mrp + '">' +
                            '<input type="hidden" name="product_price_id[]" id="input-product_price_id_' + product_id + '" value="' + product_price_id + '">' +
                            '<input type="hidden" name="product_barcode[]" id="input-product_barcode_' + product_id + '" value="' + barcode + '">' +
                            '<input type="hidden" name="product_name[]" id="input-product_name_' + product_id + '" value="' + product_name + '">' +
                            '<td>' + barcode + '</td>' +
                            '<td class="proName">' + product_name + '</td>' +
                            '<td id="product_stock_' + product_id + '">W-' + w_stock + '</br>S-' + c_stock + '</td>' +
                            '<td id="product_mrp_' + product_id + '">' + product_mrp + '</td>' +
                            '<td><input type="number" name="product_qty[]" id="product_qty_' + product_id + '" class="input-3 product_qty" value="1"></td>' +
                            '<td><input type="text" name="product_disc_percent[]" id="product_disc_percent_' + product_id + '" class="input-3 product_disc_percent" value="0"></td>' +
                            '<td><input type="text" name="product_disc_amount[]" id="product_disc_amount_' + product_id + '" class="input-3 product_disc_amount" value="0"></td>' +
                            '<td class="relative"><select name="product_unit_price[]" id="product_unit_price_' + product_id + '" class="product_unit_price">' + option_html + '</select>' +
                            '<input type="text" class="product_unit_price_amount input-3" id="product_unit_price_amount_' + product_id + '" value="' + product_mrp + '" readonly="readonly"></td>' +
                            '<td id="product_total_amount_' + product_id + '">' + product_mrp + '</td>' +
                            '<td><a href="javascript:;" onclick="remove_sell_item(' + item_row + ');"><i class="fas fa-times-circle"></i></a></td>' +
                            '</tr>';
                        $("#product_sell_record_sec").prepend(html);
                        total_cal();
                    }
                } else {
                    toastr.error("Stock not available for this Product");
                }
            } else {
                toastr.error("Stock not available for this Product");
            }
            $("#search_barcode_product").focus();
        }
    });
});

$(document).on('click', '#applyChargeBtn', function() {
    var total_payble_amount = $('#total_payble_amount-input').val();
    $("#charge_total_payable").html('$'+ Number(total_payble_amount).toFixed(2));

    var charge_amt = $('#charge_amt-input').val();
    $('#charge_amt').val(charge_amt);

    $('#modal-applyCharges').modal('show');
});

$(document).ready(function() {
    $("#applyCharge-form").validate({
        rules: {
            charge_amt: "required",
        },
        messages: {
            //promo: "Required",
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("has-success").removeClass("has-error");
        },
        submitHandler: function(form) {
            var gross_total_amount = $('#gross_total_input').val();
            var charge_amt = $('#charge_amt').val();

            var charge_amount = Number(gross_total_amount) + Number(charge_amt);
            var total_amount = Number(charge_amount);

            $('#total_payble_amount-input').val(total_amount);
            $("#total_payble_amount").html('$'+ formatNumber(total_amount));
            $('#charge_amt-input').val(charge_amt);

            $('#gross_total_amount-input').val(total_amount);

            $("#extraCharge").html('$'+ formatNumber(0 + parseFloat(charge_amt)));

            $("#sub_total_mrp").html('$'+ formatNumber(total_amount));
            $("#sub_total_mrp-input").val(total_amount);

            $('#modal-applyCharges').modal('hide');

        }
    });
});

$(document).on('keyup', '#charge_amt', function() {
    var gross_total_amount = $('#gross_total_amount-input').val();
    var charge_amt = $(this).val();

    if (Number(gross_total_amount) > 0) {
        var charge_amount = Number(gross_total_amount) + Number(charge_amt);
        $("#charge_total_payable").html('$'+ Number(charge_amount).toFixed(2));
    } else {
        toastr.error("Something Error Occurs!");
        return false;
    }
});



$(document).on('click', '#applyDiscountBtn', function() {

    var total_payble_amount = $('#total_payble_amount-input').val();
    $("#discount_total_payable").html('$'+ Number(total_payble_amount).toFixed(2));

    var discount_percent = $('#selling_special_discount_percent-input').val();
    var total_discount = $('#selling_special_discount_amt-input').val();

    $('#special_discount_percent').val(discount_percent);
    $('#special_discount_amt').val(total_discount);


    $('#modal-applyDiscount').modal('show');
});

$(document).ready(function() {
    $("#applyDiscount-form").validate({
        rules: {
            special_discount_percent: "required",
            special_discount_amt: "required",
        },
        messages: {
            //promo: "Required",
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("has-success").removeClass("has-error");
        },
        submitHandler: function(form) {
            var gross_total_amount = $('#actual_amount').val();
            var discount_percent = $('#special_discount_percent').val();
            var total_discount = $('#special_discount_amt').val();

            //var total_discount = Number(gross_total_amount) * Number(discount_percent) / 100;

            var discount_amount = Number(gross_total_amount) - Number(total_discount);
            var total_amount = Number(discount_amount);

            var charge_amt = $("#charge_amt-input").val();

            total_amount = (total_amount+Number(charge_amt));

            $('#total_payble_amount-input').val(total_amount);
            $("#total_payble_amount").html('$'+ formatNumber(total_amount));

            $('#selling_special_discount_percent-input').val(discount_percent);
            $('#selling_special_discount_amt-input').val(total_discount);

            $("#total_discount_amount").html('$'+ formatNumber(0 + parseFloat(total_discount)));
            $("#total_discount_amount-input").html(total_discount);

            $("#sub_total_mrp").html('$'+ formatNumber(total_amount));
            $("#sub_total_mrp-input").val(total_amount);

            $('#gross_total_amount-input').val(discount_amount);
            $('#gross_total_input').val(discount_amount);

            $('#modal-applyDiscount').modal('hide');

        }
    });
});


$(document).on('keyup', '#special_discount_percent', function() {
    var gross_total_amount = $('#gross_total_amount-input').val();
    var discount_percent = $(this).val();
    var charge_amt = $('#charge_amt-input').val();

    if (Number(gross_total_amount) > 0) {
        if (Number(discount_percent) > 100) {
            toastr.error("Something Error Occurs!");
            return false;
        } else {
            var total_discount = Number(gross_total_amount) * Number(discount_percent) / 100;
            var discount_amount = Number(gross_total_amount) - Number(total_discount);
            var total_amount = Number(discount_amount);

            $("#special_discount_amt").val(total_discount);
            $("#discount_total_payable").html('$'+ Number(total_amount).toFixed(2));
        }

    } else {
        toastr.error("Something Error Occurs!");
        return false;
    }
});

$(document).on('keyup', '#special_discount_amt', function() {
    var gross_total_amount = $('#gross_total_amount-input').val();
    var discount_percent = 0;
    var charge_amt = $('#charge_amt-input').val();
    var total_discount = $(this).val();

    if (Number(gross_total_amount) > 0) {
        if (total_discount != '') {
            var discount_percent = (Number(total_discount) * 100) / (Number(gross_total_amount));
            var discount_amount = Number(gross_total_amount) - Number(total_discount);
            var total_amount = Number(discount_amount);

            $("#discount_total_payable").html('$'+ Number(total_amount).toFixed(2));
        } else {
            $("#special_discount_percent").val(0);
            toastr.error("Something Error Occurs!");
            return false;
        }
    } else {
        toastr.error("Something Error Occurs!");
        return false;
    }
    $("#special_discount_percent").val(Number(discount_percent).toFixed(2));
});

$(document).on('click', '.create_customer_btn', function() {
    $('#modal_createCustomer').modal('show');
});
$(document).ready(function() {
    $("#create_customer_form").validate({
        rules: {
            customer_name: "required",
            customer_mobile: "required",
        },
        messages: {
            //promo: "Required",
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).addClass("has-success").removeClass("has-error");
        },
        submitHandler: function(form) {
            var formData = new FormData($(form)[0]);
            console.log(formData);
            $.ajax({
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                url: $('#create_customer_form').attr("action"),
                dataType: 'json',
                data: formData,
                success: function(data) {
                    console.log(data);
                    if (data[0].success == 1) {
                        $('#cd_customer_name span').html(data[0].customer_name);
                        $('#cd_customer_number span').html(data[0].customer_mobile);
                        $('#selected_customer_id').val(data[0].customer_id);
                        Swal.fire({
                            icon: 'success',
                            title: 'Customer Created successfully',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        $('#modal_createCustomer').modal('hide');
                        //window.location.replace("{{ route('admin.supplier.list') }}");
                    } else {
                        Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data[0].error_message,
                            })
                            //alert(data[0].error_message);
                    }
                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                },
                beforeSend: function() {
                    $(".loadingSpan").show();
                },
                complete: function() {
                    $(".loadingSpan").hide();
                }
            });
        }
    });
})


function barcodeEntered(value) {
    var search = value;
    if (search != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                search: search,
                action: 'get_sell_product_barcode_search',
                _token: prop.csrf_token
            },
            dataType: 'json',
            success: function(response) {
                var len = response.result.length;
                if (len > 0) {
                    var product_id = response.result[0]['id'];
                    var product_name = response.result[0]['product_name'];
                    $("#search_barcode_product").val('');
                    setProductRow('<li value="' + product_id + '">' + product_name + '</li>')
                        /* $.ajax({
                            url: prop.ajaxurl,
                            type: 'post',
                            data: {
                                product_id: product_id,
                                action: 'get_sell_product_byId',
                                _token: prop.csrf_token
                            },
                            dataType: 'json',
                            success: function(response) {

                                if (response.status == '1') {
                                    var html = '';
                                    var item_detail = response.product_result;

                                    var barcode = '';
                                    if (item_detail.product_barcode != null || item_detail.product_barcode != undefined) {
                                        barcode = item_detail.product_barcode;
                                    }

                                    var product_name = '';
                                    if (item_detail.brand_name != null || item_detail.brand_name != undefined) {
                                        product_name = item_detail.brand_name;
                                    }

                                    var product_id = item_detail.branch_stock_product_id;

                                    var item_prices_length = item_detail.item_prices.length;

                                    var w_stock = 0;
                                    var c_stock = 0;
                                    var product_mrp = 0;
                                    var product_price_id = 0;

                                    var option_html = '';

                                    if (item_prices_length > 1) {
                                        w_stock = item_detail.item_prices[0].w_qty;
                                        c_stock = item_detail.item_prices[0].c_qty;
                                        product_mrp = item_detail.item_prices[0].product_mrp;
                                        product_price_id = item_detail.item_prices[0].price_id;

                                        for (var p = 0; item_detail.item_prices.length > p; p++) {
                                            option_html += '<option value="' + item_detail.item_prices[p] + '">' + item_detail.item_prices[p] + '</option>';
                                            option_html += '<option value="' + item_detail.item_prices[p] + '">' + item_detail.item_prices[p] + '</option>';
                                        }
                                    } else {
                                        w_stock = item_detail.item_prices[0].w_qty;
                                        c_stock = item_detail.item_prices[0].c_qty;
                                        product_mrp = item_detail.item_prices[0].product_mrp;
                                        product_price_id = item_detail.item_prices[0].price_id;
                                        option_html += '<option value="' + product_mrp + '">' + product_mrp + '</option>';

                                    }

                                    //alert(w_stock);

                                    var item_row = 0;
                                    if ($('#product_sell_record_sec tr').length == 0) {
                                        item_row++;
                                    } else {
                                        var max = 0;
                                        $("#product_sell_record_sec tr").each(function() {

                                            var value = parseInt($(this).data('id'));
                                            max = (value > max) ? value : max;
                                        });
                                        item_row = max + 1;
                                    }

                                    if (stock_type == 'w') {
                                        stock = w_stock;
                                    } else {
                                        stock = c_stock;
                                    }

                                    if (stock > 0) {
                                        var same_item = 0;
                                        $("#product_sell_record_sec tr").each(function() {
                                            var row_product_id = $(this).attr('id').split('_')[2];
                                            console.log(row_product_id);
                                            if (row_product_id == product_id) {
                                                same_item = 1;

                                                var qty = $('#product_qty_' + product_id).val();
                                                var item_qty = Number(qty) + 1;

                                                if (Number(item_qty) > Number(stock)) {
                                                    $('#product_qty_' + product_id).val(stock);
                                                    toastr.error("Entered Qty should not be greater than Stock");
                                                    return false;
                                                } else {
                                                    $('#product_qty_' + product_id).val(item_qty);
                                                    var unit_price = $("#product_unit_price_amount_" + product_id).val();
                                                    var discount_percent = $("#product_disc_percent_" + product_id).val();
                                                    var total_discount = (Number(unit_price) * Number(item_qty)) * Number(discount_percent) / 100;

                                                    if (Number(discount_percent) != 0 || discount_percent != '') {
                                                        $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                                                    }
                                                    var total_selling_cost = Number(unit_price) * Number(item_qty);
                                                    var discount_amount = Number(total_selling_cost) - Number(total_discount);
                                                    var total_amount = Number(discount_amount)
                                                    $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                                                    $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                                                }

                                                total_cal();
                                            }
                                        });

                                        if (same_item == 0) {
                                            html += '<tr id="sell_product_' + product_id + '" data-id="' + item_row + '">' +
                                                '<input type="hidden" name="product_id[]" value="' + product_id + '">' +
                                                '<input type="hidden" id="product_w_stock_' + product_id + '" value="' + w_stock + '">' +
                                                '<input type="hidden" id="product_c_stock_' + product_id + '" value="' + c_stock + '">' +
                                                '<input type="hidden" name="product_total_amount[]" id="input-product_total_amount_' + product_id + '" value="' + product_mrp + '">' +
                                                '<input type="hidden" name="product_price_id[]" id="input-product_price_id_' + product_id + '" value="' + product_price_id + '">' +
                                                '<input type="hidden" name="product_barcode[]" id="input-product_barcode_' + product_id + '" value="' + barcode + '">' +
                                                '<input type="hidden" name="product_name[]" id="input-product_name_' + product_id + '" value="' + product_name + '">' +
                                                '<td>' + barcode + '</td>' +
                                                '<td class="proName">' + product_name + '</td>' +
                                                '<td id="product_stock_' + product_id + '">W-' + w_stock + '</br>S-' + c_stock + '</td>' +
                                                '<td id="product_mrp_' + product_id + '">' + product_mrp + '</td>' +
                                                '<td><input type="number" name="product_qty[]" id="product_qty_' + product_id + '" class="input-3 product_qty" value="1"></td>' +
                                                '<td><input type="text" name="product_disc_percent[]" id="product_disc_percent_' + product_id + '" class="input-3 product_disc_percent" value="0"></td>' +
                                                '<td><input type="text" name="product_disc_amount[]" id="product_disc_amount_' + product_id + '" class="input-3 product_disc_amount" value="0"></td>' +
                                                '<td class="relative"><select name="product_unit_price[]" id="product_unit_price_' + product_id + '" class="product_unit_price">' + option_html + '</select>' +
                                                '<input type="text" class="product_unit_price_amount input-3" id="product_unit_price_amount_' + product_id + '" value="' + product_mrp + '" readonly="readonly"></td>' +
                                                '<td id="product_total_amount_' + product_id + '">' + product_mrp + '</td>' +
                                                '<td><a href="javascript:;" onclick="remove_sell_item(' + item_row + ');"><i class="fas fa-times-circle"></i></a></td>' +
                                                '</tr>';
                                            $("#product_sell_record_sec").prepend(html);
                                            total_cal();
                                        }
                                    } else {
                                        toastr.error("Stock not available for this Product");
                                    }
                                } else {
                                    toastr.error("Stock not available for this Product");
                                }
                                $("#search_barcode_product").focus();
                            }
                        }); */
                } else {
                    $("#search_barcode_product").val('');
                    toastr.error("Stock not available for this Product");
                }
            }
        });
    } else {
        $("#product_search_result").empty();
        $("#search_barcode_product").focus();
    }
}



$(document).ready(function() {
    $("#search_product").keyup(function() {
        var search = $(this).val();
        if (search != "") {
            $.ajax({
                url: prop.ajaxurl,
                type: 'post',
                data: {
                    search: search,
                    action: 'get_sell_product_search',
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.result.length;

                    $("#product_search_result").empty();
                    for (var i = 0; i < len; i++) {
                        //response.result[i]['product_barcode'] + '-' + 
                        var id = response.result[i]['id'];
                        var name = response.result[i]['product_name'] + ' / ' + response.result[i]['product_barcode'];
                        $("#product_search_result").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#product_search_result li").bind("click", function() {
                        $('.loader_section').show();
                        setProductRow(this);
                    });
                }
            });
        } else {
            $("#product_search_result").empty();
            $("#search_barcode_product").focus();
        }
    });

    /*$("#search_barcode_product").keydown(function() {
    	console.log('d');
    });*/

    /*$("#search_barcode_product").input(function() {
    	
    	console.log('d');
    });*/

    /*$('#search_barcode_product').on('input', function(){ alert('d'); });*/

    /*$('#search_barcode_product').on('input', function(){
    	
    });*/
});

// Set Text to search box and get details
function setProductRow(element) {
    var value = $(element).text();
    var product_id = $(element).val();
    //console.log(element);
    $("#search_product").val('');
    $("#product_search_result").empty();
    $("#product_search_result_top").empty();
    $(".top-product-src-input").val('');

    // Request User Details
    $.ajax({
        url: prop.ajaxurl,
        type: 'post',
        data: {
            product_id: product_id,
            action: 'get_sell_product_byId',
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(response) {

            if (response.status == '1') {
                var html = '';
                var item_detail = response.product_result;
                //console.log(item_detail);
                var barcode = '';
                if (item_detail.product_barcode != null || item_detail.product_barcode != undefined) {
                    barcode = item_detail.product_barcode;
                }

                var product_name = '';
                if (item_detail.product_name != null || item_detail.product_name != undefined) {
                    product_name = item_detail.product_name;
                }
                var brand_name = '';
                if (item_detail.brand_name != null || item_detail.brand_name != undefined) {
                    brand_name = item_detail.brand_name;
                }

                var product_id = item_detail.branch_stock_product_id;

                var item_prices_length = item_detail.item_prices.length;

                var stock = 0;
                var c_stock = 0;
                var w_stock = 0;
                var product_mrp = 0;
                var product_price_id = 0;
                var selling_by_name = '';
                

                var option_html = '';

                if (item_prices_length > 1) {
                    // w_stock = item_detail.item_prices[0].w_qty;
                    //c_stock = item_detail.item_prices[0].c_qty;
                    /* stock = item_detail.item_prices[0].t_qty;
                    product_mrp = item_detail.item_prices[0].selling_price;
                    product_price_id = item_detail.item_prices[0].price_id; */
                    var price_modal_html = '';
                    for (var p = 0; item_detail.item_prices.length > p; p++) {
                        if(item_detail.item_prices[p].t_qty!=0){
                            option_html += '<option value="' + item_detail.item_prices[p].selling_price + '" data-stock_product_id="' + item_detail.item_prices[p].price_id + '">' + item_detail.item_prices[p].selling_price + '</option>';
                            //option_html += '<option value="' + item_detail.item_prices[p].product_mrp + '">' + item_detail.item_prices[p].product_mrp + '</option>';
                            price_modal_html += `<tr>
                                <td>${item_detail.item_prices[p].brand_name}</td>
                                <td>${item_detail.item_prices[p].product_name}</td>
                                <td>${item_detail.item_prices[p].product_barcode}</td>
                                <td>${item_detail.item_prices[p].selling_by_name}</td>
                                <td>${item_detail.item_prices[p].t_qty}</td>
                                <td>${formatNumber(0 + parseFloat(item_detail.item_prices[p].selling_price))}</td>
                                <td>${item_detail.item_prices[p].product_expiry_date}</td>
                                <td><button type="button" class="product-select select_product_item" data-item_id="${item_detail.item_prices[p].price_id}">select</button></td>
                            </tr>`;
                        }
                    }
                    $('#price_item_table tbody').html(price_modal_html);
                    $('#modal_multiple_price_list').modal('show');
                } else {
                    stock = item_detail.item_prices[0].t_qty;
                    //c_stock = item_detail.item_prices[0].c_qty;
                    product_mrp = item_detail.item_prices[0].selling_price;
                    product_price_id = item_detail.item_prices[0].price_id;
                    selling_by_name = item_detail.item_prices[0].selling_by_name;
                    option_html += '<option value="' + product_mrp + '" data-stock_product_id="' + product_price_id + '">' + product_mrp + '</option>';


                    var product_net_price = 0;
                    product_net_price = item_detail.item_prices[0].net_price;
                    var is_chronic = item_detail.item_prices[0].is_chronic;
                    var product_chronic_amount = 0;
                    product_chronic_amount = item_detail.item_prices[0].chronic_amount;


                    var item_row = 0;
                    if ($('#product_sell_record_sec tr').length == 0) {
                        item_row++;
                    } else {
                        var max = 0;
                        $("#product_sell_record_sec tr").each(function() {
                            var value = parseInt($(this).data('id'));
                            max = (value > max) ? value : max;
                        });
                        item_row = max + 1;
                    }

                    if (stock > 0) {
                        var same_item = 0;
                        $("#product_sell_record_sec tr").each(function() {
                            var row_product_id = $(this).attr('id').split('_')[2];
                            //console.log(row_product_id);
                            if (row_product_id == product_id) {
                                same_item = 1;

                                var qty = $('#product_qty_' + product_id).val();
                                var item_qty = Number(qty) + 1;

                                if (Number(item_qty) > Number(stock)) {
                                    $('#product_qty_' + product_id).val(stock);
                                    toastr.error("Entered Qty should not be greater than Stock");
                                    return false;
                                } else {
                                    $('#product_qty_' + product_id).val(item_qty);
                                    var unit_price = $("#product_unit_price_amount_" + product_id).val();
                                    //console.log(unit_price);
                                    var discount_percent = $("#product_disc_percent_" + product_id).val();
                                    var total_discount = (Number(unit_price) * Number(item_qty)) * Number(discount_percent) / 100;

                                    if (Number(discount_percent) != 0 || discount_percent != '') {
                                        $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                                    }
                                    var total_selling_cost = Number(unit_price) * Number(item_qty);
                                    var discount_amount = Number(total_selling_cost) - Number(total_discount);
                                    var total_amount = Number(discount_amount)
                                    $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                                    $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                                }

                                total_cal();
                            }
                        });

                        if (same_item == 0) {
                            html += `<tr id="sell_product_${product_id}" data-id="${item_row}">
                                        <input type="hidden" name="product_id[]" value="${product_id}">
                                        <input type="hidden" id="product_stock_${product_id}" value="${stock}">
                                        <input type="hidden" name="product_total_amount[]" id="input-product_total_amount_${product_id}" value="${product_mrp}">
                                        <input type="hidden" name="product_price_id[]" id="input-product_price_id_${product_id}" value="${product_id}">
                                        <input type="hidden" name="product_barcode[]" id="input-product_barcode_${product_id}" value="${barcode}">
                                        <input type="hidden" name="product_name[]" id="input-product_name_${product_id}" value="${product_name}">
                                        <input type="hidden" name="brand_name[]" id="input-brand_name_${product_id}" value="${brand_name}">
                                        <td>${barcode}</td>
                                        <td class="">${brand_name}</td>
                                        <td class="">${product_name}</td>
                                        <td class="">${selling_by_name}</td>
                                        <td id="product_stock_td_${product_id}">${stock}</td>`;

                                if(is_chronic=='Yes'){
                                    html += `<td><select class="select-3" name="" onchange="changeiscronic(this.value, ${product_id})">
                                                    <option value="sellprice">Sell price: ${formatNumber(0 + parseFloat(product_mrp))}</option>
                                                    <option value="cronicprice">Cronic price: ${formatNumber(0 + parseFloat(product_chronic_amount))}</option>
                                                </select>
                                            </td>
                                            <input type="hidden" class="product_cronic_amount input-3" name="product_cronic_amount_[]" id="product_cronic_amount_${product_id}" value="${product_chronic_amount}">`;
                                }else{
                                    html += `<td>No</td>`;
                                }

                                        
                                html += `<td id="product_unit_price_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                        <td><input type="number" name="product_qty[]" id="product_qty_${product_id}" class="input-3 product_qty" value="1"></td>
                                        <td style="display:none;"><input type="text" name="product_disc_percent[]" id="product_disc_percent_${product_id}" class="input-3 product_disc_percent" value="0"></td>
                                        <td style="display:none;"><input type="text" name="product_disc_amount[]" id="product_disc_amount_${product_id}" class="input-3 product_disc_amount" value="0"></td>
                                        <td id="product_mrp_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                        <input type="hidden" class="product_unit_price_amount input-3" name="product_unit_price_amount[]" id="product_unit_price_amount_${product_id}" value="${product_mrp}">
                                        <td id="product_total_amount_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                        <td><a href="javascript:;" onclick="remove_sell_item(${item_row});"><i class="fas fa-times-circle"></i></a></td>
                                        
                                        <input type="hidden" name="product_net_price[]" id="product_net_price_${product_id}" class="input-3" value="${product_net_price}">
                                        
                                    </tr>`;
                            $("#product_sell_record_sec").prepend(html);
                            total_cal();
                        }
                    } else {
                        toastr.error("Stock not available for this Product");
                    }

                }
            } else {
                toastr.error("Stock not available for this Product");
            }
            $("#search_barcode_product").focus();
        }
    });
}

$(document).on('click', '.select_product_item', function() {
    var branchStockProductId = $(this).attr("data-item_id");
    $.ajax({
        url: prop.ajaxurl,
        type: 'post',
        data: {
            branch_stock_product_id: branchStockProductId,
            action: 'get_stock_branch_stock_productId',
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == '1') {
                $('#modal_multiple_price_list').modal('hide');
                var item_detail = response.product_result;
                var html = '';
                //console.log(item_detail);
                var barcode = '';
                if (item_detail.product_barcode != null || item_detail.product_barcode != undefined) {
                    barcode = item_detail.product_barcode;
                }

                var product_name = '';
                if (item_detail.product_name != null || item_detail.product_name != undefined) {
                    product_name = item_detail.product_name;
                }
                var brand_name = '';
                if (item_detail.brand_name != null || item_detail.brand_name != undefined) {
                    brand_name = item_detail.brand_name;
                }

                var product_id = item_detail.branch_stock_product_id;
                var stock = 0;
                var c_stock = 0;
                var w_stock = 0;
                var product_mrp = 0;
                var product_price_id = 0;
                var option_html = '';
                var selling_by_name = '';
                stock = item_detail.t_qty;
                product_mrp = item_detail.selling_price;
                product_price_id = item_detail.price_id;
                selling_by_name = item_detail.selling_by_name;
                option_html += '<option value="' + product_mrp + '" data-stock_product_id="' + product_price_id + '">' + product_mrp + '</option>';

                var product_net_price = 0;
                product_net_price = item_detail.net_price;

                
                var is_chronic = item_detail.is_chronic;
                // console.log("is_chronic"+is_chronic);
                var product_chronic_amount = 0;
                product_chronic_amount = item_detail.chronic_amount;

                var item_row = 0;
                if ($('#product_sell_record_sec tr').length == 0) {
                    item_row++;
                } else {
                    var max = 0;
                    $("#product_sell_record_sec tr").each(function() {
                        var value = parseInt($(this).data('id'));
                        max = (value > max) ? value : max;
                    });
                    item_row = max + 1;
                }

                if (stock > 0) {
                    var same_item = 0;
                    $("#product_sell_record_sec tr").each(function() {
                        var row_product_id = $(this).attr('id').split('_')[2];
                        //console.log(row_product_id);
                        if (row_product_id == product_id) {
                            same_item = 1;

                            var qty = $('#product_qty_' + product_id).val();
                            var item_qty = Number(qty) + 1;

                            if (Number(item_qty) > Number(stock)) {
                                $('#product_qty_' + product_id).val(stock);
                                toastr.error("Entered Qty should not be greater than Stock");
                                return false;
                            } else {
                                $('#product_qty_' + product_id).val(item_qty);
                                var unit_price = $("#product_unit_price_amount_" + product_id).val();
                                //console.log(unit_price);
                                var discount_percent = $("#product_disc_percent_" + product_id).val();
                                var total_discount = (Number(unit_price) * Number(item_qty)) * Number(discount_percent) / 100;

                                if (Number(discount_percent) != 0 || discount_percent != '') {
                                    $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                                }
                                var total_selling_cost = Number(unit_price) * Number(item_qty);
                                var discount_amount = Number(total_selling_cost) - Number(total_discount);
                                var total_amount = Number(discount_amount)
                                $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                                $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                            }

                            total_cal();
                        }
                    });

                    if (same_item == 0) {
                        html += `<tr id="sell_product_${product_id}" data-id="${item_row}">
                                    <input type="hidden" name="product_id[]" value="${product_id}">
                                    <input type="hidden" id="product_stock_${product_id}" value="${stock}">
                                    <input type="hidden" name="product_total_amount[]" id="input-product_total_amount_${product_id}" value="${product_mrp}">
                                    <input type="hidden" name="product_price_id[]" id="input-product_price_id_${product_id}" value="${product_id}">
                                    <input type="hidden" name="product_barcode[]" id="input-product_barcode_${product_id}" value="${barcode}">
                                    <input type="hidden" name="product_name[]" id="input-product_name_${product_id}" value="${product_name}">
                                    <input type="hidden" name="brand_name[]" id="input-brand_name_${product_id}" value="${brand_name}">
                                    <td>${barcode}</td>
                                    <td class="">${brand_name}</td>
                                    <td class="">${product_name}</td>
                                    <td class="">${selling_by_name}</td>
                                    <td id="product_stock_td_${product_id}">${stock}</td>`;

                            if(is_chronic=='Yes'){
                                html += `<td><select class="select-3" name="" onchange="changeiscronic(this.value, ${product_id})">
                                                <option value="sellprice">Sell price: ${formatNumber(0 + parseFloat(product_mrp))}</option>
                                                <option value="cronicprice">Cronic price: ${formatNumber(0 + parseFloat(product_chronic_amount))}</option>
                                            </select>
                                        </td>
                                        <input type="hidden" class="product_cronic_amount input-3" name="product_cronic_amount_[]" id="product_cronic_amount_${product_id}" value="${product_chronic_amount}">`;
                            }else{
                                html += `<td>No</td>`;
                            }      
                            

                            html += `<td id="product_unit_price_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                    <td><input type="number" name="product_qty[]" id="product_qty_${product_id}" class="input-3 product_qty" value="1"></td>
                                    <td style="display:none;"><input type="text" name="product_disc_percent[]" id="product_disc_percent_${product_id}" class="input-3 product_disc_percent" value="0"></td>
                                    <td style="display:none;"><input type="text" name="product_disc_amount[]" id="product_disc_amount_${product_id}" class="input-3 product_disc_amount" value="0"></td>
                                    <td id="product_mrp_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                    <input type="hidden" class="input-3" name="" id="product_unit_price_amount_old_${product_id}" value="${product_mrp}">
                                    <input type="hidden" class="product_unit_price_amount input-3" name="product_unit_price_amount[]" id="product_unit_price_amount_${product_id}" value="${product_mrp}">
                                    <td id="product_total_amount_${product_id}">${formatNumber(0 + parseFloat(product_mrp))}</td>
                                    <td><a href="javascript:;" onclick="remove_sell_item(${item_row});"><i class="fas fa-times-circle"></i></a></td>
                                    <input type="hidden" name="product_net_price[]" id="product_net_price_${product_id}" class="input-3" value="${product_net_price}">
                                </tr>`;
                        $("#product_sell_record_sec").prepend(html);
                        total_cal();
                    }
                } else {
                    toastr.error("Stock not available for this Product");
                }
            } else {
                toastr.error("Something went wrong. Please try later.");
            }
        }
    });

});


function changeiscronic(pricetype, product_id){
    var product_unit_price_amount = 0;
    var product_cronic_amount = 0;
    var newMrp = 0;

    if(pricetype=='sellprice'){
        console.log("sellprice");
        newMrp = $("#product_unit_price_amount_old_"+product_id).val();
    }else if(pricetype=='cronicprice'){
        newMrp = $("#product_cronic_amount_"+product_id).val();
    }

    console.log("newMrp " +newMrp);
    
    $("#product_unit_price_"+product_id).html(newMrp);

    $("#product_unit_price_amount_"+product_id).val(newMrp);
    $("#product_mrp_"+product_id).html(newMrp);



    var qty = $('#product_qty_' + product_id).val();
    //var w_stock = $('#product_w_stock_' + product_id).val();
    //var c_stock = $('#product_c_stock_' + product_id).val();
    var stock = $('#product_stock_' + product_id).val();

    if (qty == 0) {
        $(this).select();
        toastr.error("Entered Qty should not be greater than Stock");
        return false;
    }
    if (Number(qty) > Number(stock)) {

        toastr.error("Entered Qty should not be greater than Stock");
        $('#product_qty_' + product_id).val(stock);
        var qty = $('#product_qty_' + product_id).val();
        var unit_price = $("#product_unit_price_amount_" + product_id).val();
        var discount_percent = $("#product_disc_percent_" + product_id).val();
        var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;
        console.log(unit_price);
        if (Number(discount_percent) != 0 || discount_percent != '') {
            $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
        }
        var total_selling_cost = Number(unit_price) * Number(qty);
        var discount_amount = Number(total_selling_cost) - Number(total_discount);
        var total_amount = Number(discount_amount)
        $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
        $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
        return false;
    } else {
        var unit_price = $("#product_unit_price_amount_" + product_id).val();
        var discount_percent = $("#product_disc_percent_" + product_id).val();
        var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;
        console.log(unit_price);
        if (Number(discount_percent) != 0 || discount_percent != '') {
            $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
        }
        var total_selling_cost = Number(unit_price) * Number(qty);
        var discount_amount = Number(total_selling_cost) - Number(total_discount);
        var total_amount = Number(discount_amount)
        $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
        $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));

    }

    total_cal();
    

}



function remove_sell_item(row) {
    $("#product_sell_record_sec").find("tr[data-id='" + row + "']").remove();
    total_cal();
    /*var total_qty = $("#product_record_sec tr").length;
    $(".total_qty").html(total_qty);
	final_calculation();*/
}

$(document).on('keyup', '.product_qty', function() {
    var product_id = $(this).attr('id').split('product_qty_')[1];
    var tbl_row = $(this).closest('tr').data('id');
    var qty = $('#product_qty_' + product_id).val();
    //var w_stock = $('#product_w_stock_' + product_id).val();
    //var c_stock = $('#product_c_stock_' + product_id).val();
    var stock = $('#product_stock_' + product_id).val();

    if (qty == 0) {
        $(this).select();
        toastr.error("Entered Qty should not be greater than Stock");
        return false;
    }

    /* if (stock_type == 'w') {
        stock = w_stock;
    } else {
        stock = c_stock;
    } */
    if (Number(qty) > Number(stock)) {

        toastr.error("Entered Qty should not be greater than Stock");
        $('#product_qty_' + product_id).val(stock);
        var qty = $('#product_qty_' + product_id).val();
        var unit_price = $("#product_unit_price_amount_" + product_id).val();
        var discount_percent = $("#product_disc_percent_" + product_id).val();
        var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;
        console.log(unit_price);
        if (Number(discount_percent) != 0 || discount_percent != '') {
            $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
        }
        var total_selling_cost = Number(unit_price) * Number(qty);
        var discount_amount = Number(total_selling_cost) - Number(total_discount);
        var total_amount = Number(discount_amount)
        $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
        $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
        return false;
    } else {
        var unit_price = $("#product_unit_price_amount_" + product_id).val();
        var discount_percent = $("#product_disc_percent_" + product_id).val();
        var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;
        console.log(unit_price);
        if (Number(discount_percent) != 0 || discount_percent != '') {
            $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
        }
        var total_selling_cost = Number(unit_price) * Number(qty);
        var discount_amount = Number(total_selling_cost) - Number(total_discount);
        var total_amount = Number(discount_amount)
        $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
        $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));

    }

    total_cal();
});


$(document).on('keyup', '.product_disc_percent', function() {
    var product_id = $(this).attr('id').split('product_disc_percent_')[1];

    var tbl_row = $(this).closest('tr').data('id');
    var qty = $('#product_qty_' + product_id).val();
    /* var w_stock = $('#product_w_stock_' + product_id).val();
    var c_stock = $('#product_c_stock_' + product_id).val(); */
    var stock = $('#product_stock_' + product_id).val();
    /* if (stock_type == 'w') {
        stock = w_stock;
    } else {
        stock = c_stock;
    } */
    if (Number(qty) > Number(stock)) {
        toastr.error("Entered Qty should not be greater than Stock");
        return false;
    } else {
        var unit_price = $("#product_unit_price_amount_" + product_id).val();
        var discount_percent = $("#product_disc_percent_" + product_id).val();

        if (Number(discount_percent) > 100) {
            toastr.error("Discount Cannot be greater than Product MRP");
            $("#product_disc_percent_" + product_id).val(0);
            discount_cal(product_id);
        } else {
            var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;

            if (Number(discount_percent) != 0 || discount_percent != '') {
                $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
            }
            var total_selling_cost = Number(unit_price) * Number(qty);
            var discount_amount = Number(total_selling_cost) - Number(total_discount);
            var total_amount = Number(discount_amount)
            $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
            $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
        }
    }

    total_cal();
});

$(document).on('keyup', '.product_disc_amount', function() {
    var product_id = $(this).attr('id').split('product_disc_amount_')[1];
    var tbl_row = $(this).closest('tr').data('id');
    var qty = $('#product_qty_' + product_id).val();
    /* var w_stock = $('#product_w_stock_' + product_id).val();
    var c_stock = $('#product_c_stock_' + product_id).val(); */
    var product_mrp = $('#product_unit_price_' + product_id).val();
    var disc_amount = $('#product_disc_amount_' + product_id).val();
    var unit_price = $("#product_unit_price_amount_" + product_id).val();

    //console.log(product_mrp);

    var stock = $('#product_stock_' + product_id).val();
    /* if (stock_type == 'w') {
        stock = w_stock;
    } else {
        stock = c_stock;
    } */
    if (Number(qty) > Number(stock)) {
        toastr.error("Entered Qty should not be greater than Stock");
        return false;
    } else {
        var discount_percent = (Number(disc_amount) * 100) / (Number(unit_price) * Number(qty));

        if (Number(discount_percent) > 100) {
            toastr.error("Discount Cannot be greater than Product MRP");
            $("#product_disc_percent_" + product_id).val(0);
            discount_cal(product_id);
        } else {
            $("#product_disc_percent_" + product_id).val(Number(discount_percent).toFixed(2));
            var total_discount = disc_amount;
            var total_selling_cost = Number(unit_price) * Number(qty);
            var discount_amount = Number(total_selling_cost) - Number(total_discount);
            var total_amount = Number(discount_amount)
            $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
            $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
        }
    }

    total_cal();
});

$(document).on('change', '.product_unit_price', function() {
    var product_id = $(this).attr('id').split('product_unit_price_')[1];
    var tbl_row = $(this).closest('tr').data('id');
    var qty = $('#product_qty_' + product_id).val();
    var stock_product_id = $(this).find(':selected').attr("data-stock_product_id");
    //console.log(stock_product_id);
    /* var w_stock = $('#product_w_stock_' + product_id).val();
    var c_stock = $('#product_c_stock_' + product_id).val(); */
    //var available_stock = getStockBybranchStockProductId(stock_product_id, callback);

    getStockBybranchStockProductId(stock_product_id, function(available_stock) {
        //processing the data
        //console.log(d);
        //console.log(available_stock);
        $('#product_stock_td_' + product_id).html(available_stock);
        $('#product_stock_' + product_id).val(available_stock);
        var stock = $('#product_stock_' + product_id).val();
        var product_unit_price = $('#product_unit_price_' + product_id).val();
        //console.log(product_unit_price);
        $("#product_unit_price_amount_" + product_id).val(Number(product_unit_price));
        $("#product_mrp_" + product_id).html(Number(product_unit_price));
        //console.log(qty);

        if (Number(qty) > Number(stock)) {
            toastr.error("Entered Qty should not be greater than Stock");
            $('#product_qty_' + product_id).val(stock);
            setTimeout(function() {
                var qty = $('#product_qty_' + product_id).val();
                var unit_price = $("#product_unit_price_amount_" + product_id).val();
                var discount_percent = $("#product_disc_percent_" + product_id).val();
                var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;

                if (Number(discount_percent) != 0 || discount_percent != '') {
                    $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                }
                var total_selling_cost = Number(unit_price) * Number(qty);
                var discount_amount = Number(total_selling_cost) - Number(total_discount);
                var total_amount = Number(discount_amount)
                $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                total_cal();
            }, 500);
            return false;
        } else {
            setTimeout(function() {
                var unit_price = $("#product_unit_price_amount_" + product_id).val();
                var discount_percent = $("#product_disc_percent_" + product_id).val();
                var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;

                if (Number(discount_percent) != 0 || discount_percent != '') {
                    $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
                }
                var total_selling_cost = Number(unit_price) * Number(qty);
                var discount_amount = Number(total_selling_cost) - Number(total_discount);
                var total_amount = Number(discount_amount)
                $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
                $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
                total_cal();
            }, 500);
        }
    });



    //console.log(stock);
    /* if (stock_type == 'w') {
        stock = w_stock;
    } else {
        stock = c_stock;
    } */


});

function discount_cal(product_id) {
    var qty = $('#product_qty_' + product_id).val();
    /* var w_stock = $('#product_w_stock_' + product_id).val();
    var c_stock = $('#product_c_stock_' + product_id).val(); */
    var stock = $('#product_stock_' + product_id).val();

    /* if (stock_type == 'w') {
        stock = w_stock;
    } else {
        stock = c_stock;
    } */

    var product_unit_price = $('#product_unit_price_' + product_id).val();
    $("#product_unit_price_amount_" + product_id).val(Number(product_unit_price));

    if (Number(qty) > Number(stock)) {
        toastr.error("Entered Qty should not be greater than Stock");
        return false;
    } else {
        setTimeout(function() {
            var unit_price = $("#product_unit_price_amount_" + product_id).val();
            var discount_percent = $("#product_disc_percent_" + product_id).val();
            var total_discount = (Number(unit_price) * Number(qty)) * Number(discount_percent) / 100;

            if (Number(discount_percent) != 0 || discount_percent != '') {
                $("#product_disc_amount_" + product_id).val(total_discount.toFixed(2));
            }
            var total_selling_cost = Number(unit_price) * Number(qty);
            var discount_amount = Number(total_selling_cost) - Number(total_discount);
            var total_amount = Number(discount_amount)
            $("#product_total_amount_" + product_id).html(Number(total_amount).toFixed(2));
            $("#input-product_total_amount_" + product_id).val(Number(total_amount).toFixed(2));
            total_cal();
        }, 500);
    }
}

function total_cal() {
    $("#total_quantity").html('0');
    $("#total_mrp").html('$0');
    $("#total_discount_amount").html('$0');
    $("#tax_amount").html('$0');
    $('#round_off').val(0);
    $("#sub_total_mrp").html('$0');
    $("#total_payble_amount").html('$0');


    $('#sub_total_mrp-input').val(0);
    $('#total_quantity-input').val(0);
    $('#total_mrp-input').val(0);
    $('#total_discount_amount-input').val(0);
    $('#tax_amount-input').val(0);
    $('#total_payble_amount-input').val(0);

    // $('#charge_amt-input').val(0);
    $('#charge_amt').val(0);
    $("#charge_total_payable").html('0');

    var total_quantity = 0;
    var total_mrp = 0;
    var total_discount_amount = 0;
    var tax_amount = 0;
    var round_off = 0;
    var sub_total_mrp = 0;
    var total_payble_amount = 0;

    var product_net_price = 0
    var subNetPrice = 0;
    var totalNetPrice = 0;

    setTimeout(function() {

        $("#product_sell_record_sec tr").each(function(index, e) {
            var product_id = $(this).attr('id').split('sell_product_')[1];
            var tbl_row = $(this).data('id');

            var product_qty = $("#product_qty_" + product_id).val();
            if (product_qty == '') {
                product_qty = 0;
            }
            total_quantity += (Number(product_qty));

            var unit_price = $("#product_unit_price_amount_" + product_id).val();
            var total_selling_cost = Number(unit_price) * Number(product_qty);
            total_mrp += (Number(total_selling_cost));

            var disc_amount = $("#product_disc_amount_" + product_id).val();
            total_discount_amount += (Number(disc_amount));

            var total_amount = $("#product_total_amount_" + product_id).html();
            total_amount = removeComma(total_amount);
            sub_total_mrp += (Number(total_amount));

            product_net_price = $("#product_net_price_" + product_id).val();

            subNetPrice = (Number(product_net_price)*product_qty);

            totalNetPrice += subNetPrice;


        });

        // console.log("totalNetPrice -- "+totalNetPrice);
        $("#total_net_price").html('$'+ formatNumber(totalNetPrice));

        $("#total_quantity").html(total_quantity);
        $("#total_mrp").html('$'+ formatNumber(total_mrp));
        // $("#total_discount_amount").html('$'+ total_discount_amount);
        $("#sub_total_mrp").html('$'+ formatNumber(sub_total_mrp));
        $("#total_payble_amount").html('$'+ formatNumber(sub_total_mrp));


        $('#sub_total_mrp-input').val(sub_total_mrp);
        $('#total_quantity-input').val(total_quantity);
        $('#total_mrp-input').val(total_mrp);
        // $('#total_discount_amount-input').val(total_discount_amount);
        $('#tax_amount-input').val(0);
        $('#total_payble_amount-input').val(sub_total_mrp);
        $('#gross_total_amount-input').val(sub_total_mrp);

        $("#gross_total_input").val(sub_total_mrp);
        $("#actual_amount").val(sub_total_mrp);


        var total_profit = ((total_mrp-totalNetPrice)*100);
        $("#total_profit").html('$'+ formatNumber(total_profit));

        var special_discount_percent = $('#selling_special_discount_percent-input').val();
        console.log("special_discount_percent"+special_discount_percent);
        if (special_discount_percent > 0) {
            var gross_total_amount = $('#gross_total_amount-input').val();
            var total_discount = Number(gross_total_amount) * Number(special_discount_percent) / 100;
            var discount_amount = Number(gross_total_amount) - Number(total_discount);

            $('#gross_total_amount-input').val(discount_amount);

            var charge_amt = $("#charge_amt-input").val();
            discount_amount = (discount_amount+Number(charge_amt));

            $("#total_discount_amount").html('$'+ formatNumber(total_discount));
            $("#total_discount_amount-input").val(total_discount);

            $("#sub_total_mrp").html('$'+ formatNumber(discount_amount));
            $("#sub_total_mrp-input").val(discount_amount);

            $("#total_payble_amount").html('$'+ formatNumber(discount_amount));
            $('#total_payble_amount-input').val(discount_amount);

            $("#special_discount_amt").val(total_discount);
            $("#selling_special_discount_amt-input").val(total_discount);

            

            

            $("#discount_total_payable").html('$'+ Number(discount_amount).toFixed(2));
        }

    }, 200);
}

function checkforroundoff(e, c) {
    var notallow = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '~', '`', '/', '*', '+', '>', '<', '?'];
    var allowedKeyCodesArr = [9, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 8, 37, 39, 109, 189, 46, 110, 190];
    if ($.inArray(e.key, notallow) != -1) {
        e.preventDefault();
    } else if ($.inArray(e.keyCode, allowedKeyCodesArr) === -1) {
        e.preventDefault();
    } else if ($.trim($(c).val()).indexOf('.') > -1 && $.inArray(e.keyCode, [110, 190]) !== -1) {
        e.preventDefault();
    } else {
        return true;
    }
}

$(document).on('keyup', '#round_off', function() {
    if ($("#round_off").val() != '') {
        var roundoff = 0;
        roundoff = $("#round_off").val();
        if ($("#round_off").val() == '-') {
            roundoff = 0;
        }


        //$('#gross_total_amount-input').val(total_payble_amount);

        var special_discount_percent = $('#selling_special_discount_percent-input').val();
        if (special_discount_percent > 0) {
            var gross_total_amount = $('#gross_total_amount-input').val();
            var total_discount = Number(gross_total_amount) * Number(special_discount_percent) / 100;
            var discount_amount = Number(gross_total_amount) - Number(total_discount);

            var total_payble_amount = (parseFloat(discount_amount) + parseFloat(roundoff)).toFixed(2);

            $("#total_payble_amount").html('$'+ formatNumber(total_payble_amount));
            $('#total_payble_amount-input').val(total_payble_amount);

            $("#special_discount_amt").val(total_discount);
            $("#selling_special_discount_amt-input").val(total_discount);

            $("#discount_total_payable").html(Number(discount_amount).toFixed(2) + 'ع.د');
        } else {
            var total_payble_amount = (parseFloat($("#sub_total_mrp-input").val()) + parseFloat(roundoff)).toFixed(2);
            $("#total_payble_amount").text(formatNumber(total_payble_amount));
            $('#total_payble_amount-input').val(total_payble_amount);
        }

    } else {
        $("#round_off").val(0);
    }
});

function getStockBybranchStockProductId(branchStockProductId, callback) {

    if (branchStockProductId != '') {
        var stock = 0;
        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                branch_stock_product_id: branchStockProductId,
                action: 'get_stock_branch_stock_productId',
                _token: prop.csrf_token
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == '1') {
                    //console.log(response.stock);
                    callback(response.stock);
                } else {
                    toastr.error("Something went wrong. Please try later.");
                }
            }
        });
        //return stock;
    }
}

$(document).on("keyup", "#search_customer", function() {
    var customer_search_val = $(this).val();
    if (customer_search_val != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: "post",
            dataType: "json",
            data: {
                customer_search_val: customer_search_val,
                action: "get_customer_by_search",
                _token: prop.csrf_token,
            },
            beforeSend: function() {},
            success: function(response) {
                if (response.status == 1) {
                    var len = response.result.length;

                    $("#customer_search_result").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response.result[i]["id"];
                        var name =
                            response.result[i]["customer_name"] + '/' + response.result[i]["customer_mobile"];
                        $("#customer_search_result").append(
                            "<li value='" + id + "' data-name='" + response.result[i]["customer_name"] + "' data-mobile='" + response.result[i]["customer_mobile"] + "'>" + name + "</li>"
                        );
                    }

                    // binding click event to li
                    $("#customer_search_result li").bind("click", function() {
                        //$(".loader_section").show();
                        var fulltext = $(this).text();
                        var customer_name = $(this).data("name");
                        var customer_mobile = $(this).data("mobile");
                        var customer_id = $(this).val();
                        $('#cd_customer_name span').html(customer_name);
                        $('#cd_customer_number span').html(customer_mobile);
                        $('#selected_customer_id').val(customer_id);
                        $("#customer_search_result").empty();
                        $("#search_customer").val(fulltext);
                    });
                }
            },
        });
    }
});

$(document).ready(function() {
    $("input[name='customertype']").on('click', function() {
        var selectedValue = $("input[name='customertype']:checked").val();
        if (selectedValue=='regular') {
            $(".customeSearch").show();
            $(".customerDetailsMid").show();
        } else {
            $(".customeSearch").hide();
            $(".customerDetailsMid").hide();
        }
    });
});

function formatNumber(net_price) {
    //const options = { style: 'decimal', minimumFractionDigits: 2 };
    const formattedNumber = net_price.toLocaleString('en-US');
    // console.log(formattedNumber);
    //const roundNumber = Math.round(formattedNumber);
    return formattedNumber;
}

$(document).ready(function() {
    $(".topSellProductItem").bind("click", function() {
        console.log(this);
        setProductRow(this);
    });
});

$(document).ready(function() {
    $("#top_search_product").keyup(function() {
        var search = $(this).val();
        if (search != "") {
            $.ajax({
                url: prop.ajaxurl,
                type: 'post',
                data: {
                    search: search,
                    action: 'get_top_sell_product_search',
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.result.length;

                    $("#product_search_result_top").empty();
                    for (var i = 0; i < len; i++) {
                        //response.result[i]['product_barcode'] + '-' + 
                        var id = response.result[i]['id'];
                        var name = response.result[i]['product_name'] + ' / ' + response.result[i]['product_barcode'];
                        $("#product_search_result_top").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#product_search_result_top li").bind("click", function() {
                        setProductRow(this);
                    });
                }
            });
        } else {
            $("#product_search_result_top").empty();
        }
    });

   
});

function removeComma(price){
    var stringWithComma = price;
    var stringWithoutComma = stringWithComma.replace(/,/g, '');
    return stringWithoutComma;
}