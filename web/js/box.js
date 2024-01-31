var Box = function () {
    this.changeStatus = function (boxId, status) {
        if (status !== '') {
            $.get('/box/change-status', {
                'id': boxId,
                'status': status
            }, function () {
                toastr.success('Status was changed!');
            });
        }
    };

    this.setBatchStatus = function (status) {

        var ids = [];
        $('input[name="selection[]"]:checked').map(function () {
            ids.push(this.value);
        });

        console.log(ids);


        $.get('/box/change-status-batch', {
            'ids': ids.join(','),
            'status': status
        }, function () {
            toastr.success('Status was changed!');
        });
    };
};

var box = new Box();