
// var stripeKey = 'pk_test_51KkyAqRl8XUoxwKBQRqmxAHldpFtoQ51VxvxPBl9KkHWMkhBVbriUuGOFh6xocvXwXXuO2w0XNfFlO7gpOiGHiKp00BPzsObtg';
var stripe = Stripe('pk_test_51KkyAqRl8XUoxwKBQRqmxAHldpFtoQ51VxvxPBl9KkHWMkhBVbriUuGOFh6xocvXwXXuO2w0XNfFlO7gpOiGHiKp00BPzsObtg', {
  apiVersion: "2020-08-27",
});

var paymentRequest = stripe.paymentRequest({
  country: 'US',
  currency: 'usd',
  total: {
    label: 'Demo total',
    amount: 1099,
  },
  requestPayerName: true,
  requestPayerEmail: true,
});

var elements = stripe.elements();
var prButton = elements.create('paymentRequestButton', {
  paymentRequest: paymentRequest,
});

// Check the availability of the Payment Request API first.
paymentRequest.canMakePayment().then(function(result) {
  if (result) {
    prButton.mount('#payment-request-button');
  } else {
    document.getElementById('payment-request-button').style.display = 'none';
  }
});

// var createOrderUrl = window.location.protocol + '//' + window.location.host +  '/stripe-php/lib/StripeService.php';
// var returnUrl = window.location.protocol + '//' + window.location.host + window.location.search ;
// var stripe = Stripe(stripeKey);
// var elements = stripe.elements();
// var style = {
// 	base: {
// 		color: "#32325d",
// 		fontFamily: 'Arial, sans-serif',
// 		fontSmoothing: "antialiased",
// 		fontSize: "16px",
// 		"::placeholder": {
// 			color: "#32325d"
// 		}
// 	},
// 	invalid: {
// 		fontFamily: 'Arial, sans-serif',
// 		color: "#fa755a",
// 		iconColor: "#fa755a"
// 	}
// };
// var card = elements.create("card", {
// 	hidePostalCode: true,
// 	style: style
// });
// // Stripe injects an iframe into the DOM
// card.mount("#card-element");
// card
// 	.on(
// 		"change",
// 		function(event) {
// 			// Disable the Pay button if there are no card details in
// 			// the Element
// 			document.querySelector("button").disabled = event.empty;
// 			document.querySelector("#card-error").textContent = event.error ? event.error.message
// 				: "";
// 		});

// function confirmOrder(event) {
// 	var valid = formValidate();
// 	if (valid) {
// 		var purchase = {
// 			email: document.getElementById("email").value,
// 			notes: document.getElementById("notes").value,
// 			unitPrice: document.getElementById("price").value,
// 			currency: document.getElementById("currency").value,
// 			name: document.getElementById("customer_name").value,
// 			address: document.getElementById("address").value,
// 			country: document.getElementById("country").value,
// 			postalCode: document.getElementById("postal_code").value
// 		};

// 		// Disable the button until we have Stripe set up on the page
// 		// document.querySelector("btnSubmit").disabled = true;
// 		fetch(createOrderUrl, {
// 			method: "POST",
// 			headers: {
// 				"Content-Type": "application/json",

// 			},
// 			body: JSON.stringify(purchase)
// 		}).then(function(result) {
// 			return result.json();
// 		}).then(function(data) {
// 			// Complete payment when the submit button is clicked
// 			payWithCard(stripe, card, data.clientSecret, data.orderHash);
// 		});
// 		// Calls stripe.confirmCardPayment
// 		// If the card requires authentication Stripe shows a pop-up modal
// 		// to
// 		// prompt the user to enter authentication details without leaving
// 		// your
// 		// page.
// 		var payWithCard = function(stripe, card, clientSecret, orderHash) {
// 			loading(true);
// 			stripe.confirmCardPayment(clientSecret, {
// 				payment_method: {
// 					card: card
// 				}
// 			}).then(function(result) {
// 				if (result.error) {
// 					// Show error to your customer
// 					showError(result.error.message);
// 				} else {
// 					// The payment succeeded!
// 					orderComplete(result.paymentIntent.id, orderHash);
// 				}
// 			});
// 		};
// 		/* ------- UI helpers ------- */
// 		// Shows a success message when the payment is complete
// 		var orderComplete = function(paymentIntentId, orderHash) {
// 			loading(false);
// 			window.location.href = returnUrl + "?orderId=" + orderHash;
// 		};
// 		// Show the customer the error from Stripe if their card fails to
// 		// charge
// 		var showError = function(errorMsgText) {
// 			loading(false);
// 			var errorMsg = document.querySelector("#card-error");
// 			errorMsg.textContent = errorMsgText;
// 			setTimeout(function() {
// 				errorMsg.textContent = "";
// 			}, 10000);
// 		};
// 		// Show a spinner on payment submission
// 		var loading = function(isLoading) {
// 			if (isLoading) {
// 				// Disable the button and show a spinner
// 				document.querySelector("button").disabled = true;
// 				document.querySelector("#spinner").classList.remove("hidden");
// 				document.querySelector("#button-text").classList.add("hidden");
// 			} else {
// 				document.querySelector("button").disabled = false;
// 				document.querySelector("#spinner").classList.add("hidden");
// 				document.querySelector("#button-text").classList
// 					.remove("hidden");
// 			}

// 		};
// 	}
// }
