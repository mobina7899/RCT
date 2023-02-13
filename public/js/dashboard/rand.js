
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
                url: "/dashboard/patient/rand/delete/" + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {

                    _token: $('meta[name="csrf-token"]').attr('content'),
                },

                success: function (response) {
                    $("#rand-" + id).remove();
                    Swal.fire(
                        'حذف!',
                        'بیمارها با موفقیت حذف شدند!',
                        'success'
                    )

                },
            });
        }
    });
}


