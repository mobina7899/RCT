

function calFur17 (exit) {

    if($('#first_text').val() != ''){


        const zz = Z_alpha2()+Z_beta();
        const p1p2 = p1()-p2();
        const dd = m() * (Math.pow(p1p2,2));
        const pp1 = p1() * (1 - p1());
        const pp2 = p2() * (1 - p2());
        const sm = 2 * (Math.pow(sigmaBm(),2)) * (m()-1);

        var cal = (Math.pow( ( zz ),2) * (pp1+ pp2 + sm))/(dd);
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
            $('#furmul17').fadeOut().css("display", "none");
        }
        listNumbers++;
    } else {
        alert('مقدار پیامد نمی تواند خالی باشد')
    }

}

$(document).on('click', '#selectBtnExit17', ()=>{
    calFur17(true)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
$(document).on('click', '#selectBtn17', ()=>{
    calFur172(false)
    window.scrollTo({top: 0, behavior: 'smooth'});
});
