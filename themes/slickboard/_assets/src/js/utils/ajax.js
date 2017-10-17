/*
    Author: Nozemi

    This is a class to help write AJAX requests more efficiently. Rather than piling up
    a bunch of duplicate code, we can re-use most of it, and pass the necessary variables/data to the methods.
 */

const $ = require('jquery');

module.exports = function(loader_id, data_url, serialized_data, data_type) {
    this.type           = "POST";

    this.loaderId       = loader_id;
    this.requestUrl     = data_url;
    this.requestData    = serialized_data;
    this.dataType       = data_type;

    this.loadIndex      = 0;

    this.loadData = function(return_element, maximum_entries, html_format) {
        this.loadIndex++;
    };

    this.sendData = function(message_element, callback) {
        this.loadIndex++;

        /*if(this.loaderId !== null) {
            var loader = $('<p>Loading...</p>').attr('id', 'loader_' + this.loadIndex);
            $(message_element).html(loader);
        }*/

        $.ajax({
            type: this.type,
            url: this.requestUrl,
            data: this.requestData,
            dataType: this.dataType,
            error: function(xhr, ajaxOptions, thrownError) {
                console.log("Sorry, an error occured: " + thrownError);
                console.log(xhr);
                console.log(ajaxOptions);
            },
            success: function(response) {
                /*if(this.loaderId !== null) {
                    loader.remove();
                    $(message_element).html('');
                }*/

                callback(response, message_element);
            }
        });
    }
};