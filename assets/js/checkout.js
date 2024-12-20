document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var cardNumber = document.getElementById('card_number').value;
    var expiryDate = document.getElementById('expiry_date').value;
    var cvv = document.getElementById('cvv').value;

    console.log("Payment Method Token: ", document.getElementById('payment_method_token').value);

    WooPayments.createToken({
        card_number: cardNumber,
        expiry_date: expiryDate,
        cvv: cvv
    }).then(function(token) {
        document.getElementById('payment_method_token').value = token.id;
        document.getElementById('payment-form').submit();
    }).catch(function(error) {
        alert("Error generating token: " + error.message);
    });
});
