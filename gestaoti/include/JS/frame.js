// JavaScript Document

function iframeAutoHeight(quem){
	var val= 780;         
    if(navigator.appName.indexOf(" Internet Explorer")>-1){ 
		try {
			val = quem.contentWindow.document.body.scrollHeight;            	
		} catch(err) {
			val = 780;
		}
    }else{
        try{
           val = quem.contentWindow.document.body.parentNode.offsetHeight + 5;
        } catch(err) {
           val = 780;
        }
    }
    quem.style.height= val;
}
