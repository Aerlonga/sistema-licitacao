// Função de ordenação personalizada para datas no formato DD/MM/YYYY
jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-pt-br-pre": function (a) {
        if (a == null || a == "") {
            return 0;
        }
        var brDatea = a.split('/');
        return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
    },

    "date-pt-br-asc": function (a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-pt-br-desc": function (a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    },

    "datetime-pt-br-pre": function (a) {
        if (a == null || a == "") {
            return 0;
        }
        var dateTimeParts = a.split(' ');
        var dateParts = dateTimeParts[0].split('/');
        var timeParts = dateTimeParts[1].split(':');
        var dateTimeString = dateParts[2] + dateParts[1] + dateParts[0] +
            timeParts[0] + timeParts[1] + timeParts[2];
        return dateTimeString * 1;
    },

    "datetime-pt-br-asc": function (a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "datetime-pt-br-desc": function (a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});
