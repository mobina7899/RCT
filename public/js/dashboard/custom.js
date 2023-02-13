
var listNumbers = 0;

var fid = null;
studyId = null ;
$(document).ready(function () {

    function checkDesignType(){
        const designType_id = $('#designType').val();

        /*if(designType_id) {
            $('#calBtns').fadeIn().css("display", "flex");
        } else {
            $('#calBtns').fadeOut().css("display", "none");
        }*/
        $.getJSON('http://clitri.ir/dashboard/get-json', function (data) {
            $.each(data, function (key, value) {

                if (value.id == designType_id) {
                    $(value.furmula).fadeIn().css("display", "block");
                } else {
                    $(value.furmula).fadeOut().css("display", "none");
                }

            });
        });
    }



    load_json_data('scale');

    function load_json_data(id, parent_id) {
        var html_code = '';
        $.getJSON('http://clitri.ir/dashboard/get-json', function (data) {

            let idName = '';
            if(id=='scale'){
                idName = 'نوع طرح مطالعه';
            } else if (id=='hypothesis') {
                idName = 'مقیاس';
            } else if (id=='designType') {
                idName = 'نوع فرضیه';
            }

            html_code += '<option value="">انتخاب ' + idName + '</option>';
            $.each(data, function (key, value) {
                if (id == 'scale') {
                    if (value.parent_id == '0') {
                        html_code += '<option value="' + value.id + '">' + value.name + '</option>';
                    }
                } else {

                    if (value.parent_id == parent_id) {
                        html_code += '<option value="' + value.id + '">' + value.name + '</option>';
                        $('#fur').html(
                            '<div id="result"></div>'
                        );
                    }
                }
            });
            $('#' + id).html(html_code);
        });

    }

    $(document).on('change', '#scale', function(){
        var scale_id = $(this).val();
        if(scale_id != '')
        {
            load_json_data('hypothesis', scale_id);
        }
        else
        {
            $('#hypothesis').html('<option value=""> نوع مقیاس را انتخاب کنید</option>');
            $('#designType').html('<option value="">نوع فرضیه را انتخاب کنید</option>');
            $('#fur').html(
                '<div id="result"></div>'
            );
        }
        $('#designType').html('<option value="">نوع فرضیه را انتخاب کنید</option>');
        checkDesignType();
    });

    $(document).on('change', '#hypothesis', function(){
        var hypothesis_id = $(this).val();

        if(hypothesis_id != '')
        {
            load_json_data('designType', hypothesis_id);
        }
        else
        {
            $('#fur').html(
                '<div id="result"> No</div>'
            );
        }
        $('#designType').html('<option value="">نوع فرضیه را انتخاب کنید</option>');
        checkDesignType();
    });


    /*$(document).on('change', '#hypothesis', function () {
        var hypothesis_id = $(this).val();

        if (hypothesis_id != '') {
            $('#selectBtn').prop('disabled', false);
            $.getJSON('http://clitri.ir/dashboard/get-json', function (data) {
                $.each(data, function (key, value) {

                    if (value.id == hypothesis_id) {
                        console.log(value.furmula);
                        $(value.furmula).fadeIn().css("display", "block");
                    } else {
                        $(value.furmula).fadeOut().css("display", "none");
                    }

                });
            });
        } else {
            $('#selectBtn').prop('disabled', true);
            $('#fur').html(
                '<div id="result"> No</div>'
            );
        }
    });*/



    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        checkDesignType();
    });
    /*$(document).on('keyup', '#first_text', function(){
        var first_text = $(this).val();

        if(first_text == '')
        {
            $('#designType').prop('disabled', true);
            $('#scale').prop('disabled', true);
            $('#hypothesis').prop('disabled', true);

        } else {
            $('#designType').prop('disabled', false);
            $('#scale').prop('disabled', false);
            $('#hypothesis').prop('disabled', false);
        }
    });*/

    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text;
        sampleTextarea.select();
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
    }

    $('#CalList').delegate('.Item', 'click', function(e){
        var id = this.id;
        copyToClipboard(e.target.textContent);
        $('.copied').show();
        $('.copied').fadeOut(2000);
    });

    $("ul").on("click", ".closeItem", function(e) {
        $(this).closest('li').fadeOut();
    });

});

$(document).on("click", ".calc-plan-btn", showCalc);

function showCalc(event){
    const id = $(this).data('id');
    window.studyId = id ;
    $.ajax({
        url: "/dashboard/study/study_design/" + id,
        method: "get",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (response) {
            // $('#hypothesis').val(response).prop('disabled' , true);
            $('#scale').val(response).change().prop('disabled' , true);

        },
        error: function (error) {
        },
    });
}
function saveResult(result) {
    $.ajax({
        method: "get",
        url: "/dashboard/study/sample/"+studyId+'/'+result,

        success: function (response) {
            // swal.fire("ذخیره شد" , 'حجم نمونه ذخیره شد' , 'success');
            Swal.fire({
                title: "حجم نمونه ذخیره شد ! برای ادامه رندومایزیش را انجام دهید",
                icon: "success",
                confirmButtonColor: "#DD6B55",
                showCancelButton: true,
                confirmButtonText: "انجام رندومایزیشن",
                cancelButtonText: "بستن",
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location.href = '/dashboard/patient/randomisation/'+studyId;
                }
            });

        },
    });
}
