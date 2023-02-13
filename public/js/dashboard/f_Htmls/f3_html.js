let f3Html = `<div id="furmul3" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs3" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn3" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit3" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`







$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==5){
            $('#furmules').append(f3Html);
            $(document).ready(()=>{
                activeVars = [k_Html,sigma_Html,alpha_Html,beta_Html,delta_Html,mu1_Html,mu2_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs3').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul3').remove();
        }
    });
})