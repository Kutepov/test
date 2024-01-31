var Product = function () {
    this.matchWithReceived = function (value) {
        console.log(value);
        $('#product-shipped_qty').val(value);
    };
};

var product = new Product();