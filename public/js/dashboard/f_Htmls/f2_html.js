let f2Html = `<div id="furmul2" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs2" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn2" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit2" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`







$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==4){
            $('#furmules').append(f2Html);
            $(document).ready(()=>{
                activeVars = [k_Html,sigma_Html,alpha_Html,beta_Html,delta_Html,mu1_Html,mu2_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs2').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul2').remove();
        }
    });
})