$(function() {
    var mainTable = $('#main').DataTable( {
        "ajax": {
            "url": "getItemListFromDB.php",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "type" },
            { "data": "level" },
            { "data": "type2" }
        ]
    });
    
    var watchlistTable = $('#watchlist').DataTable( {
        "ajax": {
            "url": "manageWatchList.php",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            { "data": "name" }
        ]
    });
    
    $('#main tbody').on( 'click', 'tr', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
        else {
            mainTable.$('tr.active').removeClass('active');
            $(this).addClass('active');
        }
        if (mainTable.rows('.active').data().length > 0) {
            $('#itemId').val(mainTable.rows('.active').data()[0].id);
        }
    });
    
    $('#watchlist tbody').on( 'click', 'tr', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
        else {
            watchlistTable.$('tr.active').removeClass('active');
            $(this).addClass('active');
        }
        if (watchlistTable.rows('.active').data().length > 0) {
            $('#itemId').val(watchlistTable.rows('.active').data()[0].id);
        }
    });
 
    $('#insertWatchlist').click( function () {
        for (var i = 0; i < mainTable.rows('.active').data().length; i++) {
            var id = mainTable.rows('.active').data()[i].id;
            var name = mainTable.rows('.active').data()[i].name;
            $.ajax({
                type: 'POST',
                url: "manageWatchList.php",
                data: { cmd: "insert", id: id },
                success: function(result) { 
                    if (result !== "n") {
                        var isFound = false;
                        for (var i = 0; i < watchlistTable.rows().data().length; i++) {
                            if(result === watchlistTable.rows().data()[i].id) {
                                isFound = true;
                                break;
                            }
                        }
                        if (!isFound) {
                            watchlistTable.row.add({id: id, name: name}).draw(false);
                        }
                    } else {
                        alert("Maximum(100) exceeded");
                    }
                },
                async:false
            });
        }
    });
    
    $('#deleteWatchlist').click( function () {
        if(confirm("Are you sure to delete ?")) {
            for (var i = 0; i < watchlistTable.rows('.active').data().length; i++) {
                var id = watchlistTable.rows('.active').data()[i].id;
                $.ajax({
                    type: 'POST',
                    url: "manageWatchList.php",
                    data: { cmd: "delete", id: id },
                    success: function(result) { watchlistTable.row('.active').remove().draw(false); },
                    async:false
                });
            }
        }
    });
    
    $('#searchItem').click( function () {
        if ($('#itemId').val() !== null) {
            $.ajax({
                type: 'GET',
                url: "https://api.guildwars2.com/v2/items/" + $('#itemId').val(),
                success: function(result) { $('#itemResult').text(JSON.stringify(result)); },
            });
        }
    });
    
    $('#history').click( function () {
        if (watchlistTable.rows('.active').data().length > 0) {
            window.open("history.html?id=" + watchlistTable.rows('.active').data()[0].id + 
            "&name=" + watchlistTable.rows('.active').data()[0].name, '_blank');
        }
    });

});