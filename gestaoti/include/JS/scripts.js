// --- Funções gerais
// 	funcoes a fazer de edições
//		datas
//		retirar zeros não validos (a esquerda)
//		retirar brancos do inicio
//		colocar zeros a esquerda
// --- fecha a janela do browser --- <input type="botton" name="Fechar" onclick="fechaJanela()">
function fValidaForm(){
   // Validar o formulário
    flag=0;
 	for(i=0; i<document.form.elements.length; i++){
 		if (document.form.elements(i).obrigatorio == "S"){
 			if(document.form.elements(i).value == ""){
 				alert("Preencha o campo "+document.form.elements(i).msg);
				document.form.elements(i).focus();
 				return false;
 			}
 		}
 	}
    return true;
}

function fDeletar(vCampo, vValor){
    if(confirm("Desejar apagar o registro?")){
		vCampo.value=vValor;
		document.form.flag.value=2;
		document.form.submit();
	}
}
function fDeletarPlus(vCampo, vValor, vAction){
    if(confirm("Desejar apagar o registro?")){
    	document.form.action = vAction;
		vCampo.value=vValor;
		document.form.flag.value=2;
		document.form.submit();
	}
}
function fAcaoMesmaPagina(vCampo, vValor, vAction, flag, msg){
    if(confirm(msg)){
    	document.form.action = vAction;
        vCampo.value=vValor;
        document.form.flag.value=flag;
        document.form.submit();
    }
}
function comparaDatas(campoInicial, campoFinal){
	inicial = campoInicial.value.split("/");
	final = campoFinal.value.split("/");
	dataInicial = new Date(inicial[1]+"/"+inicial[0]+"/"+inicial[2]);
	dataFinal = new Date(final[1]+"/"+final[0]+"/"+final[2]);
	intervalo = dataFinal - dataInicial;
	if (intervalo < 0){
		return false;
	} else {
		return true;
	}
}

// Recebe no formato d/m/Y H:i:s
function comparaDatasHora(campoInicial, campoFinal){
	//Date(ano,mês,dia,hora,minutos,segundos)
	dataInicial = new Date(parseInt(campoInicial.substr(6,4)), parseInt(campoInicial.substr(3,2)), parseInt(campoInicial.substr(0,2)), parseInt(campoInicial.substr(11,2)), parseInt(campoInicial.substr(14,2)), 0, 0);
	dataFinal = new Date(parseInt(campoFinal.substr(6,4)), parseInt(campoFinal.substr(3,2)), parseInt(campoFinal.substr(0,2)), parseInt(campoFinal.substr(11,2)), parseInt(campoFinal.substr(14,2)), 0, 0);
	intervalo = dataFinal - dataInicial;
	if (intervalo < 0){
		return false;
	} else {
		return true;
	}
}


function limpa_string(S){
	// Deixa so' os digitos no numero
	var Digitos = "0123456789";
	var temp = "";
	var digito = "";
    for (var i=0; i<S.value.length; i++){
      digito = S.value.charAt(i);
      if (Digitos.indexOf(digito)>=0){temp=temp+digito}
    }
    return temp;
}

function valida_horas(edit){
	if(event.keyCode<48 || event.keyCode>57){
        event.returnValue=false;
    }
    if(edit.value.length==2 /*|| edit.value.length==5*/){
    	edit.value+=":";
	}
}

function limpa_string_letras(S){
	// Deixa so' os digitos no numero
	var Digitos = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz ";
	var temp = "";
	var digito = "";
    for (var i=0; i<S.value.length; i++){
      digito = S.value.charAt(i);
      if (Digitos.indexOf(digito)>=0){temp=temp+digito}
    }
    return temp;
}

function limpa_string_decimal(S){
	// Deixa so' os digitos no numero
	var Digitos = "0123456789.-";
	var temp = "";
	var digito = "";
    for (var i=0; i<S.value.length; i++){
      digito = S.value.charAt(i);
	  if (digito==",")digito=".";
      if (Digitos.indexOf(digito)>=0){temp=temp+digito}
    }
    return temp;
}

function campo_numerico(edit){
	if(event.keyCode<48 || event.keyCode>57){
        event.returnValue=false;
    }
}

function browser() {
   nome   = navigator.appName
   versao = navigator.appVersion
}
function fechaJanela () {
   window.close();
}
function dataHoje() {
   var data       = new Date();
   var mesN       = data.getMonth() + 1;
   var diaNSemana = data.getDay() + 1;
   var diaN       = data.getDate();
   var anoN       = data.getYear();
   diaSemana = new Array('','Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado');
   mesAno    = new Array("","Janeiro","Fevereiro","Março","Abril","Maio","Junho",
                            "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
   var dataHoje =(" " + diaSemana[diaNSemana] + ", " + diaN + "/" + mesN + "/" + anoN );
   return dataHoje;
}
function horaHoje() {
   var hora       = new Date();
   var horaHora   = hora.getHours();
   var horaMinuto = hora.getMinutes();
   var horaHoje =("Hora : " + horaHora + ":" + horaMinuto );
   return horaHoje;
}
function mascaraDECampo(campo, mascara, event) {
   tamc = campo.value.length;
   tamm = mascara.length;
   if (tamc >= tamm)     return campo;
   var m=(tamm-1);
   var n=(tamc-1);
   var resp="";
   for ( i=n ; i >= 0 ; i-- )
   {
      caracter = campo.value.substr(i,1);
      masctipo = mascara.substr(m,1);
      if (masctipo == '9') { resp = caracter+resp; }
	  else
	  {
          if (masctipo == 'A') { resp = caracter+resp; }
		  else
		  {
		      resp = caracter+masctipo+resp;
			  m--;
		  }
	  }
	  m--;
   }
//      alert( 'resp: ' + resp );
   return campo.value = resp;
}
function mascaraEDCampo(campo, mascara, event) {
   tamc = campo.value.length;
   tamm = mascara.length;
   if (tamc >= tamm)     return campo;
   var m=0;
   var resp="";
   for ( i=0 ; i <= tamc ; i++)
   {
      caracter = campo.value.substr(i,1);
      masctipo = mascara.substr(m,1);
      if (masctipo == '9') { resp = resp+caracter; }
	  else
	  {
          if (masctipo == 'A') { resp = resp+caracter; }
		  else
		  {
		      resp = resp+masctipo+caracter;
			  m++;
		  }
	  }
	  m++;
   }
//      alert( 'resp: ' + resp );
   return campo.value = resp;
}
function mascaraVideoCampo(campo, mascara, event) {
//
//   esta com problema, porque a tecla lida nao corresponde ao caracter
//   exemplo = numeral 1 tem em dois lugares um funciona no outro nao
//				tem que mexer mais, outra o campo contera aquela informações
//				não ficando somente imaginario
//
   var naveTipo = (navigator.appName.indexOf("Netscape")!=-1);
   var tecla    = (naveTipo) ? event.which : event.keyCode;
// --- é aqui
   key = String.fromCharCode(tecla);
//
   tamc = campo.value.length;
   tamm = mascara.length;
   masctipo = mascara.substr(tamc,1);
   vlr  = campo.value;
//   alert( 'tecla: ' + tecla + ' valor: ' + campo.value + ' Key ' + key +
//          '  -> mascara: ' + masctipo + ' A ' + tamc + ' B ' + tamm);
   if (tamc >= tamm)     return true;
   if (masctipo == '9')  return true;
   if (masctipo == 'A')  return true;
   campo.value = campo.value+masctipo+key;
//   alert( 'pass: ' + campo.value);
   return true;
}
function autoTab(campo, tam, event) {
   var naveTipo = (navigator.appName.indexOf("Netscape")!=-1);
   var keyCode  = (naveTipo) ? event.which : event.keyCode;
   var filter   = (naveTipo) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
   if (campo.value.length >= tam && !contemDigito(filter,keyCode))
   {
      campo.value = campo.value.slice(0, tam);
      campo.form[(getPosicao(campo)+1) % campo.form.length].focus();
   }
}
function getPosicao(campo) {
   var ind = -1, i = 0, found = false;
   while (i < campo.form.length && ind == -1)
      if (campo.form[i] == campo)
	     ind = i;
      else
	     i++;
   return ind;
}
function contemDigito(filtro, ele) {
   var found = false, ind = 0;
   while(!found && ind < filtro.length)
      if(filtro[ind] == ele)
         found = true;
      else
         ind++;
   return found;
}
function verificaDigito(tipo, caractere )
{
   var strValidos = ""
   if (tipo == 'A') var strValidos = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz _";
   if (tipo == 'B') var strValidos = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz _áàãÁÀÃéÉíÍóÓõÕúÚüÜ";
   if (tipo == 'C') var strValidos = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz _0123456789";
   if (tipo == 'N') var strValidos = "0123456789,";
   if (tipo == 'N*') var strValidos = "0123456789";
   if ( strValidos.indexOf( caractere ) == -1 )
      return false;
   return true;
}
function validaDigito(tipo, campo, event)
{
   var BACKSPACE=  8;
   var key;
   var naveTipo = (navigator.appName.indexOf("Netscape")!=-1);
   var tecla    = (naveTipo) ? event.which : event.keyCode;
   key = String.fromCharCode( tecla);
//   alert( 'key: ' + tecla + ' letra: ' + key + '  -> campo: ' + campo.value);
   if ( tecla == 13 )         return false;
   if ( tecla == BACKSPACE )  return true;
   return ( verificaDigito(tipo, key));
}
function alinhaCampo(tam, alinha, campo, event)
{
   return true;
}
function FormataCNPJ( el )
{
   vlr  = el.value;
   tam  = vlr.length;
   if ( vlr.indexOf(".") == -1 )
   {
      if ( tam <= 2)                  el.value = vlr;
      if ((tam > 2)   && (tam <= 6) ) el.value = vlr.substr(0,2)+'.'+vlr.substr(2,tam);
      if ((tam >= 7)  && (tam <= 10)) el.value = vlr.substr(0,2)+'.'+vlr.substr(2,3)+'.'+vlr.substr(5,3)+'/';
      if ((tam >= 11) && (tam <= 18)) el.value = vlr.substr(0,2)+'.'+vlr.substr(2,3)+'.'+vlr.substr(5,3)+'/'
	                                           + vlr.substr(8,4)+'-'+vlr.substr(12,2) ;
   }
   return true;
}
function extenso(xvalor, xmoeda, xtami, xtamm) {
// ---
   valor = xvalor.value;
   moeda = xmoeda.value;
   tami  = xtami.value;
   tamm  = xtamm.value;
// --- sem separação silabica
   unidade       = new Array('','UM','DOIS','TRES','QUATRO','CINCO','SEIS','SETE','OITO','NOVE');
   unidadeesp    = new Array('','ONZE','DOZE','TREZE','QUATORZE',
                             'QUINZE','DEZESEIS','DEZESETE','DEZOITO','DEZENOVE');
   dezena        = new Array('','DEZ','VINTE','TRINTA','QUARENTA',
                             'CINQUENTA','SESSENTA','SETENTA','OITENTA','NOVENTA');
   centena       = new Array('','CENTO','DUZENTOS','TREZENTOS','QUATROCENTOS',
		                     'QUINHENTOS','SEISCENTOS','SETECENTOS','OITOCENTOS','NOVECENTOS');
// --- moedas
   moeda         = new Array('REAL','REAIS','CENTAVO','CENTAVOS');
// --- com separação silabica
   divunidade    = new Array('','3UM','4DOIS','4TRES','3QUA3TRO','3CIN2CO','4SEIS','2SE2TE','2OI2TO','2NO2VE');
   divunidadeesp = new Array('','2ON2ZE','2DO2ZE','3TRE2ZE','3QUA3TOR2ZE',
                             '4QUIN2ZE','2DE2ZE4SEIS','2DE2ZE2SE2TE','2DE3ZOI2TO','2DE2ZE2NO2VE');
   divdezena     = new Array('','3DEZ','3VIN2TE','4TRIN2TA','3QUA3REN2TA',
                             '3CIN4QUEN2TA','3SES3SEN2TA','2SE3TEN2TA','2OI3TEN2TA','2NO3VEN2TA');
   divcentena    = new Array('','3CEN2TO','2DU3ZEN3TOS','3TRE3ZEN3TOS','3QUA3TRO3CEN3TOS',
		                     '3QUI4NHEN3TOS','4SEIS3CEN3TOS','2SE2TE3CEN3TOS','2OI2TO3CEN3TOS','2NO2VE3CEN3TOS');
// --- medidas de unidades
   medidaunidade = new Array ('TRILHAO','BILHAO','MILHAO','MIL','');
   medidasunidade= new Array ('TRILHOES','BILHOES','MILHOES','MIL','');
// --- juncoes
   juncaoe = 'E ';
// ---  prepara valor (0bs. o valor tem que vir com as duas casas decimais)
   vlrfixo = strzeroVlr(valor, 18);
// ---
   vlrextenso = "";
   for (i=0; i<=4; i++)
   {
      ind        = (i * 3);
      vlrunid    = vlrfixo.substr(ind, 3);
	  if (i < 4) vlrpunid    = vlrfixo.substr((ind+3), 3);
	  if (vlrunid > 0)
	  {
	     vlrcentena = vlrunid.substr(0,1);
         vlrdezena  = vlrunid.substr(1,1);
	     vlrunidade = vlrunid.substr(2,1);
	     medunid    = medidaunidade[i];
	     medsunid   = medidasunidade[i];
	  	 if (vlrcentena > 0)  vlrextenso = vlrextenso+centena[vlrcentena]+' ';
	  	 if (vlrcentena > 0 && ((vlrdezena > 0)||(vlrunid > 0)))  vlrextenso = vlrextenso+juncaoe;
		 if (vlrdezena > 1)   vlrextenso = vlrextenso+dezena[vlrdezena]+' ';
		 if (vlrdezena == 1)
		 {
		    if (vlrunid == 0) vlrextenso = vlrextenso+dezena[vlrdezena]+' ';
			if (vlrunid > 0)  vlrextenso = vlrextenso+unidadeesp[vlrunidade]+' ';
		 }
		 else
		 {
	  	    if (vlrdezena > 0 && (vlrunid > 0))  vlrextenso = vlrextenso+juncaoe;
		    if (vlrunidade > 0)  vlrextenso = vlrextenso+unidade[vlrunidade]+' ';
		 }
		 if (vlrunid > 1)     vlrextenso = vlrextenso+medsunid+' ';
		 if (vlrunid == 1)    vlrextenso = vlrextenso+medunid+' ';
      }
	  if (i < 4 && vlrpunid > 0) vlrextenso = vlrextenso+juncaoe;
   }
   if (vlrfixo > 1)      vlrextenso = vlrextenso+moeda[1]+' ';
   if (!(vlrfixo > 1))   vlrextenso = vlrextenso+moeda[0]+' ';
   vlrunid    = vlrfixo.substr(16, 2);
   if (vlrunid > 0)
   {
      vlrextenso = vlrextenso+juncaoe;
      vlrdezena  = vlrunid.substr(0,1);
      vlrunidade = vlrunid.substr(1,1);
      if (vlrdezena > 1)   vlrextenso = vlrextenso+dezena[vlrdezena]+' ';
      if (vlrdezena == 1)
      {
          if (vlrunid == 0) vlrextenso = vlrextenso+dezena[vlrdezena]+' ';
	      if (vlrunid > 0)  vlrextenso = vlrextenso+unidadeesp[vlrunidade]+' ';
      }
      else
      {
	  	 if (vlrdezena > 0 && (vlrunid > 0))  vlrextenso = vlrextenso+juncaoe;
	     if (vlrunidade > 0)  vlrextenso = vlrextenso+unidade[vlrunidade]+' ';
      }
      if (vlrunid.substr(1,2) > 1)      vlrextenso = vlrextenso+moeda[3]+' ';
      if (!(vlrunid.substr(1,2) > 1))   vlrextenso = vlrextenso+moeda[2]+' ';
   }
   return vlrextenso;
// ---
//   document.write('Valor: ' + vlrfixo +'<br>'+ ' Extenso: ' + vlrextenso);
// ---
}
function strzeroVlr(Val, Qtd)
{
   Zeros      = "000000000000000000000000000000";
   Valx       = Val.value;
   QtdDigitos = Valx.length;
   QtdZeros   = Qtd - QtdDigitos;
   xis = Val;
   if (QtdZeros > 0) xis = Zeros.substr(0, QtdZeros)+Val;
   return xis;
}
 function ValidaData(form){
	var flag=true;
	//alert('oi');
	if (form.value=="")
	{
		//alert("Preencha a "+form.msg+".");
		//form.value="";
		//form.focus();
		flag=true;
		return true;
    }
	else
	{
		if (form.value.length>10)
  	    {
	       alert("A data está preenchida incorretamente.Preencha-a novamente.");
		   form.value="";
		   form.focus();
		   flag=false;
	    }

		else if (form.value.substring(3,5)>"12"){
			alert("Preencha o mês da data corretamente.");
			form.value="";
			form.focus();
			flag=false;
		}
		else
		{
			if(form.value.substring(3,5)=="02")
			{
				if(form.value.substring(0,2)>"29")
				{
					alert("Valor do dia inválido para o mês informado.");
					form.value="";
					form.focus();
					flag=false;
				}
				else
				{
					if(form.value.length < "10")
					{
				       alert("Preencha a data no formato dd/mm/aaaa.");
					   form.value="";
					   form.focus();
					   flag=false;
					}
				}
			}
			else
			{
				if(form.value.substring(3,5)=="04" || form.value.substring(3,5)=="06" || form.value.substring(3,5)=="09" || form.value.substring(3,5)=="11")
				{
					if(form.value.substring(0,2)>"30")
					{
						alert("Valor do dia inválido para o mês informado.");
						form.value="";
						form.focus();
						flag=false;
					}
					else
					{
						if(form.value.length < "10")
						{
						   alert("Preencha a data no formato dd/mm/aaaa.");
						   form.value="";
						   form.focus();
						   flag=false;
						}
					}
				}
				else
				{
						if(form.value.substring(3,5)=="01" || form.value.substring(3,5)=="03" || form.value.substring(3,5)=="05" || form.value.substring(3,5)=="07" || form.value.substring(3,5)=="08" || form.value.substring(3,5)=="10" || form.value.substring(3,5)=="11")
						{
							if(form.value.substring(0,2)>"31")
							{
								alert("Valor do dia inválido para o mês informado.");
								form.value="";
								form.focus();
								flag=false;
							}
							else
							{
							    if(form.value.length < "10")
								{
									alert("Preencha a data no formato dd/mm/aaaa.");
									form.value="";
									form.focus();
									flag=false;
								}
							}
						}
				 }
			}
		}
	}
	return flag;
}

function VerificaData(digData)
{

    var bissexto = 0;
    var data = digData;
    var tam = data.length;
    if (tam == 10)
    {
        var dia = data.substr(0,2)
        var mes = data.substr(3,2)
        var ano = data.substr(6,4)

        if ((ano > 1900)||(ano < 2100))
        {
            switch (mes)
            {
                case '01':
                case '03':
                case '05':
                case '07':
                case '08':
                case '10':
                case '12':
                    if  (dia <= 31)
                    {
                        return true;
                    }
                    break

                case '04':
                case '06':
                case '09':
                case '11':
                    if  (dia <= 30)
                    {
                        return true;
                    }
                    break
                case '02':
                    /* Validando ano Bissexto / fevereiro / dia */
                    if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0))
                    {
                        bissexto = 1;
                    }
                    if ((bissexto == 1) && (dia <= 29))
                    {
                        return true;
                    }
                    if ((bissexto != 1) && (dia <= 28))
                    {
                        return true;
                    }
                    break
            }
        }
    }
	if (data!=''){
	    alert("A Data "+data+" é inválida!");
	}
    return false;
}


function ValidaHora(form){
	var flag=true;
	if (form.value=="")
	{
		//alert("Preencha a "+form.msg+".");
		//form.value="";
		//form.focus();
		//flag=false;
    } else {
		if (form.value.length>5){
	       alert("A hora está preenchida incorretamente. Preencha-a novamente.");
		   form.value="";
		   form.focus();
		   flag=false;
	    } else if (form.value.substring(0,2)>"23" || form.value.substring(3,5)>"59" || form.value.substring(2,3) != ":"){
			alert("Preencha a hora corretamente.");
			form.value="";
			form.focus();
			flag=false;
		}
	}
	return flag;
}
function ContaCaracteres(intMaxCaracteres, obj1, win){
  	intCaracteres = intMaxCaracteres - obj1.value.length;
  	if (intCaracteres > 0){
  		win.innerHTML = intCaracteres;
    	return true;
  	}
    else {
    	intLegenda = intMaxCaracteres;
    	win.innerHTML = 0;
    	obj1.value = obj1.value.substr(0,intLegenda);
    	return false;
	}
}
// Usar com autotab se necessário
function preenche(obj){
	  if (navigator.appName != "Netscape"){
			if (obj.value.length==2){
				obj.value = obj.value + "/";
			} else{
				if (obj.value.length==5){
					obj.value = obj.value + "/";
				}
			}
	  }
}

function preencheVelho(obj, objredirect){
	  if (navigator.appName != "Netscape"){
			if (obj.value.length==2){
				obj.value = obj.value + "/";
			} else{
				if (obj.value.length==5){
					obj.value = obj.value + "/";
				}
			}
	  }

	  if (obj.value.length==10){
 	    objredirect.focus();
	  }
}

function preencheHora(obj){
	  if (navigator.appName != "Netscape"){
			if (obj.value.length==2){
				obj.value = obj.value + ":";
			}
	  }
}

function fPassa(objOrigem, objDestino){
	for (i=0; i < objOrigem.options.length; i++) {
		if (objOrigem.options[i].selected == true){
			var no = new Option();
			no.value = objOrigem.options[i].value;
			no.text = objOrigem.options[i].text;
			objDestino.options[objDestino.options.length] = no;
			objOrigem.options.remove(i);
			i--;
		}
	 }
}

function fSelecionaTodos(checkEscolha, combo){
	if(checkEscolha.checked == true){
		for (i=0; i < combo.options.length; i++) {
			combo.options[i].selected = true;
		}
	} else{
		for (i=0; i < combo.options.length; i++) {
			combo.options[i].selected = false;
		}
	}
}

function VerificarExistenciaValorCombo(combo, valor){
	for (i=0; i < combo.options.length; i++) {
		if(combo.options[i].value == valor){
			return true;
		}
	}
	return false;
}

function fAdicionaValorCombo(value, text, Obj){
	var no = new Option();
	no.value = value;
	no.text = url_decode(text);
	Obj.options[Obj.options.length] = no;
}

function fExcluirValorCombo(combo){
	//combo = document.getElementById(combo.name);
	for (i=0; i < combo.options.length; i++) {
		if(combo.options[i].selected == true){
			//combo.options.remove(i);
			combo.remove(i);
			i--;
		}
	}
}

function GetTextItemCombo(combo){
	for (i=0; i < combo.options.length; i++) {
		if(combo.options[i].selected == true){
			return combo.options[i].text;
		}
	}
}

function fEncheComboBox(val, Obj){
	while(Obj.length > 0) {
		Obj.removeChild(Obj.childNodes[0]);
	}

	for (i=0; i < Obj.options.length; i++) {
		Obj.options.remove(i);
		i--;
	}
	var no = new Option();
	no.value = "";
	no.text = "Escolha";
	Obj.options[Obj.options.length] = no;
	for (var i in val){
		repr = val[i];
		codigo = repr.substr(0, repr.indexOf("|"));
		nome = repr.substr(repr.indexOf("|")+1, repr.length);
		var no = new Option();
		no.value = codigo;
		no.text = url_decode(nome);
		Obj.options[Obj.options.length] = no;
	}
}
function fEncheComboBoxPlus(val, Obj, Valor1){
	while(Obj.length > 0) {
		Obj.removeChild(Obj.childNodes[0]);
	}

	for (i=0; i < Obj.options.length; i++) {
		Obj.options.remove(i);
		i--;
	}
	var no = new Option();
	no.value = "";
	no.text = Valor1;
	Obj.options[Obj.options.length] = no;
	for (var i in val){
		repr = val[i];
		codigo = repr.substr(0, repr.indexOf("|"));
		nome = repr.substr(repr.indexOf("|")+1, repr.length);
		var no = new Option();
		no.value = codigo;
		no.text = url_decode(nome);
		Obj.options[Obj.options.length] = no;
	}
}

// url_encode version 1.0
    function url_encode(str) {
        var hex_chars = "0123456789ABCDEF";
        var noEncode = /^([a-zA-Z0-9\_\-\.])$/;
        var n, strCode, hex1, hex2, strEncode = "";

        for(n = 0; n < str.length; n++) {
            if (noEncode.test(str.charAt(n))) {
                strEncode += str.charAt(n);
            } else {
                strCode = str.charCodeAt(n);
                hex1 = hex_chars.charAt(Math.floor(strCode / 16));
                hex2 = hex_chars.charAt(strCode % 16);
                strEncode += "%" + (hex1 + hex2);
            }
        }
        return strEncode;
    }

    // url_decode version 1.0
    function url_decode(str) {
        var n, strCode, strDecode = "";

        for (n = 0; n < str.length; n++) {
            if (str.charAt(n) == "%") {
                strCode = str.charAt(n + 1) + str.charAt(n + 2);
                strDecode += String.fromCharCode(parseInt(strCode, 16));
                n += 2;
            } else {
                strDecode += str.charAt(n);
            }
        }

        return strDecode;
    }

function valida_CPF(s){
	var i, flag=0;
	var c = s.substr(0,9);
	var dv = s.substr(9,2);
	var d1 = 0;
	for (i = 0; i < 8; i++){
		if (c.charAt(i) != c.charAt(i+1)) flag++;
	}

	if (flag == 0) return false;
	for (i = 0; i < 9; i++){
		d1 += c.charAt(i)*(10-i);
	}
        if (d1 == 0) return false;
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1)
	{
		return false;
	}

	d1 *= 2;
	for (i = 0; i < 9; i++)
	{
		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1)
	{
		return false;
	}
        return true;
}

function valida_CNPJ(s)
{
	var i;
	s = limpa_string(s);
	var c = s.substr(0,12);
	var dv = s.substr(12,2);
	var d1 = 0;
	for (i = 0; i < 12; i++)
	{
		d1 += c.charAt(11-i)*(2+(i % 8));
	}
        if (d1 == 0) return false;
        d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1)
	{
		return false;
	}

	d1 *= 2;
	for (i = 0; i < 12; i++)
	{
		d1 += c.charAt(11-i)*(2+((i+1) % 8));
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1)
	{
		return false;
	}
        return true;
}

function fRetornaValoresComboMultiple(obj){
	    vRetorno = "";
	 	for(i=0; i<obj.options.length; i++){
	 		if (obj.options[i].selected == true){
	 			vRetorno = vRetorno + obj.options[i].value + ",";
	 		}
	 	}
		return vRetorno.substr(0,vRetorno.length - 1);
}

function format_number(pnumber,decimals){
	if (isNaN(pnumber)) { return 0};
	if (pnumber=='') { return 0};

	var snum = new String(pnumber);
	var sec = snum.split('.');
	var whole = parseFloat(sec[0]);
	var result = '';

	if(sec.length > 1){
		var dec = new String(sec[1]);
		dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
		dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
		var dot = dec.indexOf('.');
		if(dot == -1){
			dec += '.';
			dot = dec.indexOf('.');
		}
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	} else{
		var dot;
		var dec = new String(whole);
		dec += '.';
		dot = dec.indexOf('.');
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	}
	return result;
}

/***
* Descrição.: formata um campo do formulário de
* acordo com a máscara informada...
* Parâmetros: - objForm (o Objeto Form)
* - strField (string contendo o nome
* do textbox)
* - sMask (mascara que define o
* formato que o dado será apresentado,
* usando o algarismo "9" para
* definir números e o símbolo "!" para
* qualquer caracter...
* - evtKeyPress (evento)
* Uso.......: <input type="textbox"
* name="xxx".....
* onkeypress="return txtBoxFormat(document.rcfDownload, 'str_cep', '99999-999', event);">
* Observação: As máscaras podem ser representadas como os exemplos abaixo:
* CEP -> 99.999-999
* CPF -> 999.999.999-99
* CNPJ -> 99.999.999/9999-99
* Data -> 99/99/9999
* Tel Resid -> (99) 999-9999
* Tel Cel -> (99) 9999-9999
* Processo -> 99.999999999/999-99
* C/C -> 999999-!
* E por aí vai...
***/
function txtBoxFormat(objForm, strField, sMask, evtKeyPress) {
	var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;

	if(document.all) { // Internet Explorer
		nTecla = evtKeyPress.keyCode; }
	else if(document.layers) { // Nestcape
		nTecla = evtKeyPress.which;
	}

	sValue = objForm[strField].value;

	// Limpa todos os caracteres de formatação que
	// já estiverem no campo.
	sValue = sValue.toString().replace( "-", "" );
	sValue = sValue.toString().replace( "-", "" );
	sValue = sValue.toString().replace( ".", "" );
	sValue = sValue.toString().replace( ".", "" );
	sValue = sValue.toString().replace( "/", "" );
	sValue = sValue.toString().replace( "/", "" );
	sValue = sValue.toString().replace( "(", "" );
	sValue = sValue.toString().replace( "(", "" );
	sValue = sValue.toString().replace( ")", "" );
	sValue = sValue.toString().replace( ")", "" );
	sValue = sValue.toString().replace( " ", "" );
	sValue = sValue.toString().replace( " ", "" );
	fldLen = sValue.length;
	mskLen = sMask.length;

	i = 0;
	nCount = 0;
	sCod = "";
	mskLen = fldLen;

	while (i <= mskLen) {
		bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/"))
		bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))

		if (bolMask) {
			sCod += sMask.charAt(i);
			mskLen++; }
		else {
			sCod += sValue.charAt(nCount);
			nCount++;
		}
		i++;
	}

	objForm[strField].value = sCod;

	if (nTecla != 8) { // backspace
		if (sMask.charAt(i-1) == "9") { // apenas números...
			return ((nTecla > 47) && (nTecla < 58)); } // números de 0 a 9
		else { // qualquer caracter...
			return true;
		}
	}else {
		return true;
	}
}

function FormataData(objeto,teclapress)
{
    var tecla = teclapress.keyCode;

    //Testa se o campo está selecionado, se estiver, limpa o conteúdo para nova digitação.
    var selecionado = document.selection.createRange();
    var selecao        = selecionado.text;
    if((selecao != "") && (tecla != 8) && (tecla != 9) && (tecla != 13) && (tecla != 35) && (tecla != 36) && (tecla != 46) && (tecla != 16) && (tecla != 17) && (tecla != 18) && (tecla != 20) && (tecla != 27) && (tecla != 37) && (tecla != 38) && (tecla != 39) && (tecla != 40)) {objeto.value = "";}



    if(((window.event.keyCode == 13) || (window.event.keyCode == 9))&&objeto.value != "")
    {
        if(!(ValidaData(objeto)))
            {
                window.event.cancelBubble = true;
                window.event.returnValue = false;
                alert("Data Inválida");
                objeto.value = "";
                objeto.focus();
            }
    }

    if (( tecla == 8 || tecla == 88 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )&& objeto.value.length < (10))
    {
        vr = objeto.value;
        vr = vr.replace( "/", "" );
        vr = vr.replace( "/", "" );
        tam = vr.length;

        if (tam < 8)
            {
                if (tecla != 8) {tam = vr.length + 1 ;}
            }
        else
            {
                window.event.cancelBubble = true;
                window.event.returnValue = false;
            }

        if ((tecla == 8) && (tam > 1))
            {
                tam = tam - 1 ;
                objeto.value = vr.substr(0,tam);
                window.event.cancelBubble = true;
                window.event.returnValue = false;
            }
                if ( tam <= 4 && tecla != 8){
                     objeto.value = vr ; }

                if ( (tam >= 4) && (tam <= 6) ){
                     objeto.value = vr.substr(0, tam - 4) + '/' + vr.substr( tam - 4, 4 ); }

                if ( (tam >= 6) && (tam <= 8) ){
                    objeto.value = vr.substr(0, tam - 6 ) + '/' + vr.substr( tam - 6, 2 ) + '/' + vr.substr( tam - 4, 4 ); }

                if ((tam == (8)) && tecla != 8)
                    {
                        if(tecla >=96 && tecla <=105)
                            {
                                tecla = tecla - 48;
                            }

                        objeto.value = objeto.value + (String.fromCharCode(tecla));
                        window.event.cancelBubble = true;
                        window.event.returnValue = false;

                        if (!(ValidaData(objeto)))
                            {
                                alert("Data Inválida");
                                objeto.value = "";
                                objeto.focus();
                            }
                    }
    }
    else if((window.event.keyCode != 8) && (window.event.keyCode != 9) && (window.event.keyCode != 13) && (window.event.keyCode != 35) && (window.event.keyCode != 36) && (window.event.keyCode != 46))
        {
            event.returnValue = false;
        }
}

// Controle de tabelas dinâmicas
function InserirItemArray(vArray, vValor){
	flag = true;
	for (x in vArray){
		if(vArray[x][0] ==  vValor){
			flag = false;
		}
	}
	if(flag == true){
		vArray[vArray.length] = new Array(vValor,"");
	}
	return flag;
}

function setRowIndex(vArray, vValor, vRowIndex){
	for (x in vArray){
		if(vArray[x][0] ==  vValor){
			vArray[x][1] = vRowIndex;
		}
	}
}

function ExcluirItemArray(vArray, vRowIndex){
	vFlagArray = new Array();
	for (x in vArray){
		if(vArray[x][1] !=  vRowIndex){
			vFlagArray[vFlagArray.length] = vArray[x];
		}
		if(vArray[x][1] > vRowIndex){
			vArray[x][1] = vArray[x][1] - 1;
		}
	}
	return vFlagArray;
}

function SoNumeros()
{
 var carCode = event.keyCode;
 if ((carCode < 48) || (carCode > 57))
 {
  alert('Por favor digite apenas números.');
  event.cancelBubble = true
  event.returnValue = false;
 }
}

/*-------------------------------------------------------------------------------------
Máscara para o campo data dd/mm/aaaa hh:mm:ss
Exemplo: <input maxlength="16" name="datahora" onKeyPress="DataHora(event, this)">
--------------------------------------------------------------------------------------*/
function DataHora(evento, objeto){
	var keypress=(window.event)?event.keyCode:evento.which;
	campo = eval (objeto);
	if (campo.value == '00/00/0000 00:00:00')
	{
		campo.value=""
	}

	caracteres = '0123456789';
	separacao1 = '/';
	separacao2 = ' ';
	separacao3 = ':';
	conjunto1 = 2;
	conjunto2 = 5;
	conjunto3 = 10;
	conjunto4 = 13;
	conjunto5 = 16;
	if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < (19))
	{
		if (campo.value.length == conjunto1 )
		campo.value = campo.value + separacao1;
		else if (campo.value.length == conjunto2)
		campo.value = campo.value + separacao1;
		else if (campo.value.length == conjunto3)
		campo.value = campo.value + separacao2;
		else if (campo.value.length == conjunto4)
		campo.value = campo.value + separacao3;
		else if (campo.value.length == conjunto5)
		campo.value = campo.value + separacao3;
	}
	else
		event.returnValue = false;
}

function verifica_hora(valor){

		hors = (valor.substring(0,2));
		min = (valor.substring(3,5));
		sec = (valor.substring(8,5));

		situacao = "";
		// verifica data e hora
		if ((hors < 00 ) || (hors > 23) || ( minu < 00) ||( minu > 59) ){
		  situacao = "falsa";
		}

		if ( sec < 00 || sec > 59) {
			//alert('oi');
		}

		if (situacao == "falsa") {
		  alert("Hora inválida!");
		  return false;
		}
}

function FormatarArrayInsercao(vArray, ObjForm){
	for (x in vArray){
		ObjForm.value = ObjForm.value + vArray[x][0];
		if(x < vArray.length - 1){
			ObjForm.value = ObjForm.value + ";";
		}
	}
}

function FormataValor(id,tammax,teclapres) {

	if(window.event) { // Internet Explorer
		var tecla = teclapres.keyCode; }
	else if(teclapres.which) { // Nestcape / firefox
		var tecla = teclapres.which;
	}

	vr = document.getElementById(id).value;
	vr = vr.toString().replace( "/", "" );
	vr = vr.toString().replace( "/", "" );
	vr = vr.toString().replace( ",", "" );
	vr = vr.toString().replace( ".", "" );
	vr = vr.toString().replace( ".", "" );
	vr = vr.toString().replace( ".", "" );
	vr = vr.toString().replace( ".", "" );
	tam = vr.length;
	//alert(tecla);
	if((tecla > 47 && tecla < 58) || (tecla > 95 && tecla < 106)){
		if (tam < tammax && tecla != 8){ tam = vr.length + 1; }

		if (tecla == 8 ){ tam = tam - 1; }

		if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
			if ( tam <= 2 ){
				document.getElementById(id).value = vr;
			}
			if ( (tam > 2) && (tam <= 5) ){
				document.getElementById(id).value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam );
			}
			if ( (tam >= 6) && (tam <= 8) ){
				document.getElementById(id).value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam );
			}
			if ( (tam >= 9) && (tam <= 11) ){
				document.getElementById(id).value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam );
			}
			if ( (tam >= 12) && (tam <= 14) ){
				document.getElementById(id).value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam );
			}
			if ( (tam >= 15) && (tam <= 17) ){
				document.getElementById(id).value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam );
			}
		}
	}else{
		document.getElementById(id).value = vr.substr( 0, vr.length - 1);
	}
}

function verificarDataMenorQueHoraAtual(data, hora) {

	var dt = new Date();

	var diaAtual = dt.getDate();
	var mesAtual = dt.getMonth()+1;
	var anoAtual = dt.getFullYear();
	var horaAtual   = dt.getHours();
	var minutoAtual = dt.getMinutes();


	if(diaAtual > 0 && diaAtual < 10) diaAtual = "0" + diaAtual;
	if(mesAtual > 0 && mesAtual < 10) mesAtual = "0" + mesAtual;
	
	var dataAtual = diaAtual + "/" + mesAtual + "/" + anoAtual;
	var vDia = data.substr(0,2);
	var vMes = data.substr(3,2);
	var vAno = data.substr(6,5);
	var vHora = hora.substr(0,2);
	var vMinuto = hora.substr(3,2);
	 
	if( anoAtual > vAno ) {
		alert("A data informada deve ser maior que a data atual!");
		return false;
	}else if( mesAtual > vMes ) {
		alert("A data informada deve ser maior que a data atual!");
		return false;
	}else if(diaAtual > vDia ) {
		alert("A data informada deve ser maior que a data atual!");
		return false;
	}else if(diaAtual == vDia ) {
		if(horaAtual == vHora ) {
			if(minutoAtual > vMinuto ) {
				alert("A data informada deve ser maior que a data atual!");
				return false;
			}else if(minutoAtual == vMinuto ) {
				alert("A data informada deve ser maior que a data atual!");
				return false;
			}	
		}else if(horaAtual > vHora ) {
			alert("A data informada deve ser maior que a data atual!");
			return false;
		}
	}else if(diaAtual < vDia ) {
		return true;
			
	}else if(horaAtual > vHora ) {
		alert("A data informada deve ser maior que a data atual!");
		return false;
	}else if(horaAtual == vHora ) {
		if(minutoAtual > vMinuto ) {
			alert("A data informada deve ser maior que a data atual!");
			return false;
		}
	} 
	 
	return true;


}

function fExportGrid(url){
		//alert(url);
		//window.open(url,'_blank','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=100,height=100');
		document.form.target = '_blank';
		document.form.method = 'POST';
	  	document.form.action  = url; 
		document.form.submit();
		
		document.form.action="";;
		document.form.method="get" ;
		document.form.target="_self";
		 
	 
		
		 
	   
	   return true;
	}
