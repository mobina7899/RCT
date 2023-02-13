

function calFur18 (exit) {

    if($('#first_text').val() != ''){


        const zz = Z_alpha()+Z1_beta();
        const dd = Math.pow(d(),2);
        const sigma2 = Math.pow(sigma(),2);

        console.log(zz ** 2);

        var cal = (Math.pow( ( zz ),2) * sigma2) / (dd);
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
            $('#furmul18').fadeOut().css("display", "none");
        }
        listNumbers++;
    } else {
        alert('مقدار پیامد نمی تواند خالی باشد')
    }

}

$(document).on('click', '#selectBtnExit18', ()=>{
    calFur18(true)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
$(document).on('click', '#selectBtn18', ()=>{
    calFur18(false)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
