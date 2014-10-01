function carisatker()
{
	//var csatmikal  = document.frmsatker.csatmikal.value;c_satminkal
	var csatmikal  = document.frmsatker.csatminkal.value;
	var cprop  = document.frmsatker.cprop.value;
	var danggaran  = document.frmsatker.d_anggaran.value;
	var nama  = document.frmsatker.nama.value;
	handler = "/aplikasi/popup/listdatasatker";
	var opt = {csatmikal:csatmikal,cprop:cprop,danggaran:danggaran,nama:nama};
	jQuery.get(handler,opt,function(data) {
			$("#tableview").html(data);

		 });
}