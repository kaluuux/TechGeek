$(document).ready(function() {
    var User = Backbone.Model.extend({
        defaults: {
            username: 'Guest'
        }
    });

    var UserView = Backbone.View.extend({
        el: '#userForm',

        events: {
            'submit form': 'submitForm'
        },

        initialize: function() {
            this.model = new User();
            this.render();
        },

        render: function() {
            var template = _.template($('#user-template').html());
            this.$el.html(template(this.model.attributes));
        },

        submitForm: function(event) {
            event.preventDefault();
            var username = this.$('input[name="username"]').val();
            var email = this.$('input[name="email"]').val();
            var password = this.$('input[name="password"]').val();
            if (username.length < 5) {
                alert('Username must be at least 5 characters.');
                return;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters.');
                return;
            }
            this.$('form')[0].submit();
        }
    });

    var userView = new UserView();
});
