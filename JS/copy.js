//CREATED BY: NORFIRDAUS HARUN
//PUSAT TEKNOLOGI MAKLUMAT DAN KOMUNIKASI , UPNM

<!-- Begin
var txtalamt1sm="";
var txtalamt2sm="";
var txtalamt3sm="";
var slctBandarsmIndex = 0;
var slctBandarsm="";
var txtPoskodsm="";
var slctNegerismIndex = 0;
var slctNegerism="";
var slctNegarasmIndex= 0;
var slctNegarasm="";
var txtNoTelt2="";

function InitSaveVariables(form) {
	txtalamt1sm= form.txtalamt1sm.value;
	txtalamt2sm= form.txtalamt2sm.value;
	txtalamt3sm= form.txtalamt3sm.value;
	
	//slctBandarsmIndex = form.slctBandarsm.selectedIndex;
	//slctBandarsm = form.slctBandarsm[slctBandarsmIndex].value;
	//slctBandarsm = form.slctBandarsm[slctBandarsmIndex].value;
	slctBandarsm = form.slctBandarsm.value;
	
	txtPoskodsm = form.txtPoskodsm.value;
	
	slctNegerismIndex = form.slctNegerism.selectedIndex;
	slctNegerism = form.slctNegerism[slctNegerismIndex].value;
	
	slctNegarasmIndex = form.slctNegarasm.selectedIndex;
	slctNegarasm = form.slctNegarasm[slctNegarasmIndex].value;
	
	txtNoTelt2 = form.txtNoTelt2.value;
	
}

function copyinfo(form) {
if (form.chkCopy.checked) {
	InitSaveVariables(form);
	form.txtalamt1sm.value = form.txtalamt1t.value;
	form.txtalamt2sm.value = form.txtalamt2t.value;
	form.txtalamt3sm.value = form.txtalamt3t.value;
	//form.slctBandarsm.selectedIndex = form.slctBandart.selectedIndex;
	form.slctBandarsm.value = form.slctBandart.value;
	
	form.txtPoskodsm.value = form.txtPoskodt.value;
	form.slctNegerism.selectedIndex = form.slctNegerit.selectedIndex;
	form.slctNegarasm.selectedIndex = form.slctNegarat.selectedIndex;
	form.txtNoTelt2.value = form.txtNoTelt.value;

}
else {
	form.txtalamt1sm.value = txtalamt1sm;
	form.txtalamt2sm.value = txtalamt2sm;
	form.txtalamt3sm.value = txtalamt3sm;
	//form.slctBandarsm.selectedIndex = slctBandarsmIndex;
	form.slctBandarsm.value = slctBandarsm;
	
	form.txtPoskodsm.value = txtPoskodsm;
	form.slctNegerism.selectedIndex = slctNegerismIndex;
	form.slctNegarasm.selectedIndex = slctNegarasmIndex;
	form.txtNoTelt2.value = txtNoTelt2;
	
   }
}
//  End -->