const SwalConfirmAjax = (type, url, method, data, afterSuccess = null) => {
    const attribute = attrByType(type);

    swalInit
        .fire({
            icon: "question",
            title: attribute.title,
            text: attribute.text,
            showCancelButton: true,
            confirmButtonText: "Confirm",
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: (num) => {
                return $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    dataType: "json",
                })
                    .done(function (data) {
                        return data;
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 422) {
                            var xhr = JSON.stringify(
                                JSON.parse(jqXHR.responseText).errors
                            );
                        } else {
                            var xhr = JSON.stringify(
                                JSON.parse(jqXHR.responseText)
                            );
                        }
                        swalInit.fire({
                            title: "Request Error",
                            text: xhr.substring(0, 160),
                            icon: "error",
                        });
                    });
            },
            allowEscapeKey: false,
            allowOutsideClick: false,
        })
        .then((result) => {
            if (result.value != null) {
                if (result.value.status) {
                    editing = false;
                    swalInit.fire({
                        title: "Success",
                        text: result.value.msg,
                        icon: "success",
                        didClose: afterSuccess,
                    });
                } else {
                    swalInit.fire({
                        title: "Error",
                        text: result.value.msg.substring(0, 160),
                        icon: "error",
                    });
                }
            }
        });
};

const SwalConfirm = (type, form) => {
    const attribute = attrByType(type);

    swalInit
        .fire({
            icon: "question",
            title: attribute.title,
            text: attribute.text,
            showCancelButton: true,
            confirmButtonText: "Confirm",
            reverseButtons: true,
            showLoaderOnConfirm: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
};

const attrByType = (type) => {
    let objType = {};

    switch (type) {
        case "INSERT":
            objType = {
                title: "Submit item",
                text: "Are you sure to save item ?",
            };
            break;
        case "DELETE":
            objType = {
                title: "Delete item",
                text: "Are you sure to delete item ?",
            };
            break;
        case "POSTING":
            objType = {
                title: "Posting item",
                text: "Are you sure to posting item ?",
            };
            break;
        case "UNPOSTING":
            objType = {
                title: "UnPosting item",
                text: "Are you sure to unposting item ?",
            };
            break;
    }

    return objType;
};

const SelectRemoteData = (elClass, url, elParent = "") => {
    $(elClass).select2({
        dropdownParent: elParent != "" ? $(elParent) : null,
        ajax: {
            url: url,
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.results,
                    pagination: data.pagination,
                };
            },
            cache: true,
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        dropdownCssClass: "tfont",
        minimumInputLength: 1,
    });
};

const SelectData = (elClass, url) => {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "JSON",
        data: {},
    })
        .done(function (response) {
            $(elClass).find("option").remove();
            if (response.status) {
                $(elClass).append($("<option></option>").text("").val(""));
                $.each(response.data, function (index, val) {
                    $(elClass).append(
                        $("<option></option>").text(val).val(index)
                    );
                });
            }
            console.log("success");
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
};

const CustomAjax = (
    url,
    method,
    data,
    triggerSuccess = null,
    triggerAlways = null
) => {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "json",
    })
        .done(function (response) {
            return triggerSuccess != null ? triggerSuccess(response) : response;
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 422) {
                var xhr = JSON.stringify(JSON.parse(jqXHR.responseText).errors);
            } else {
                var xhr = JSON.stringify(JSON.parse(jqXHR.responseText));
            }
            swalInit.fire({
                title: "Request Error",
                text: xhr.substring(0, 160),
                icon: "error",
            });
        })
        .always(function () {
            return triggerAlways != null ? triggerAlways() : true;
        });
};
