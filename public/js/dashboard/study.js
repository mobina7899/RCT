_token = $('meta[name="csrf-token"]').attr("content");
const regSpecial = /[`@#$%^&*+=\[\]{}'"\\|<>\/~]/;

$(document).ready(function () {
    $(".search-tags").slideUp();

    $("#study-form input[name=tags]").on("blur", function (event) {
        $(".search-tags").slideUp();
    });

    $(document).on("click", ".search-tags li", function (event) {
        $("#study-form input[name=tags]").val($(this).text()).focus();
    });

    $(document).on("keydown", ".plan-tags", function (event) {
        if (event.key == "Enter") {
            event.preventDefault();
            addNewTag(event);
        }
    });

    $(document).on("click", `.plan-tags+i`, addNewTag);
    $(document).on("click", ".tag-item i", removeTag);

    function addNewTag(event) {

        if (
            $("#study-form input[name=tags]").val() == "" ||
            regSpecial.test($("#study-form input[name=tags]").val()) ||
            $("#study-form input[name=tags]").val().length > 255
        ) {
            console.log($("#study-form input[name=tags]").val());
            event.preventDefault();
            $("#study-form input[name=tags]").siblings(".errors").css("height", "auto");
            $("#study-form input[name=tags]")
                .siblings(".errors")
                .text("* برچسب وارد شده صحیح نمی باشد!");

        } else {

            event.preventDefault();
            $.ajax({
                url: "/dashboard/study/check-tag",
                method: "POST",
                data: {title: $(".plan-tags").val(), _token: _token},
                dataType: "json",

                success: function (response) {
                    $(".plan-tags")
                        .siblings(".tags-container")
                        .append(
                            `<a class="tag-item tag-style">
  <i class="fa-solid fa-xmark mt-1 cursor-pointer align-middle"></i>
  ${$("#study-form input[name=tags]").val()}
</a>`
                        );

                    $(".plan-tags").val("");
                    $(".plan-tags").siblings(".errors").text("");
                },
                error: function () {
                    $(".plan-tags").siblings(".errors").css("height", "auto");
                    $(".plan-tags")
                        .siblings(".errors")
                        .text("* برچسب وارد شده صحیح نمی باشد!")
                }
            });
        }
    }

    function removeTag(event) {
        $(event.target).parent(".tag-item").remove();
    }

    $("#study-form input[name=tags]").on("keyup", function (event) {
        if (event.key != "Enter") {
            var search = $(this).val();
            $.ajax({
                url: "/dashboard/study/search-tag",
                method: "POST",
                data: {search: search, _token: _token},
                dataType: "json",

                success: function (response) {
                    $(".search-tags").slideDown();
                    $(".search-tags li").remove();
                    response.forEach((item) => {
                        $(".search-tags").append(`<li>${item.title}</li>`);
                    });
                },
            });
        }
    });

    $("#study-form").on("submit", function () {
        let tags = new Array();
        Array.from($(this).find(".tags-container .tag-item")).forEach(
            (element) => {
                tags.push($(element).text().trim());
            }
        );

        let submitFlag = $(this).find(".errors").text().trim().length == 0;

        if (submitFlag) {
            $(this).find(".plan-tags").val(JSON.stringify(tags));
        }
    });


    $(document).on("keyup", ".in-search", function (event) {
        var search = $(this).val();
        $.ajax({
            url: "/dashboard/study/search",
            method: "POST",
            data: {search: search, _token: $('meta[name="csrf-token"]').attr("content")},
            dataType: 'json',

            success: function (response) {
                $(".search-table").text("");
                if (response['studies'].length == 0) {
                    $('.search-table').append('<td valign="top" colspan="8" class="dataTables_empty">داده ای در دسترس نیست</td>');
                } else {
                    let counter = 1;
                    $.each(response['studies'], function (key, value) {
                        $(".search-table").append(`<tr id="study-${value.id}">
                                                <td> ${counter++}</td>
                                                <td>${value.title}</td>
                                                <td>${value.studies_type}</td>
                                                <td>${value.start_date}</td>
                                                <td>${value.end_date}</td>
                                                <td data-id="${value.id}">${value.number == 0 ? 'تعیین نشده' : value.number}</td>
                                                <td>` +
                            (response.status ? (value.status == 2 ? `تکمیل نشده` : (value.status == 1
                                ? `<div class="activity-btn  switch-to-active">
                            <span class="activity-switch"></span>
                            <input type="checkbox" class="d-none" data-id="${value.id}" checked />
                            </div>`
                                : `<div class="activity-btn">
                            <span class="activity-switch"></span>
                            <input type="checkbox" class="d-none" data-id="${value.id}" />
                            </div>`)) : (value.status == 2 ?
                                `<a class="link-color"
                                                           href="/dashboard/study/${value.id}/edit">تکمیل
                                                            نشده</a>` : (
                                value.status == 1 ? `تایید شده` : `تایید نشده`))) + `</td><td>

                                                        <div class="d-flex justify-content-center">
                                                        ${value.file ? `
                                                        <a href="http://survey.clitri.ir/${response.token}/${value.id}"
                                                               class="btn btn-primary text-white shadow btn-xs sharp"><i
                                                                    class="fa-solid fa-solid fa-file"></i></a>` : ''}
                                                        ${value.detail ? `
                                                         <a href="/dashboard/study/detail/${value.id}"
                                                   class="view-plan-btn shadow btn-xs sharp">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>`:''}

                            ${value.edit ?
                                `<a href="/dashboard/study/${value.id}/edit"
                                                                       class="btn btn-primary text-white shadow btn-xs sharp"><i
                                                                            class="fa-solid fa-pencil"></i></a>` : ``}
                            ${response.delete ?
                                `<a onclick="deleterecord(${value.id})"
                                                               class="btn btn-danger text-white shadow btn-xs sharp"><i
                                                                    class="fa-solid fa-trash"></i></a>` : ``}

                            ${value.rand ? `
                                                        <a href="/dashboard/patient/randomisation/${value.id}"
                                                                   class="analysis-plan-btn shadow btn-xs sharp"><i
                                                                        class="fa-solid fa-compass-drafting"></i></a>` : ``}
                            ${value.sample ? `

                                                            <a data-id="${value.id}"
                                                               href="#"
                                                               class="calc-plan-btn shadow btn-xs sharp"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#sample_size_modal"
                                                            >
                                                                <i class="fa-solid fa-calculator"></i>
                                                            </a>` : ``}
                                                         </div>
                                                    </td>
                                            </tr>
`);
                    });
                }
                $(".activity-btn").on("click", activityChange);
            }
        });
    });
    $(".activity-btn").on("click", activityChange);

    // $(document).on("click", ".answer-number", function (event) {
    //     if (this.innerText == 'تعیین نشده') {
    //         $("#id").val($(this).data('id'));
    //         $("#answerNumber").modal("toggle");
    //     }
    //     let id = $(this).data('id');
    //
    //
    // });
    // $(document).on("submit", "#answerForm", function (event) {
    //     event.preventDefault();
    //     let formData = new FormData(this);
    //     // console.log(id);
    //     $.ajax({
    //         url: "/dashboard/study/patient/",
    //
    //         method: "post",
    //
    //         data: formData,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //
    //         success: function (response) {
    //             $('#answerForm')[0].reset();
    //             $("#answerNumber").modal("hide");
    //
    //             Swal.fire("موفقیت آمیز", "اختصاص بیماران به طرح انجام شد", "success");
    //         },
    //         error: function (error) {
    //             console.log(error);
    //             Swal.fire("خطا!", "تعداد وارد شده معتبر نیست", "error");
    //         },
    //     });
    // });

});

function deleterecord(id) {

    Swal.fire({
        title: "ایا از حذف مطمئن هستید؟",
        icon: "warning",
        confirmButtonColor: "#DD6B55",
        showCancelButton: true,
        confirmButtonText: "حذف",
        cancelButtonText: "لغو",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/dashboard/study/" + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {

                    _token: $('meta[name="csrf-token"]').attr('content'),
                },

                success: function (response) {
                    $("#study-" + id).remove();
                    Swal.fire(
                        'حذف!',
                        'طرح با موفقیت حذف شد!',
                        'success'
                    )

                },
            });
        }
    });
}

function activityChange(event) {
    const target = $(this);
    const input = $(this).find("input[type=checkbox]");
    const id = $(this).find("input[type=checkbox]").data("id");
    const column_value = !$(this).find("input").prop("checked");
    console.log(column_value);

    $.ajax({
        url: "/dashboard/study/update-status",
        method: "PUT",
        data: {column_value: column_value, id: id, _token: $('meta[name="csrf-token"]').attr('content')},
        success: function (response) {
            if (input.prop("checked")) {
                input.attr("checked", false);
                target.removeClass("switch-to-active");
            } else {
                input.attr("checked", true);
                target.addClass("switch-to-active");
            }

            Swal.fire("وضعیت ویرایش شد", "", "success");
        },
    });
}

$(document).on('change', '#study_sort', function () {
    if ($(this).val()) {
        if (location.href.includes("study/sort")) {
            location.href = $(this).val();
        } else {
            location.href = `study/sort/${$(this).val()}`;
        }
    }
})
