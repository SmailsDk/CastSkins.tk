new Vue({
    el: 'body',
    data: {
        ammout: '',
        email: '',
        cost: '0',
        payment_method: 'g2a pay'
    },
    computed: {
        // a computed getter
        cost: function () {
            // `this` points to the vm instance
            return 'EUR ' + ((this.ammout) * 0.0013).toFixed(2);
        }
    }

});

if (window.location.hash.indexOf("thanks") >= 0) {
    var inst = $('[data-remodal-id=thanks]').remodal();
    inst.open();
}
if (window.location.hash.indexOf("ups") >= 0) {
    var inst = $('[data-remodal-id=ups]').remodal();
    inst.open();
}
$('#checkout').on('click', function () {
    var amount = $('#ammout').val();
    var email = $('#email').val();
    $.ajax({
        url: '/requestToken',
        method: 'post',
        dataType: 'json',
        data: {
            email: email,
            amount: amount
        },
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        },
        success: function (data) {
            if (data.success == 0) {
                alertify.error(data.response);
            } else {
                $.ajax({
                    url: 'https://checkout.pay.g2a.com/index/createQuote',
                    method: 'post',
                    dataType: 'json',
                    data: $.parseJSON(data.response),
                    success: function (msg) {
                        console.log(msg);
                        if(msg.status == 'ok') {
                            alertify.success('You will be redirected to payment now.');
                        }
                        if(msg.token) {
                            window.location = 'https://checkout.pay.g2a.com/index/gateway?token='+msg.token+'';
                        }
                    }
                });
            }
        }
    });
});