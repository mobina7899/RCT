const getStudys = (item, index) => {
    $(item).data("id", index);
    return {
        id: index,
        text: $(item).text().trim(),
    };
};
studySpecifications = Array.from($("#s-d-specifications>.row .entry-item")).map(
    getStudys
);
entryStudy = Array.from($("#entry-container>.row .entry-item")).map(getStudys);
failEntryStudy = Array.from($("#fail-entry-container>.row .entry-item")).map(
    getStudys
);

$(document).ready(function () {
    $("#min-age-check").on("change", function () {
        if ($(this).prop("checked")) {
            $(this).next(".date-box").addClass("open");
            $(this)
                .next(".date-box")
                .find("input")
                .each((index, item) => {
                    item.disabled = false;
                });
        } else {
            $(this).next(".date-box").removeClass("open");
            $(this)
                .next(".date-box")
                .find("input")
                .each((index, item) => {
                    item.disabled = true;
                });
        }
    });

    $("#max-age-check").on("change", function () {
        if ($(this).prop("checked")) {
            $(this).next(".date-box").addClass("open");
            $(this)
                .next(".date-box")
                .find("input")
                .each((index, item) => {
                    item.disabled = false;
                });
        } else {
            $(this).next(".date-box").removeClass("open");
            $(this)
                .next(".date-box")
                .find("input")
                .each((index, item) => {
                    item.disabled = true;
                });
        }
    });

    $("#min-age-check")
        .siblings("label")
        .on("click", function (event) {
            event.stopPropagation();
            if ($("#min-age-check").prop("checked")) {
                if (!$(this).siblings(".date-box").hasClass("open")) {
                    console.log("check");
                    console.log($(this).siblings(".date-box"));
                    $(this).siblings(".date-box").addClass("open");
                } else {
                    $(this).siblings(".date-box").removeClass("open");
                }
            }
        });

    $("#max-age-check")
        .siblings("label")
        .on("click", function (event) {
            event.stopPropagation();
            if ($("#max-age-check").prop("checked")) {
                if (!$(this).siblings(".date-box").hasClass("open")) {
                    $(this).siblings(".date-box").addClass("open");
                } else {
                    $(this).siblings(".date-box").removeClass("open");
                }
            }
        });

    document.addEventListener(
        "click",
        function (event) {
            if (
                !$(event.target).parents().hasClass("date-box") &&
                !$(event.target).hasClass("date-box") &&
                $(".date-box").hasClass("open")
            ) {
                $(".date-box").removeClass("open");
            }
        },
        false
    );

    const regSpecial = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    const regSpace = /\s{2,}/g;

    // ================ study specifications ==============

    let s_d_speci = studySpecifications.length;

    $("input[name=study_specifications]").on("keypress", function (event) {
        if (event.key === "Enter") {
            study_specifications(event);
        }
    });

    $("input[name=study_specifications]")
        .next("i")
        .on("click", study_specifications);

    if (
        $("input[name=study_specifications]").siblings(".errors").text().trim()
            .length !== 0
    ) {
        $("input[name=study_specifications]")
            .siblings(".errors")
            .css("height", "auto");
    }

    function showConditionDropdown() {
        if ($("#s-d-specifications").find(".entry-item").length == 0) {
            $("#s-d-specifications").parent(".specifications").css("display", "none");
        } else {
            $("#s-d-specifications")
                .parent(".specifications")
                .css("display", "block");
        }
        if ($("#entry-container").find(".entry-item").length == 0) {
            $("#entry-container").parent(".specifications").css("display", "none");
        } else {
            $("#entry-container").parent(".specifications").css("display", "block");
        }
        if ($("#fail-entry-container").find(".entry-item").length == 0) {
            $("#fail-entry-container")
                .parent(".specifications")
                .css("display", "none");
        } else {
            $("#fail-entry-container")
                .parent(".specifications")
                .css("display", "block");
        }
    }

    showConditionDropdown();

    function study_specifications(event) {
        if ($("input[name=study_specifications]").val() == "") {
            event.preventDefault();
            $("input[name=study_specifications]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=study_specifications]")
                .next("i")
                .next(".errors")
                .text("* این فیلد نمی تواند خالی باشد!");
        } else if (
            regSpecial.test($("input[name=study_specifications]").val()) ||
            regSpace.test($("input[name=study_specifications]").val())
        ) {
            event.preventDefault();
            $("input[name=study_specifications]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=study_specifications]")
                .next("i")
                .next(".errors")
                .text("* ورودی نامعتبر است!");
        } else {
            $("input[name=study_specifications]").next("i").next(".errors").text("");
            studySpecifications.push({
                id: s_d_speci++,
                text: $("input[name=study_specifications]").val(),
            });
            $("input[name=study_specifications]").val("");
            updateSSpecific();
        }
        showConditionDropdown();
    }

    function updateSSpecific() {
        $("#s-d-specifications>.row").empty();
        console.log(studySpecifications);
        studySpecifications.forEach((item) => {
            $("#s-d-specifications>.row")
                .append(`<div class="col-12 entry-item" data-id=${item.id}>
      <p>${item.text}</p>
      <span class="entry-close"><i class="fa-solid fa-xmark"></i></span>
      </div>`);
        });
    }

    $(document).on("click", "#s-d-specifications>.row .entry-close", function () {
        const id = $(this).parent(".entry-item").data("id");
        studySpecifications = studySpecifications.filter((item) => item.id !== id);
        console.log(studySpecifications);
        updateSSpecific();
        showConditionDropdown();
    });

    // =========== Entry study ==============

    let entry_num = entryStudy.length;
    $("input[name=entry_study]").on("keypress", function (event) {
        if (event.key === "Enter") {
            entry_study(event);
        }
    });

    if (
        $("input[name=entry_study]").siblings(".errors").text().trim().length !== 0
    ) {
        $("input[name=entry_study]").siblings(".errors").css("height", "auto");
    }

    $("input[name=entry_study]").next("i").on("click", entry_study);

    function entry_study(event) {
        event.preventDefault();
        if ($("input[name=entry_study]").val() == "") {
            $("input[name=entry_study]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=entry_study]")
                .next("i")
                .next(".errors")
                .text("* این فیلد نمی تواند خالی باشد!");
        } else if (
            regSpecial.test($("input[name=entry_study]").val()) ||
            regSpace.test($("input[name=entry_study]").val())
        ) {
            $("input[name=entry_study]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=entry_study]")
                .next("i")
                .next(".errors")
                .text("* ورودی نامعتبر است!");
        } else {
            $("input[name=entry_study]").next("i").next(".errors").text("");
            entryStudy.push({
                id: entry_num++,
                text: $("input[name=entry_study]").val(),
            });
            updateEntryStudy();
            $("input[name=entry_study]").val("");
        }
        showConditionDropdown();
    }

    function updateEntryStudy() {
        $("#entry-container>.row").empty();
        entryStudy.forEach((item) => {
            $("#entry-container>.row")
                .append(`<div class="col-12 entry-item" data-id=${item.id}>
    <p>${item.text}</p>
    <span class="entry-close"><i class="fa-solid fa-xmark"></i></span>
  </div>`);
        });
    }

    $(document).on("click", "#entry-container>.row .entry-close", function () {
        const id = $(this).parent(".entry-item").data("id");
        entryStudy = entryStudy.filter((item) => item.id !== id);
        updateEntryStudy();
        showConditionDropdown();
    });

    // ============== Fail Entry study ============

    let fail_entry_num = failEntryStudy.length;
    $("input[name=failure_entry_study]").on("keypress", function (event) {
        if (event.key === "Enter") {
            failure_entry_study(event);
        }
    });

    $("input[name=failure_entry_study]")
        .next("i")
        .on("click", failure_entry_study);

    if (
        $("input[name=failure_entry_study]").siblings(".errors").text().trim()
            .length !== 0
    ) {
        $("input[name=failure_entry_study]")
            .siblings(".errors")
            .css("height", "auto");
    }

    function failure_entry_study(event) {
        event.preventDefault();
        if ($("input[name=failure_entry_study]").val() == "") {
            $("input[name=failure_entry_study]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=failure_entry_study]")
                .next("i")
                .next(".errors")
                .text("* این فیلد نمی تواند خالی باشد!");
        } else if (
            regSpecial.test($("input[name=failure_entry_study]").val()) ||
            regSpace.test($("input[name=failure_entry_study]").val())
        ) {
            $("input[name=failure_entry_study]")
                .next("i")
                .next(".errors")
                .css("height", "auto");
            $("input[name=failure_entry_study]")
                .next("i")
                .next(".errors")
                .text("* ورودی نامعتبر است!");
        } else {
            $("input[name=failure_entry_study]").next("i").next(".errors").text("");
            failEntryStudy.push({
                id: fail_entry_num++,
                text: $("input[name=failure_entry_study]").val(),
            });
            updateFailEntry();
            $("input[name=failure_entry_study]").val("");
        }
        showConditionDropdown();
    }

    function updateFailEntry() {
        $("#fail-entry-container>.row").empty();
        failEntryStudy.forEach((item) => {
            $("#fail-entry-container>.row")
                .append(`<div class="col-12 entry-item" data-id=${item.id}>
      <p>${item.text}</p>
      <span class="entry-close"><i class="fa-solid fa-xmark"></i></span>
      </div>`);
        });
    }
    $(document).on(
        "click",
        "#fail-entry-container>.row .entry-close",
        function () {
            const id = $(this).parent(".entry-item").data("id");
            failEntryStudy = failEntryStudy.filter((item) => item.id !== id);
            updateFailEntry();
            showConditionDropdown();
        }
    );

    function toggleLists(in1, in2) {
        $(in1).on("click", function () {
            if ($(this).find("i").hasClass("fa-angle-up")) {
                $(this).find("i").removeClass("fa-angle-up");
                $(this).find("i").addClass("fa-angle-down");
            } else {
                $(this).find("i").addClass("fa-angle-up");
                $(this).find("i").removeClass("fa-angle-down");
            }
            $(in2).slideToggle();
        });
    }

    // =====================================

    toggleLists(".entry-toggle1", "#entry-container");
    toggleLists(".entry-toggle2", "#fail-entry-container");
    toggleLists(".entry-toggle3", "#s-d-specifications");

    $("input[name=termination_illness]").on("change", function () {
        if ($("input[name=termination_illness]").prop("checked")) {
            $("input[name=start_date_illness]")
                .parent("div")
                .siblings(".errors")
                .text("");
            $("input[name=start_date_illness]").removeClass("field-error");
            $("input[name=end_date_illness]")
                .parent("div")
                .siblings(".errors")
                .text("");
            $("input[name=end_date_illness]").removeClass("field-error");

            $("input[name=start_date_illness]")
                .parent("div")
                .siblings("label")
                .find("strong")
                .text("تاریخ شروع بیمارگیری خاتمه یافته :");
            $("input[name=end_date_illness]")
                .parent("div")
                .siblings("label")
                .find("strong")
                .text("تاریخ پایان بیمارگیری خاتمه یافته :");

            $("input[name=start_date_illness]").attr("name", "start_get_sick_ended");
            $("input[name=end_date_illness]").attr("name", "end_get_sick_ended");
        } else {
            $("input[name=start_get_sick_ended]")
                .parent("div")
                .siblings(".errors")
                .text("");
            $("input[name=start_get_sick_ended]").removeClass("field-error");
            $("input[name=end_get_sick_ended]")
                .parent("div")
                .siblings(".errors")
                .text("");
            $("input[name=end_get_sick_ended]").removeClass("field-error");

            $("input[name=start_get_sick_ended]")
                .parent("div")
                .siblings("label")
                .find("strong")
                .text("تاریخ شروع بیمارگیری :");
            $("input[name=end_get_sick_ended]")
                .parent("div")
                .siblings("label")
                .find("strong")
                .text("تاریخ پایان بیمارگیری :");

            $("input[name=start_get_sick_ended]").attr("name", "start_date_illness");
            $("input[name=end_get_sick_ended]").attr("name", "end_date_illness");
        }
    });

    // =============== calendar click ================

    $("i.fa-calendar-days").on("click", function (event) {
        $(this).parent().find(".dates").focus();
    });

    // ============= upload file =================

    $(document).ready(function () {
        $("#upload-file").change(function () {
            var filename = $(this).val();
            $("#file-upload-name").html(filename);
            if (filename != "") {
                setTimeout(function () {
                    $(".upload-wrapper").addClass("uploaded");
                }, 600);
                setTimeout(function () {
                    $(".upload-wrapper").removeClass("uploaded");
                    $(".upload-wrapper").addClass("success");
                }, 1600);
            }
        });
    });
});
