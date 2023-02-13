let f8Html = `<div id="furmul8" style="margin-top: 20px;margin-bottom: 20px;display: none">
                    <div style="border-bottom: 2px solid grey;margin-top: 30px;margin-bottom: 30px"></div>

                    <div id="formInputs8" class="row">

                        
                        
                    </div>
                    
                    <div class="d-flex justify-space-around" >
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtn8" class="cal-button btn btn-info btn-block">محاسبه
                    </button>
                    <button style="height: 42px;width: 120px;margin: 0" id="selectBtnExit8" class="cal-button btn btn-warning btn-block">محاسبه و خروج
                    </button>
                </div>

                </div>`






/*
$(document).ready(()=>{
    $('#furmules').append(fHtml);
    $(document).ready(()=>{
        activeVars = [k_Html,alpha_Html,beta_Html,OR_Html,pT_Html,pC_Html];
        for (let item in activeVars){
            $('#formInputs8').append(activeVars[item]);
            console.log('as')
        }
    })
})*/


$(document).ready(()=>{
    $(document).on('change', '#designType', function(){
        fid = $('#designType').val();
        if(fid==28){
            $('#furmules').append(f8Html);
            $(document).ready(()=>{
                activeVars = [k_Html,alpha_Html,delta_Html,beta_Html,OR_Html,pT_Html,pC_Html,x_Html,landa1_Html,landa2_Html];
                for (let item in activeVars){
                    $('#formInputs8').append(activeVars[item]);
                }
            })
        } else {
            $('#furmul8').remove();
        }
    });
})
