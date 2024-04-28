// $(document).ready(function() {
//     var User = Backbone.Model.extend({
//         defaults: {
//             username: 'Guest'  // Default username is set to 'Guest'
//         }
//     });

//     var UserView = Backbone.View.extend({
//         el: '#userForm',  // Targets the div with id='userForm'

//         initialize: function() {
//             this.model = new User();  // Initializes the model when the view is created
//             this.render();  // Calls the render function upon initialization
//         },

//         render: function() {
//             var template = _.template($('#user-template').html());  // Compiles the template
//             this.$el.html(template(this.model.attributes));  // Renders the compiled HTML to the element
//         }
//     });

//     var userView = new UserView();  // Creates an instance of UserView
// });
$(document).ready(function() {
    var User = Backbone.Model.extend({
        defaults: {
            username: 'Guest'  // Default username is set to 'Guest'
        }
    });

    var UserView = Backbone.View.extend({
        el: '#userForm',  // Targets the div with id='userForm'

        events: {
            'submit form': 'submitForm'
        },

        initialize: function() {
            this.model = new User();  // Initializes the model when the view is created
            this.render();  // Calls the render function upon initialization
        },

        render: function() {
            var template = _.template($('#user-template').html());  // Compiles the template
            this.$el.html(template(this.model.attributes));  // Renders the compiled HTML to the element
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
            // If validation passes, submit the form
            this.$('form')[0].submit();
        }
    });

    var userView = new UserView();  // Creates an instance of UserView
});
