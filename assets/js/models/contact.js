var Contact = Backbone.Model.extend({
    defaults: {
        firstName: "",
        lastName: "",
        emailAddress: "",
        telephoneNumber: "",
        relationalTag: ""
    },

    idAttribute: 'contactId',

    urlRoot: "http://localhost//index.php/ApiController/contact",

    validate: function (attrs) {
        if (!attrs.firstName && !attrs.lastName && !attrs.emailAddress && !attrs.telephoneNumber) {
            return "Only Relational Tag can be left empty!";
        }
    }
});