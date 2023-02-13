let f12Html = `<div id="furmul12" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs12" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn12" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit12" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`






/*
$(document).ready(()=>{
    $('#furmules').append(fHtml);
    $(document).ready(()=>{
        activeVars = [k_Html,alpha_Html,beta_Html,delta_Html,HR_Html,PiK_variable];

        for (let item in activeVars){
            $('#formInputs12').append(activeVars[item]);
            console.log('as')
        }
    })
})*/


$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==15){
            $('#furmules').append(f12Html);
            $(document).ready(()=>{
                activeVars = [k_Html,alpha_Html,beta_Html,delta_Html,HR_Html,PiK_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs12').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul12').remove();
        }
    });
})
