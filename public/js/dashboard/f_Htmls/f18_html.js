let f18Html = `<div id="furmul18" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs18" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn18" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit18" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`






/*
$(document).ready(()=>{
    $('#furmules').append(fHtml);
    $(document).ready(()=>{
        activeVars = [k_Html,sigma_Html,alpha_Html,beta_Html,delta_Html,mu1_Html,mu2_Html];
        for (let item in activeVars){
            $('#formInputs18').append(activeVars[item]);
            console.log('as')
        }
    })
})*/



$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==20) {
            $('#furmules').append(f18Html);
            $(document).ready(()=>{
                activeVars = [sigma_Html,alpha_Html,betaS_Html,mu1_Html,mu2_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs18').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul18').remove();
        }
    });
})
