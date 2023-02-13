let f19Html = `<div id="furmul19" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs19" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn19" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit19" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`






/*
$(document).ready(()=>{
    $('#furmules').append(fHtml);
    $(document).ready(()=>{
        activeVars = [k_Html,sigma_Html,alpha_Html,beta_Html,delta_Html,mu1_Html,mu2_Html];

        for (let item in activeVars){
            $('#formInputs19').append(activeVars[item]);
            console.log('as')
        }
    })
})*/


$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==6) {
            $('#furmules').append(f19Html);
            $(document).ready(()=>{
                activeVars = [alpha_Html,betaS_Html,delta_Html,sigma_B_Html,sigma_err_Html,D_Html,P_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs19').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul19').remove();
        }
    });
})
