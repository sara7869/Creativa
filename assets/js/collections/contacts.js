var ContactsCollection = Backbone.Collection.extend({
    model: Contact,
    url: "http://localhost/repo/index.php/ApiController/contact"
});