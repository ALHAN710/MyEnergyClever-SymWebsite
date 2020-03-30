/* var EA = document.querySelector('.EA');
console.log(EA.val());*/

// Select elements by their data attribute
const $gridEntryElements = $('[data-entry-idgrid]');

// Map over each element and extract the data value
const $gridEntryIds = $.map($gridEntryElements, item => $(item).data('entryIdgrid'));
//$('.gridTab td').eq(7).html('1.5');
// You'll now have array containing string values
console.log($gridEntryIds[0]); // eg: ["1", "2", "3"]

// Select elements by their data attribute
const $fuelEntryElements = $('[data-entry-idfuel]');

// Map over each element and extract the data value
const $fuelEntryIds = $.map($fuelEntryElements, item => $(item).data('entryIdfuel'));
//$('.fuelTab td').eq(7).html('0.7');
// You'll now have array containing string values
console.log(Object.values($fuelEntryIds)); // eg: ["1", "2", "3"]

$("#datetimepicker10").on("dp.change", function (e) {
    //$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    //console.log(e.date._d.toLocaleString().replace('à', ''));
    //console.log(e.date._d);
    //$date = new Date(e.date._d);
    //console.log(e.date.format("YYYY-MM-DD H:i:s")._d);
    //console.log(Date.parse($date).toString('yyyy-MM-dd H:i:s'));
    $dat = new moment(e.date._d);
    $date = $dat.format("YYYY-MM").toString();
    //console.log($date);
    $date = '%' + $date + '%';
    //console.log($date);
    var $data = JSON.stringify({
        "gridIds": $gridEntryIds,
        "fuelIds": $fuelEntryIds,
        "selectedDate": $date
    });
    console.log($data);
    $.ajax({
        type: "POST",//method type
        contentType: "application/json; charset=utf-8",
        url: $urlupdateDashSite,///Target function that will be return result
        data: $data,//parameter pass data is parameter name param is value 
        dataType: "json",
        success: function (data) {
            //alert("Success");
            console.log(data);
            //console.log(data.EA[$gridEntryIds[0]]);
            //console.log(typeof data.Liters[$fuelEntryIds[0]]);
            $gridEntryIds.forEach(function (item, index) {
                $('.gridTab td').eq(index * 6 + 1).html(data.EA[item] === null ? 0 : data.EA[item]); //EA
                $('.gridTab td').eq(index * 6 + 2).html(data.ER[item] === null ? 0 : data.ER[item]); //ER
                $('.gridTab td').eq(index * 6 + 3).html(data.Cos[item] === null ? 0 : data.Cos[item]); //Cosphi
                $('.gridTab td').eq(index * 6 + 4).html(data.Smax[item] === null ? 0 : data.Smax[item]); //Smax
                $('.gridTab td').eq(index * 6 + 5).html(data.Smoy[item] === null ? 0 : data.Smoy[item]); //Smoy
            });

            $fuelEntryIds.forEach(function (item, index) {
                $('.fuelTab td').eq(index * 7 + 1).html(data.EA[item] === null ? 0 : data.EA[item]); //EA
                $('.fuelTab td').eq(index * 7 + 2).html(data.ER[item] === null ? 0 : data.ER[item]); //ER
                $('.fuelTab td').eq(index * 7 + 3).html(data.Cos[item] === null ? 0 : data.Cos[item]); //Cosphi
                $('.fuelTab td').eq(index * 7 + 4).html(data.Liters[item] === null ? 0 : data.Liters[item]); //Liter
                $('.fuelTab td').eq(index * 7 + 5).html(data.WorkingTime[item] === null ? 0 : data.WorkingTime[item]); //Temps de fonctionnement
            });

        },
        error: function (result) {
            console.log("Error");
            console.log(result);
        }
    });

    /*var settings = {
        "url": $urlupdateDashSite,
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": $data,
        "dataType": "json",

    };

    $.ajax(settings).done(function (response) {
        console.log(response);
    }).error(function (error) {
        console.log(error);
    });*/

});

