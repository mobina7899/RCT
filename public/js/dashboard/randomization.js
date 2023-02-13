const regSpecial = /[`!@#$%^&*()_+\-=\[\]{};':"\\|.<>\/?~]/;
let sickness = [];

let sickSearch = [];

const data = {
    age: [],
    sick: [],
    height: [],
    weight: [],
};

let selected = -1;

$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/dashboard/study/sicknesses",
        success: function (response) {
            console.log(response);
            sickness = response;
        },
    });
});

$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/dashboard/study/provinces",
        success: function (response) {
            console.log(response);
            cities = response;
        },
    });
});

$(document).on("change", "#stratification_input", function (event) {
    updateStrat($(this).val());
});

function updateStrat(statifics) {
    const str_box = $("#stratification_box");
    ["age", "sick", "city", "marid", "weight", "height", "gender"].forEach(
        (item) => {
            if (!statifics.includes(item)) {
                item in data ? (data[item] = []) : "";
                $(`input[name=${item}]`).closest(".col-12").remove();
                $(`select[name=${item}]`).closest(".col-12").remove();
            }
        }
    );

    if (statifics.includes("age") && $("input[name=age]").length == 0) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">سن</h5>
          <input
            type="text"
            name="age"
            class="form-control"
          />
          <i class="fa-solid fa-plus"></i>
          <div class="errors text-danger"></div>
        </div>
        <div class="item-box mt-2"></div>
      </div>
    `);
    }

    if (statifics.includes("city[]") && $(`select[name="city[]"]`).length == 0) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">شهر</h5>
          <select name="city[]" class="form-select" multiple>
            ${cities
            .map((item) => `<option value="${item.id}">${item.name}</option>`)
            .toString()
            .replaceAll(",", "")}
          </select>
        </div>
      </div>
    `);
    }
    if (statifics.includes("sick") && $("input[name=sick]").length == 0) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1 position-relative">
        <div class="form-group">
          <h5 class="mb-1 mt-3">نوع عارضه</h5>
          <input
            type="text"
            name="sick"
            class="form-control"
            autocomplete="off"
          />
          <i class="fa-solid fa-plus"></i>
          <div class="errors text-danger"></div>
        </div>
        <div class="item-box mt-2"></div>
        <ul class="sick-search">
          ${sickSearch
            .map((item) => `<li data-val="${item}">${item}</li>`)
            .toString()
            .replaceAll(",", "")}
        </ul>
      </div>
    `);
    }
    if (statifics.includes("weight") && $("input[name=weight]").length == 0) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">وزن</h5>
          <input
            type="text"
            name="weight"
            class="form-control"
          />
          <i class="fa-solid fa-plus"></i>
          <div class="errors text-danger"></div>
        </div>
        <div class="item-box mt-2"></div>
      </div>
    `);
    }
    if (
        statifics.includes("gender[]") &&
        $(`select[name="gender[]"]`).length == 0
    ) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">جنسیت</h5>
          <select name="gender[]" class="form-select" multiple>
            <option value="2">زن</option>
            <option value="1">مرد</option>
            <option value="3">هردو</option>
          </select>
        </div>
      </div>
    `);
    }
    if (statifics.includes("height") && $("input[name=height]").length == 0) {
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">قد</h5>
          <input
            type="text"
            name="height"
            class="form-control"
          />
          <i class="fa-solid fa-plus"></i>
          <div class="errors text-danger"></div>
        </div>
        <div class="item-box mt-2"></div>
      </div>
    `);
    }
    if (
        statifics.includes("marid[]") && $(`select[name = "marid[]"]`).length == 0
    ){
        str_box.append(`
    <div class="col-12 col-lg-6 px-3 mb-1">
        <div class="form-group">
          <h5 class="mb-1 mt-3">وضعیت تاهل</h5>
          <select name="marid[]" class="form-select" multiple>
            <option value="0">مجرد</option>
            <option value="1">متاهل</option>
          </select>
        </div>
      </div>
    `);
}
}

$(document)
    .on("focus", "input[name=sick]", function () {
        $(this).parent().siblings(".sick-search").slideDown();
    })
    .on("blur", "input[name=sick]", function (event) {
        $(this).parent().siblings(".sick-search").slideUp();
    })
    .on("mousedown", ".sick-search li", function (event) {
        console.log($(this).data("val"));
        $("input[name=sick]").val($(this).data("val"));
    })
    .on("keypress", "input[name=sick]", function (event) {
        if (event.key == "Enter") {
            event.preventDefault();
            addSick($(this));
        }
    })
    .on("keyup", "input[name=sick]", function (event) {
        if (event.key !== "ArrowDown" && event.key !== "ArrowUp")
            searchSick(event, this);
    })
    .on("click", "input[name=sick] + i.fa-plus", function () {
        addSick($(this).siblings("input[name=sick]"));
    })
    .on("click", ".item-box i.fa-xmark", function () {
        console.log($(this).siblings("span").text());
        deleteItems($(this), $(this).siblings("span").text());
    })
    .on("keypress", "input[type=text]:not(input[name=sick])", function (event) {
        if (event.key == "Enter") {
            event.preventDefault();
            addItems($(this));
        }
    })
    .on(
        "click",
        "input[type=text]:not(input[name=sick]) + i.fa-plus",
        function () {
            addItems($(this).siblings("input[type=text]"));
        }
    );

function searchSick(event, input) {
    sickSearch = [];

    sickness.forEach((item) => {
        if (item.includes($(input).val())) {
            sickSearch.push(item);
        }
    });

    updateSearch($(input).parent().siblings(".sick-search"));
}

function updateSearch(list) {
    list.empty();
    selected = -1;
    sickSearch.forEach((item) =>
        list.append(`<li data-val="${item}">${item}</li>`)
    );
    updateSelected(selected, list.find("li"));
}

function addSick(input) {
    const error = input.siblings(".errors");
    if (input.val() == "") {
        error.text("* مقدار این فیلد نمی تواند خالی باشد!");
    } else if (data.sick.includes(input.val())) {
        error.text("* این مقدار قبلا اضافه شده است!");
    } else if (!sickness.includes(input.val())) {
        error.text("* عارضه وارد شده وجود ندارد!");
    } else {
        error.text("");
        data.sick.push(input.val());
        input.val("");
        updateItems(input, data.sick);
    }
}

function addItems(input) {
    const error = input.siblings(".errors");
    if (input.val() == "") {
        error.text("* مقدار این فیلد نمی تواند خالی باشد!");
    } else if (!validateInput(input.val())) {
        error.text("* لطفا یک مقدار معتبر وارد کنید!");
    } else {
        error.text("");
        console.log(validateInput(input.val()));
        data[input.attr("name")].push(validateInput(input.val()));
        input.val("");
        console.log(data[input.attr("name")]);
        updateItems(input, data[input.attr("name")]);
    }
}

function deleteItems(elm, val) {
    const input = elm
        .parents(".item-box")
        .siblings(".form-group")
        .find("input[type=text]");
    console.log(data[input.attr("name")]);
    data[input.attr("name")] = data[input.attr("name")].filter(
        (item) => item !== val
    );

    updateItems(input, data[input.attr("name")]);
}

function updateItems(input, data) {
    input.parent().siblings(".item-box").empty();
    data.forEach((item) => {
        input.parent().siblings(".item-box").append(`
    <span class="px-2 py-1 m-1">
          <i class="fa-solid fa-xmark ml-1" data-val="${item}"></i>
          <span class="d-inline">${item}</span>
        </span>
    `);
    });
}

function validateInput(value) {
    const valA = value.split("-");
    const val_0 = valA[0];
    const val_1 = valA[1];
    if ((valA.length == 1 && Number(val_0) == 0) ||  Number(val_0)) {
        return value.replaceAll(" ", "");
    } else if (
        (valA.length == 2 && Number(val_0)) ||
        (Number(val_0) == 0 && Number(val_1)) ||
        Number(val_0) == 0
    ) {
        return value.replaceAll(" ", "");
    } else {
        return false;
    }
}

// =============== multi selection =============

$(document).on("mousedown", "select[multiple] option", function (e) {
    e.preventDefault();
    $(this).toggleClass("selected");
    $(this).prop("selected", !$(this).prop("selected"));
    $(this).parent().change();
});

// ================ submit form ===================

$(document).on("submit", "#randomization", function (event) {
    const submitFlag = $(this).find(".errors").text().trim().length === 0;

    if (!submitFlag) {
        event.preventDefault();
    } else {
        Object.keys(data).forEach((key) => {
            $(this).find(`input[name=${key}]`).val(JSON.stringify(data[key]));
        });
    }
});

// ===================== change selected ==============

$(document).on("keydown", "input[name=sick]", function (event) {
    const listItems = $(this).parent().siblings(".sick-search").find("li");
    if (event.key == "ArrowDown" && selected < listItems.length - 1) {
        selected++;
        updateSelected(selected, listItems, $(this));
    } else if (event.key == "ArrowUp" && selected > 0) {
        selected--;
        updateSelected(selected, listItems, $(this));
    }
});

function updateSelected(selected, list, input) {
    list.removeClass("selected");
    $(list[selected]).addClass("selected");
    if (input) {
        input.val($(list[selected]).data("val"));
    }
}
