

function calFur13 (exit) {

    if($('#first_text').val() != ''){

        const zz = Z1_alpha2()+Z1_beta();
        const dd = 2 * (d()*d());
        const sig = sigma();

        var cal = Math.pow(sig * ( (zz) ),2)/(dd);
        cal = Math.ceil(cal);

        function xFunCal(){
            if(xx()){
                const x = (cal)/(1-xx());
                return x;
            } else {
                return null
            }
        }

        function drFunCal(){
            if(landa1() && landa2() && xx()){
                const drop = xFunCal()/Math.pow((1-landa1()-landa2()),2)
                return drop
            } else {
                return null
            }
        }


        if (!cal) {
            cal = 'تعریف نشده'
        }

        let x = '';
        if(xFunCal()){
            x = `<div> n<span class="list-item-index" >x</span> : <span class="Item textC" >${xFunCal()}</span> <br/> `
        }

        let dr = '';
        if(drFunCal()){
            dr = `<div> n<span class="list-item-index" >dr</span> : <span class="Item textC" >${drFunCal()}</span> <br/> `
        }



        $('#list_first').after(
            '<li id="li'+listNumbers+'" class="list-item-styles" > <span class="list-item-bold" >'+$('#first_text').val()+'</span><br/>' +
            '<div> n<span class="list-item-index" >c</span> :  <span class="Item textC" >'+cal+'</span> <br/> ' +


            x +


            dr +


            '<span class="closeItem right-icon" id="'+listNumbers+'"><img width="18" height="18" src="/images/delete-sign.png"/></span></li>'
        );

         if (cal != 'تعریف نشده') {
            saveResult(cal) ;
        }
        $('input').val('');

        if(exit){
            $('#designType').val('');
            $('#hypothesis').val('');
            $('#scale').val('');
            $('#furmul13').fadeOut().css("display", "none");
        }
        listNumbers++;
    } else {
        alert('مقدار پیامد نمی تواند خالی باشد')
    }

}

$(document).on('click', '#selectBtnExit13', ()=>{
    calFur13(true)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
$(document).on('click', '#selectBtn13', ()=>{
    calFur13(false)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
