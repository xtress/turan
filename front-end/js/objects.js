'use strict';

/* Objects */

var settingsJs = {
    getLocale: function(){

        var locale = 'ru';
        var locationPartials = location.host.split('.');
        if (locationPartials[0] == 'www'){
            if (locationPartials[1] == 'en'){
                locale = 'en';
            }
        }else{
            if (locationPartials[0] == 'en'){
                locale = 'en';
            }
        }
        return locale;
    },
    getUniqueValue: function(){
        var value = Math.round(new Date().getTime() / 1000);
        return value;
    },
    getBaseUrl: function(){
        return window.location.origin+window.location.pathname;
    }
};


var mainJs = {
    initDateTimePickers: function (){
        $('#date').datetimepicker({
            pickTime: false,
            language: settingsJs.getLocale(),
            setDate: 'today'
        });
    },
    getFormattedDate: function(input){
        var data = input.toJSON().slice(0,10);
        var pattern=/(.*?)\-(.*?)\-(.*?)$/;
        var result = data.replace(pattern,function(match,p1,p2,p3){
            return (p3<10?"0"+p3:p3)+"."+p2+"."+p1;
        })

        return result;
    },
    getFormattedDateRequest: function(data){
        var pattern=/(.*?)\.(.*?)\.(.*?)$/;
        var result = data.replace(pattern,function(match,p1,p2,p3){
            return p1+"-"+p2+"-"+(p3<10?"0"+p3:p3);
        })
        console.log(result);
        return result;
    }
}
