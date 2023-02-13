$(document).ready(function () {
    let state = $('#study-form').data('step') !== 3 ? $('#study-form').data('step') ? $('#study-form').data('step') + 1 : 1 : 1;
    let buttonPos = "";

    const steps = Array.from($(".steps .step"));

    function updateNavigation() {
        if (state == 1) {
            $(".nav-form .nav-prev").css("opacity", "0");
            $(".nav-form .nav-prev").css("visibility", "hidden");
            $("#shortNext .prev-btn").css("display", "none");
        } else {
            $(".nav-form .nav-prev").css("visibility", "visible");
            $(".nav-form .nav-prev").css("opacity", "1");
            $("#shortNext .prev-btn").css("display", "inline-block");
        }

        if (state == 3) {
            $(".nav-form .nav-next").css("visibility", "hidden");
            $(".nav-form .nav-next").css("opacity", "0");
            $("#shortNext .next-btn").css("display", "none");
        } else {
            $(".nav-form .nav-next").css("visibility", "visible");
            $(".nav-form .nav-next").css("opacity", "1");
            $("#shortNext .next-btn").css("display", "inline-block");
        }
    }

    updateNavigation();

    function updateProgress() {
        for (let x = 0; x < state; x++) {
            if (x == state - 1) {
                steps[state - 1].classList.add("current");
            } else {
                steps[x].classList.add("active", "current");
                steps[x].querySelector(".step div").innerHTML =
                    "<i class='fa-solid fa-check'></i>";
            }
        }

        for (let x = state; x < 3; x++) {
            steps[x].classList.remove("current");
            steps[x - 1].classList.remove("active");
            steps[x - 1].querySelector(".step div").innerHTML = x;
        }
    }
    updateProgress();

    function updateForms() {
        steps.forEach((step) => {
            if ($(`.questions-container #${step.dataset.step}`).attr("id") != state) {
                $(`.questions-container #${step.dataset.step}`).css("display", "none");
            } else {
                $(`.questions-container #${step.dataset.step}`).css("display", "block");
            }
        });
    }

    updateForms();

    function nextControl() {
        if (state < 3) {
            steps[state - 1].classList.add("current");
            state += 1;
            updateProgress();
            updateForms();
            updateNavigation();
        }
    }

    $(".nav-form .nav-prev").on("click", function () {
        if (state > 1) {
            steps[state - 1].classList.remove("current");
            state -= 1;
            updateProgress();
            updateForms();
            updateNavigation();
        }
    });

    // ================== backend-error state ==================

    $(".steps .step").each((index, item) => {
        if (
            $(`.form-questions #${index + 1} .errors`)
                .text()
                .trim().length != 0
        ) {
            $(item).addClass("backend-error");
        }
    });

    // ============== validation ================

    const regDate =
        /^1[34][0-9][0-9]\/(0?[1-9]|1[012])\/(0?[1-9]|[12][0-9]|3[01])$/;
    const regSpecial = /[`!@#$%^&*()_+\=\[\]{};':"\\|.<>\/?~]/;

    $(".nav-next").on("click", function (event) {
        console.log('check')
        buttonPos = "next";
        validateForm();

        let nextFlag =
            $(`.form-questions #${state} .errors`).text().trim().length === 0;

        if (nextFlag) {
            const id = $("#study-form").data("id");
            if (state == 1) {
                updateStateOne(id);
            } else if (state == 2) {
                updateStateTwo(id);
            }
        }
    });

    function validateForm() {
        const titlePlan = $(`.form-questions #${state}`).find(
            'input[name="title"]'
        );
        const nickName = $(`.form-questions #${state}`).find('input[name="name"]');
        const file = $(`.form-questions #${state}`).find("input[name=file]");

        if (titlePlan.length > 0) {
            if (titlePlan.val() == "") {
                event.preventDefault();
                titlePlan.next(".errors").text("* فیلد عنوان نمی تواند خالی باشد!");
                titlePlan.addClass("field-error");
            } else if (regSpecial.test(titlePlan.val())) {
                event.preventDefault();
                titlePlan
                    .next(".errors")
                    .text("* مقدار وارد شده نباید دارای کاراکتر خاص باشد!");
                titlePlan.addClass("field-error");
            } else {
                titlePlan.next(".errors").text("");
                titlePlan.removeClass("field-error");
            }
        }

        if (nickName.length > 0) {
            if (regSpecial.test(nickName.val())) {
                event.preventDefault();
                nickName
                    .next(".errors")
                    .text("* مقدار وارد شده نباید دارای کاراکتر خاص باشد!");
                nickName.addClass("field-error");
            } else {
                nickName.next(".errors").text("");
                nickName.removeClass("field-error");
            }
        }

        if (file.length > 0 && $("#file-upload-name").length == 0) {
            if (file.val() == "") {
                $(".upload-main-wrapper")
                    .find(".errors")
                    .text("* این فیلد نمیتواند خالی باشد!");
                file.parent().addClass("field-error");
            } else if (file[0].files[0].size / 1024 ** 2 > 10) {
                $(".upload-main-wrapper")
                    .find(".errors")
                    .text("* فایل بارگزاری شده باید کوچکتر از 10MB باشد!");
                file.parent().addClass("field-error");
            } else {
                $(".upload-main-wrapper").find(".errors").text("");
                file.parent().removeClass("field-error");
            }
        }
        // ============ start_end date validation ==============

        const start_date = $(
            `.form-questions #${state} .date-inputs input[name=start_date]`
        );
        const end_date = $(
            `.form-questions #${state}  .date-inputs input[name=end_date]`
        );

        if (start_date.length > 0 && end_date.length > 0) {
            if (end_date.val() != "" && start_date.val() == "") {
                $(start_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* باید هردو فیلد تاریخ پر شود!");
                $(start_date).addClass("field-error");
            } else if (!regDate.test(start_date.val()) && start_date.val() != "") {
                $(start_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(start_date).addClass("field-error");
            } else {
                $(start_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(start_date).removeClass("field-error");
            }

            if (start_date.val() != "" && end_date.val() == "") {
                $(end_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* باید هردو فیلد تاریخ پر شود!");
                $(end_date).addClass("field-error");
            } else if (!regDate.test(end_date.val()) && end_date.val() != "") {
                $(end_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(end_date).addClass("field-error");
            } else if (dateValidation(start_date, end_date)) {
                $(end_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ پایان نمی تواند قبل از تاریخ شروع باشد!");
                $(end_date).addClass("field-error");
            } else {
                $(end_date)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(end_date).removeClass("field-error");
            }
        }

        const start_date_illness = $(
            `.form-questions #${state}  .date-inputs input[name=start_date_illness]:not(:disabled)`
        );
        const end_date_illness = $(
            `.form-questions #${state}  .date-inputs input[name=end_date_illness]:not(:disabled)`
        );

        if (end_date_illness.length > 0 && start_date_illness.length > 0) {
            if (start_date_illness.val() == "") {
                $(start_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* فیلد تاریخ نمیتواند خالی باشد!");
                $(start_date_illness).addClass("field-error");
            } else if (!regDate.test(start_date_illness.val())) {
                $(start_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(start_date_illness).addClass("field-error");
            } else {
                $(start_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(start_date_illness).removeClass("field-error");
            }

            if (end_date_illness.val() == "") {
                $(end_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* فیلد تاریخ نمیتواند خالی باشد!");
                $(end_date_illness).addClass("field-error");
            } else if (!regDate.test(end_date_illness.val())) {
                $(end_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(end_date_illness).addClass("field-error");
            } else if (dateValidation(start_date_illness, end_date_illness)) {
                $(end_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ پایان نمی تواند قبل از تاریخ شروع باشد!");
                $(end_date_illness).addClass("field-error");
            } else {
                $(end_date_illness)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(end_date_illness).removeClass("field-error");
            }
        }

        const start_get_sick_ended = $(
            `.form-questions #${state}  .date-inputs input[name=start_get_sick_ended]:not(:disabled)`
        );

        const end_get_sick_ended = $(
            `.form-questions #${state}  .date-inputs input[name=end_get_sick_ended]:not(:disabled)`
        );

        if (start_get_sick_ended.length > 0 && end_get_sick_ended.length > 0) {
            if (start_get_sick_ended.val() == "") {
                $(start_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* فیلد تاریخ نمیتواند خالی باشد!");
                $(start_get_sick_ended).addClass("field-error");
            } else if (!regDate.test(start_get_sick_ended.val())) {
                $(start_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(start_get_sick_ended).addClass("field-error");
            } else {
                $(start_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(start_get_sick_ended).removeClass("field-error");
            }

            if (end_get_sick_ended.val() == "") {
                $(end_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* فیلد تاریخ نمیتواند خالی باشد!");
                $(end_get_sick_ended).addClass("field-error");
            } else if (!regDate.test(end_get_sick_ended.val())) {
                $(end_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ وارد شده معتبر نیست!");
                $(end_get_sick_ended).addClass("field-error");
            } else if (dateValidation(start_get_sick_ended, end_get_sick_ended)) {
                $(end_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("* تاریخ پایان نمی تواند قبل از تاریخ شروع باشد!");
                $(end_get_sick_ended).addClass("field-error");
            } else {
                $(end_get_sick_ended)
                    .parent("div")
                    .parent(".date-inputs")
                    .find(".errors")
                    .text("");
                $(end_get_sick_ended).removeClass("field-error");
            }
        }

        $(".age-fields:not(:disabled)").each((index, item) => {
            if (parseInt($(item).val()) < 0 || parseInt($(item).val()) > 100) {
                $(item).next(".errors").text("*مقدار وارد شده معتبر نیست!");
                $(item).next(".errors").css("height", "auto");
            } else {
                $(item).next(".errors").text("");
            }
        });

        if(state == 3) {
            $(`#3 .specifications`).each((index, item) => {
                if ($(item).find(".entry-item").length == 0) {
                    event.preventDefault();
                    $(item)
                        .parent(".form-group")
                        .find(".errors")
                        .text("* حداقل یک شرط باید وارد شود!");
                    $(item).parent(".form-group").find(".errors").css("height", "auto");
                } else {
                    $(item).parent(".form-group").find(".errors").text("");
                    $(item).parent(".form-group").find(".errors").css("height", "0");
                }
            });
        }

        $(`#${state} .age-fields[name=min_month]:not(:disabled)`) &&
        validateMonth($(`#${state} .age-fields[name=min_month]:not(:disabled)`));
        $(`#${state} .age-fields[name=max_month]:not(:disabled)`) &&
        validateMonth($(`#${state} .age-fields[name=max_month]:not(:disabled)`));

        function validateMonth(item) {
            if (parseInt(item.val()) < 0 || parseInt(item.val()) > 12) {
                event.preventDefault();
                item.next(".errors").text("*مقدار وارد شده معتبر نیست!");
            } else {
                item.next(".errors").text("");
            }
        }

        $(`#${state} .age-fields[name=min_day]:not(:disabled)`) &&
        validateDay($(`#${state} .age-fields[name=min_day]:not(:disabled)`));
        $(`#${state} .age-fields[name=max_day]:not(:disabled)`) &&
        validateDay($(`#${state} .age-fields[name=max_day]:not(:disabled)`));

        function validateDay(item) {
            if (parseInt(item.val()) < 0 || parseInt(item.val()) > 31) {
                event.preventDefault();
                item.next(".errors").text("*مقدار وارد شده معتبر نیست!");
            } else {
                item.next(".errors").text("");
            }
        }

        $(".date-box").each((index, item) => {
            if ($(item).find(".errors").text().trim().length > 0) {
                $(item).parent().find("label").css("color", "#f25767");
            } else {
                $(item).parent().find("label").css("color", "#115169");
            }
        });

        function dateValidation(start, end) {
            const startDate = new Date(start.val());
            const endDate = new Date(end.val());

            return startDate >= endDate;
        }

        if (state == 2) {
            $("#delete_fields").click();
            $("#delete_type").click();

            const inter_table = $("#intervention_table");

            if (interventions.length == 0) {
                inter_table.find(".errors").text("*وارد کردن مداخله الزامی است!");
            }
        }
    }

    function updateStateOne(id) {
        const formData = new FormData();
        const textInputs = $("#1 input[type=text]");
        const selects = $("#1 select.form-select");
        const file = $("#1 input[type=file]");

        textInputs.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        selects.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });
        formData.append(
            file.attr("name"),
            file[0].files[0] ? file[0].files[0] : ""
        );
        let tags = new Array();
        Array.from($("#study-form .tags-container .tag-item")).forEach(
            (element) => {
                tags.push($(element).text().trim());
            }
        );
        formData.append("tags", JSON.stringify(tags));
        formData.append("step_number", state);
        formData.append("id", id);

        $.ajax({
            method: "POST",
            url: "/dashboard/study/update",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#study-form").data("id", response);
                Swal.fire(
                    "",
                    "ثبت اطلاعات این مرحله با موفقیت انجام شد!",
                    "success"
                ).then(() => {
                    if (buttonPos == "next") {
                        nextControl();
                    }
                });
            },
            error: function (error) {
                Swal.fire("", "ثبت اطلاعات این مرحله با خطا مواجه شد!", "error");
            },
        });
    }

    function updateStateTwo(id) {
        const formData = new FormData();
        const selects = $("#2 select:not(#Classification)");
        const check = $("#2 input[type=checkbox]");

        selects.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        formData.append(check.attr("name"), check.val());
        formData.append(
            "study_specifications",
            JSON.stringify(studySpecifications.map((item) => item.text))
        );
        formData.append("intervention", JSON.stringify(interventions));
        formData.append("step_number", state);
        formData.append("id", id);

        $.ajax({
            method: "POST",
            url: "/dashboard/study/update",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#study-form").data("id", response);
                Swal.fire(
                    "",
                    "ثبت اطلاعات این مرحله با موفقیت انجام شد!",
                    "success"
                ).then(() => {
                    if (buttonPos == "next") {
                        nextControl();
                    }
                });
            },
            error: function (error) {
                Swal.fire("", "ثبت اطلاعات این مرحله با خطا مواجه شد!", "error");
            },
        });
    }

    function updateStateThree(id) {
        const formData = new FormData();
        const selects = $("#3 select.form-select");
        const textInputs = $("#3 input[type=text]");
        const checks = $("#3 input[type=checkbox]");
        const numberInputs = $("#3 input[type=number]");

        selects.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        textInputs.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        numberInputs.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        checks.each((index, item) => {
            formData.append($(item).attr("name"), $(item).val());
        });

        formData.append(
            "entry_study",
            JSON.stringify(entryStudy.map((item) => item.text))
        );
        formData.append(
            "failure_entry_study",
            JSON.stringify(failEntryStudy.map((item) => item.text))
        );
        formData.append("step_number", state);
        formData.append("dates" , $("#study-form input[name=dates]").val());
        formData.append("id", id);

        for (const key of formData.keys()) {
            console.log(key, formData.get(key));
        }

        $.ajax({
            method: "POST",
            url: "/dashboard/study/update",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#study-form").data("id", response);
                Swal.fire(
                    "",
                    "ثبت اطلاعات این مرحله با موفقیت انجام شد!",
                    "success"
                ).then(() => {
                    if (buttonPos == "next") {
                        nextControl();
                    }
                });
            },
            error: function (error) {
                Swal.fire("", "ثبت اطلاعات این مرحله با خطا مواجه شد!", "error");
            },
        });
    }

    console.log( $("#shortNext .next-btn"))
    $("#shortNext .next-btn").on("click", function (event) {
        event.preventDefault();
        console.log("check");
        $(".nav-next").click();
    });

    $("#shortNext .prev-btn").on("click", function (event) {
        event.preventDefault();
        buttonPos = "prev";
        validateForm();

        let nextFlag =
            $(`.form-questions #${state} .errors`).text().trim().length === 0;

        if (nextFlag) {
            const id = $("#study-form").data("id");

            if (state == 2) {
                updateStateTwo(id);
            } else if (state == 3) {
                console.log("check");
                updateStateThree(id);
            }
            $(".nav-prev").click();
        }
    });
});
