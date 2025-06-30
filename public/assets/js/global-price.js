function precisionPrice(price) {
    return Math.round(price * 100) / 100;
}

$('#global-hour-price, #global-price-discount').change(function() {
    if($('#global-hour-price').val() !== "") {
        let price = parseFloat($('#global-hour-price').val().replace(".", "").replace(",", "."));
        let priceDiscount = parseFloat($('#global-price-discount').val().replace(".", "").replace(",", "."));
        let calc = precisionPrice(price - (price / 100) * priceDiscount);
        $('#global-price-result').val(numberFormat(calc, 2, ',', '.'));
        $('#global-fifteen-price').val(numberFormat((calc / 4), 2, ',', '.'));
    }
});

$('#global-hour-price').keyup(function() {
    if($(this).val() === "") {
        $('#global-fifteen-price').prop('disabled', false);
        $('#global-price-result, #global-price-discount').prop('readonly', true);
    }
    else {
        $('#global-fifteen-price').prop('disabled', true);
        $('#global-price-result, #global-price-discount').prop('readonly', false);
    }
});

$('#global-fifteen-price').change(function() {
    let rate = parseFloat($(this).val().replace(".", "").replace(",", "."));
    $('#global-price-result').val(numberFormat(4 * rate, 2, ",", "."));
    $('#global-hour-price').val(numberFormat(4 * rate, 2, ",", "."));
    $('#global-hour-price').trigger('keyup');
    $('#global-price-discount').val('0,0000');
    $('#global-hour-price').trigger('change');
});


$('#global-price-result').change(function() {
    let price = parseFloat($('#global-hour-price').val().replace(".", "").replace(",", "."));
    if(isNaN(price)) {
        $('#global-hour-price').val($(this).val());
        parseFloat($(this).val().replace(".", "").replace(",", "."));
    }
    let result = parseFloat($(this).val().replace(".", "").replace(",", "."));
    let calc = ((price-result) / price) * 100;
    $('#global-price-discount').val(numberFormat(calc, 4, ",", "."));
    $('#global-fifteen-price').val(numberFormat((result / 4), 2, ',', '.'));
});