var k_variable = null; //
var sigma_variable = null; //
var sigmaW_variable = null; //
var sigmaBm_variable = null; //
var alpha_variable = null; //
var beta_variable = null; //
var delta_variable = null; //
var mu1_variable = null; //
var mu2_variable = null; //
var pT_variable = null; //
var pC_variable = null;//
var p1_variable = null;//
var p2_variable = null;//
var OR_variable = null;//
var HR_variable = null;//
var PiK_variable = null; //
var m_variable = null; //
var Rho_variable = null;//
var Rho1_variable = null;//
var Rho2_variable = null;//
var x_variable = null;//
var landa1_variable = null;//
var landa2_variable = null;//

var D_variable = null;//
var P_variable = null;//
var sigma_err_variable = null;//
var sigma_B_variable = null;//

$('#furmules').on('change', () => {
    k_variable = parseFloat($('#cal_k').val());
    sigma_variable = parseFloat($('#cal_sigma').val());
    sigmaW_variable = parseFloat($('#cal_sigmaW').val());
    sigmaBm_variable = parseFloat($('#cal_sigmaBm').val());
    alpha_variable = parseFloat($('#cal_alpha').val());
    beta_variable = parseFloat($('#cal_beta').val());
    if (parseFloat($('#cal_betaS').val())) {
        beta_variable = parseFloat($('#cal_betaS').val());
    }
    delta_variable = parseFloat($('#cal_delta').val());
    mu1_variable = parseFloat($('#cal_mu1').val());
    mu2_variable = parseFloat($('#cal_mu2').val());
    pT_variable = parseFloat($('#cal_pT').val());
    pC_variable = parseFloat($('#cal_pC').val());
    p1_variable = parseFloat($('#cal_p1').val());
    p2_variable = parseFloat($('#cal_p2').val());
    OR_variable = parseFloat($('#cal_OR').val());
    HR_variable = parseFloat($('#cal_HR').val());
    PiK_variable = parseFloat($('#cal_PiK').val());
    m_variable = parseFloat($('#cal_m').val());
    Rho_variable = parseFloat($('#cal_Rho').val());
    Rho1_variable = parseFloat($('#cal_Rho1').val());
    Rho2_variable = parseFloat($('#cal_Rho2').val());
    x_variable = parseFloat($('#cal_x').val());
    landa1_variable = parseFloat($('#cal_landa1').val());
    landa2_variable = parseFloat($('#cal_landa2').val());

    D_variable = parseFloat($('#cal_D').val());
    P_variable = parseFloat($('#cal_P').val());
    sigma_err_variable = parseFloat($('#cal_sigma_err').val());
    sigma_B_variable = parseFloat($('#cal_sigma_B').val());
})

function D(){
    return D_variable;
}

function P(){
    return P_variable;
}

function sigma_err(){
    return sigma_err_variable;
}

function sigma_B(){
    return sigma_B_variable;
}

function xx(){
    return x_variable;
}

function landa1(){
    return landa1_variable;
}

function landa2(){
    return landa2_variable;
}

function Rho() {
    return Rho_variable;
}

function Rho1() {
    return Rho1_variable;
}

function Rho2() {
    return Rho2_variable;
}

function Sum() {
    return 1
}

function m() {
    return m_variable;
}

function OR() {
    return OR_variable;
}

function HR() {
    return HR_variable;
}

function pT() {
    return pT_variable;
}

function pC() {
    return pC_variable;
}

function p1() {
    return p1_variable;
}

function p2() {
    return p2_variable;
}

function PiK() {
    return PiK_variable;
}

function sigma() {
    return sigma_variable;
}

function sigmaW() {
    return sigmaW_variable;
}

function sigmaBm() {
    return sigmaBm_variable;
}

function k() {
    return k_variable;
}

// cal Alpha //////////////////////////////////

function Z1_alpha() {
    return qnorm(1 - alpha_variable);
}

function Z1_alpha2() {
    return qnorm(1 - (alpha_variable / 2));
}

function Z_alpha2() {
    return qnorm(alpha_variable / 2);
}

function Z_alpha() {
    return qnorm(alpha_variable);
}

//////////////////////////////////////////


// cal Beta //////////////////////////////////

function Z1_beta() {
    return qnorm(1 - beta_variable);
}

function Z1_beta2() {
    return qnorm((1 - beta_variable) / 2);
}

function Z_beta() {
    return qnorm(beta_variable);
}

function Z_beta2() {
    return qnorm(beta_variable / 2);
}

//////////////////////////////////////////


function delta() {
    return delta_variable;
}

function d() {
    return (mu1_variable - mu2_variable);
}



