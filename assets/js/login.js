$(document).ready(function () {
	var User = Backbone.Model.extend({
		defaults: {
			email: "",
			password: "",
		},
	});

	var LoginView = Backbone.View.extend({
		el: "#loginFormContainer",

		events: {
			"submit #actualLoginForm": "submitForm",
		},
		

		initialize: function () {
			this.model = new User();
			this.render();
		},

		render: function () {
			var template = _.template($("#login-template").html());
			this.$el.html(template(this.model.attributes));
		},

		submitForm: function (event) {
			event.preventDefault();
			// var loginUrl = this.$el.data('login-url');
			var loginUrl = $('#loginFormContainer').data('url');

			// Basic validation checks
			var email = this.$("#email").val().trim();
			var password = this.$("#password").val().trim();

			if (!email) {
				alert("Please enter your email.");
				return; // Stop processing the form submission
			}
			if (!password) {
				alert("Please enter your password.");
				return; // Stop processing the form submission
			}

			// If all validations pass, set the model and log data (or send to server)
			var loginData = { email: email, password: password };
			this.model.set(loginData);
			console.log("Login submitted with:", this.model.toJSON());
			var csrfToken = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: loginUrl,
				type: "POST",
				data: {
					email: email,
					password: password,
					csrf_token_name: csrfToken  // Assuming CSRF protection is enabled and token name is 'csrf_token_name'
				},
				dataType: "json",
				success: function(response) {
					if (response.success) {
						window.location.href = response.redirect;
					} else {
						alert('Login failed: ' + (response.message || 'No error message provided'));
					}
				},
				error: function(xhr, textStatus, error) {
					console.log("AJAX Error:", textStatus + ': ' + error);
					console.log("Response was:", xhr.responseText);
					alert("Login failed. Please try again later.");
				}
			});
		},
	});

	var loginView = new LoginView();
});
