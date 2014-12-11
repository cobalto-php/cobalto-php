function loadMasks(){
    $('.datepicker, .cep, .cpf, .cnpj, .telefone, .nr_processo_rh, .ano').unmask();
    $('.datepicker').mask('99/99/9999', {placeholder:' '});
    $('.datepicker').bind('blur', function(){validateDate(this.id);});
    $('.cep').mask('99999-999',{completed:function(){void(0);}});
    $('.cpf').mask('999.999.999-99');
    $('.cpf').bind('blur', function(){validateCPF(this);});
    $('.cnpj').mask('99.999.999/9999-99',{completed:function(){void(0);}});
    $('.telefone').mask('(99) 9999-9999',{completed:function(){void(0);}});
    $('.placa').mask('aaa 9999');
    $('.real').unmaskMoney();
    $('.real').maskMoney({symbol:'R$', decimal:',', thousands:'.'});
    $('.nr_processo_rh').mask("99999.999999/9999-99");
    $('.ano').mask('9999');
    $('.numero').on('keypress', function(event){
        e = event||window.event;
        var k = e.which||e.keyCode||e.charCode;
        return k==8 || k==9 || k==13 || k==16 || k==17 || k==18 || k==27 || k==46 // delete, tab, enter, shift, control, alt, esc, delete keys
            || (k >= 37 && k <= 40) // direcional arrows
            || (k >= 112 && k <= 123) // F1 to F12
            || (k >= 48 && k <= 57); // 0 to 9
    });
}
