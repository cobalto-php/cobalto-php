/**
 * @fileoverview Este arquivo contém os metódos relacionados a utilização de janelas
 */

/**
 * @function
 * @description Método utilizado para abrir janelas modais, sem a possibilidade de manipular a tela abaixo
 * @example
 * openWindow('url_controller', 'titulo da janela', width);
 * @name openWindow
 * @author rsantos
 * @param string url URL do controle reponsável por exibir a tela
 * @param string title Título da janela a ser exibida
 * @param integer width Largura da tela a ser exibida
 * @param boolean iframe Caso informado TRUE a tela terá uma altura fixa de 400px e será exibido a tela dentro de um iframe, caso contrário não terá estes atributos
 */
function openWindow(url, title, width, iframe){
    var idWindow = generateNameWindowByUrl(url);
    if(iframe == undefined){
        $("#"+idWindow+" iframe").remove();
        $("#"+idWindow).dialog("destroy");
        $("#"+idWindow).remove();
        $('<div id="'+idWindow+'" style="display:hidden; padding: 0px; overflow: hidden !important"><iframe id="modalIframeId" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title="Dialog Title">Your browser does not suppr</iframe></div>').appendTo('body');
        $("#modalIframeId").attr("src", url);

        $('#carregando').fadeIn('normal');
        $("#"+idWindow).dialog({
            bgiframe: true,
            modal: true,
            title:title,
            height: 400,
            width: width,
            position: [50, 50],
            open: function(){
                $('#carregando').fadeOut('normal');
                $.cookie('lastWindow', idWindow, {
                    path: PATH_COOKIE
                });
                try{
                    initPopUp();
                }catch(err){}
            },
            close: function(){
                $("#"+idWindow+" iframe").remove();
                $("#"+idWindow).remove();
                $.cookie('lastWindow', null, {
                    path: PATH_COOKIE
                });
                try{
                    closePopUp();
                }catch(err){}
            }
        });
    }else{
        $("#"+idWindow).dialog("destroy");
        $("#"+idWindow).remove();
        var windowModal = $('<div id="'+idWindow+'" style="display:hidden"></div>').appendTo('body');

        windowModal.load(url, {},
            function (responseText, textStatus, XMLHttpRequest){
                windowModal.dialog({
                    bgiframe: true,
                    modal:true,
                    title:title,
                    minHeight:5,
                    width:width,
                    position: [50, 50],
                    open: function(){
                        $('#carregando').fadeOut('normal');
                        loadComponents();
                        generateTabs();
                        try{
                            initPopUp();
                        }catch(err){}
                        $.cookie('lastWindow', idWindow, {
                            path: PATH_COOKIE
                        });
                    },
                    close: function(){
                        $("#"+idWindow).remove();
                        $.cookie('lastWindow', null, {
                            path: PATH_COOKIE
                        });
                        try{
                            closePopUp();
                        }catch(err){}
                    }
                });
            }
            );
    }
}

/**
 * @function
 * @description Não sei direito porque existe este método, na verdade achei a chamada no arquivo footerGlobalView.php, quando a tela foi chamada por um buttonHit, tenho que descobrir como utilizar isso.
 * @name setHeightWindow
 * @private
 */
function setHeightWindow(objectId, height){
    $('#'+objectId).height(height + 10);
}

/**
 * @function
 * @description Método utilizado para fechar ultima janela aberta
 * @name closeWindowSelf
 * @author rsantos
 */
function closeWindowSelf(){
    $('#'+$.cookie('lastWindow')).dialog('close');
}

/**
 * @function
 * @description Método utilizado internamente para gerar id HTML válido
 * @name generateNameWindowByUrl
 * @author rsantos
 * @param string url URL que tera seu valor alterado para se tornar um ID html válido
 * @return string
 * @private
 * @see openWindow
 */
function generateNameWindowByUrl(url){
    return url.split('?')[0].replace(/\:/g, '_').replace(/\./g, '_').replace(/\//g, '_').replace(/\|/g, '_').replace(/\=/g, '_').replace(/%/g, '_');
}

/**
 * @function
 * @description Exibe janela com mensagens de avisos
 * @example
 * messageBox('mensagem');
 * @example
 * messageBox('mensagem', 'txtNome');
 * @example
 * messageBox('mensagem', 'txtNome', 'metodoCallBack');
 * @example
 * messageBox('mensagem', 'metodoCallBack');
 * @name messageBox
 * @author rsantos
 * @param string message Mensagem que deve ser exibida
 * @param string field Componente a ser selecionado após o usuário clicar em OK
 * @param string functionCallBack Método a ser executado após o usuário clicar em OK
 */
function messageBox(message, field, functionCallBack){
    $("#dialog-message-info p label").html(message.replace(/\n/g, "<br/>"));
    $("#dialog-message-info").dialog({
        modal: true,
        minHeight: 50,
        title: 'Atenção',
        open: function(){
            $('button').blur();
            $('.ui-dialog .ui-dialog-buttonpane button').focus();
        },
        buttons: {
            Ok: function() {
                callBackDialog(field, functionCallBack);
                $(this).dialog('close');
            }
        }
    });
}

/**
 * @function
 * @description Exibe janela com mensagens de erros
 * @example
 * messageBox('mensagem');
 * @example
 * messageBox('mensagem', 'txtNome');
 * @example
 * messageBox('mensagem', 'txtNome', metodoCallBack);
 * @example
 * messageBox('mensagem', metodoCallBack);
 * @name messageErrorBox
 * @author rsantos
 * @param string message Mensagem que deve ser exibida
 * @param string field Componente a ser selecionado após o usuário clicar em OK
 * @param object functionCallBack Método a ser executado após o usuário clicar em OK
 */
function messageErrorBox(message, field, functionCallBack){
    $("#dialog-message-error p label").html(message.replace(/\n/g, "<br/>"));
    $("#dialog-message-error").dialog({
        modal: true,
        minHeight: 50,
        title: 'Atenção',
        open: function(){
            $('button').blur();
            $('.ui-dialog .ui-dialog-buttonpane button').focus();
        },
        buttons: {
            Ok: function() {
                callBackDialog(field, functionCallBack);
                $(this).dialog('close');
            }
        }
    });
}

/**
 * @function
 * @description Exibe mensagem com a opção para o usuário confirmar
 * @example
 * messageConfirm('mensagem', metodoResultadoConfirmacao);
 * @name messageConfirm
 * @author rsantos
 * @param string message Mensagem que deve ser exibida
 * @param object functionCallBack Método a ser executado após o usuário clicar em Sim ou Não
 */
function messageConfirm(message, functionCallBack){
    $("#dialog-confirm p label").html(message.replace(/\n/g, "<br/>"));
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: 'Confirmação',
        width: 350,
        buttons: {
            'Não': function() {
                if ($.isFunction(functionCallBack)){
                    functionCallBack(false);
                }
                $(this).dialog('close');
            },
            'Sim': function() {
                if ($.isFunction(functionCallBack)){
                    functionCallBack(true);
                }
                $(this).dialog('close');
            }
        },
        open: function(){
            $('button').blur();
            $('.ui-dialog button:last').focus();
        }
    });
}

/**
 * @function
 * @description Método utilizado para executar a método callback das janelas de mensagens
 * @name callBackDialog
 * @private
 * @author rsantos
 * @param string field
 * @param object functionCallBack
 * @return boolean
 * @see messageBox
 * @see messageErrorBox
 * @see messageConfirm
 */
function callBackDialog(field, functionCallBack){
    if ($.isFunction(field)){
        field();
    }else if(field != undefined){
        try {
            var element = document.getElementById(field);
            var tabParent = getParentField(element);
            $('#'+tabParent.split('-', 2)[0]).tabs('select', tabParent.split('-', 2)[1]);
            element.focus();
        }catch(err){}
    }
    if ($.isFunction(functionCallBack)){
        functionCallBack();
    }
    return true;
}

/**
 * @function
 * @description Método reponsável por buscar o ID do elemento HTML parent
 * @name getParentField
 * @author rsantos
 * @param string element Elemento HTML que desejo buscar o parentNode
 * @return integer ID do elemento HTML parent
 * @private
 * @see callBackDialog
 */
function getParentField(element){
    while (element.id.split('-', 2)[1] == undefined){
        element = element.parentNode;
    }
    return element.id;
}

/**
 * @function
 * @description Usado para setar a mensagem de warnings
 * @name setMessageWarning
 * @return this The instance itself
 */
String.prototype.setMessageWarning = function(message){
    $("#"+this+' p').html(message);
    $("#"+this).fadeIn(3000);
    return this;
}

/**
 * @function
 * @description Usado para ocultar mensagens de warning
 * @name hideMessageWarning
 * @return this The instance itself
 */
String.prototype.hideMessageWarning = function(){
    $("#"+this).fadeOut(3000);
    return this;
}

/**
 * @function
 * @description Usado para exibir mensagens de warning
 * @name showMessageWarning
 * @return this The instance itself
 */
String.prototype.showMessageWarning = function(){
    $("#"+this).fadeIn(3000);
    return this;
}

/**
 * @function
 * @description Abre uma página da wiki
 * @name wiki
 * @author carloseduardo
 * @param string pagina Endereço da página da wiki
 * @return window Uma referência à janela aberta
 */
function wiki(pagina){
    var name = pagina.split("#"); //remove hashtags
    if (name.length > 1) {
        pagina = name[0]+"#"+wikiNotation(name[1]);
    }
    name = name[0]; //window name is the page name, whithout hashes, preventing multiple tabs for the same page
    return open(WIKI + pagina, name);
}

/**
 * @function
 * @description converte as letras acentuadas para uma saida que a wiki entende
 * @name wikiNotation
 * @author carloseduardo
 * @private
 * @see wiki
 */
function wikiNotation(text){
    var input = "Â:À:Á:Ä:Ã:â:ã:à:á:ä:Ê:È:É:Ë:ê:è:é:ë:Î:Í:Ì:Ï:î:í:ì:ï:Ô:Õ:Ò:Ó:Ö:ô:õ:ò:ó:ö:Û:Ù:Ú:Ü:û:ú:ù:ü:,:\\.: :-:\\+:\\*:/:\\(:\\):\\[:\\]:{:}:\\?:;:@:#:\\$:%:&:\\|:\\\\:_:ç:Ç".split(":");
    var output = ".C3.82:.C3.80:.C3.81:.C3.84:.C3.83:.C3.A2:.C3.A3:.C3.A0:.C3.A1:.C3.A4:.C3.8A:.C3.88:.C3.89:.C3.8B:.C3.AA:.C3.A8:.C3.A9:.C3.AB:.C3.8E:.C3.8D:.C3.8C:.C3.8F:.C3.AE:.C3.AD:.C3.AC:.C3.AF:.C3.94:.C3.95:.C3.92:.C3.93:.C3.96:.C3.B4:.C3.B5:.C3.B2:.C3.B3:.C3.B6:.C3.9B:.C3.99:.C3.9A:.C3.9C:.C3.BB:.C3.BA:.C3.B9:.C3.BC:.2C:.:_:-:.2B:.2A:.2F:.28:.29:.5B:.5D:.7B:.7D:.3F:.3B:.40:.23:.24:.25:.26:.7C:.5C:_:.C3.A7:.C3.87".split(":");
    for (var i = 0; i < output.length; i++) {
        text = text.replace(new RegExp(input[i], "g"), output[i]);
    }
    return text;
}
