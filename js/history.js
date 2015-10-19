$(function() {
    var id = getUrlParameter("id");
    var name = getUrlParameter("name");
    var historyTable = $('#history').DataTable( {
        dom: 'Bfrtip',
        buttons: [{
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5]
                },
            },'pdf'
        ],
        "ajax": {
            "url": "getItemHistory.php?id=" + id,
            "dataSrc": ""
        },
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "columns": [
            { "data": null},
            { "data": "max_offer_unit_price" },
            { "data": "all_offer_quantity" },
            { "data": "min_sale_unit_price" },
            { "data": "all_sale_quantity" },
            { "data": "created_date" }
        ],
        "order": [[ 5, 'desc' ]]
    });
    
    historyTable.on( 'order.dt search.dt', function () {
        historyTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
    }).draw();
    
    historyTable.on('init.dt', function() {
        historyTable.column(1).nodes().each( function (cell, i) {
           cell.innerHTML = convertToCoin(cell.innerHTML);
        });
        
        historyTable.column(3).nodes().each( function (cell, i) {
           cell.innerHTML = convertToCoin(cell.innerHTML);
        });
    }).draw();
    
    $('#txtHead').text(name + " [ID:" + id + "] History");
    
    
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function convertToCoin(money) {
    var g = 0, s = 0, c = 0;
    c = money.slice(-2);
    s = money.slice(-4, -2);
    g = money.slice(0, -4);
    return g + " Gold " + s + " Silver " + c + " Copper";
}