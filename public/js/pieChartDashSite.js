/**
* Theme: Frogetor - Responsive Bootstrap 4 Admin Dashboard
* Author: Mannatthemes
* Chart Js
*/


!function ($) {
    "use strict";
    let Obj = { 'chart': 1 };
    var ChartJs = function () { };

    ChartJs.prototype.respChart = function (selector, obj, type, data, options) {
        // get selector by context
        var ctx = selector.get(0).getContext("2d");
        // pointing parent container to make chart js inherit its width
        var container = $(selector).parent();

        // enable resizing matter
        $(window).resize(generateChart);

        // this function produce the responsive Chart JS
        function generateChart() {
            // make chart width fit with its container
            var ww = selector.attr('width', $(container).width());
            switch (type) {
                case 'Line':
                    obj.chart = new Chart(ctx, { type: 'line', data: data, options: options });
                    //CourbedeCharge_Linechart = _chart1;
                    //console.log(_chart1);
                    break;
                case 'Doughnut':
                    obj.chart = new Chart(ctx, { type: 'doughnut', data: data, options: options });
                    break;
                case 'Pie':
                    new Chart(ctx, { type: 'pie', data: data, options: options });
                    break;
                case 'Bar':
                    obj.chart = new Chart(ctx, { type: 'bar', data: data, options: options });
                    break;
                case 'Radar':
                    obj.chart = new Chart(ctx, { type: 'radar', data: data, options: options });
                    break;
                case 'PolarArea':
                    obj.chart = new Chart(ctx, { data: data, type: 'polarArea', options: options });
                    break;
            }
            // Initiate new chart or Redraw

        };
        // run function - render chart at first load
        generateChart();
    },
        //init
        ChartJs.prototype.init = function () {
            //Pie chart
            var pieChart = {
                labels: [
                    "Desktops",
                    "Tablets",
                    "Mobiles",
                    "Mobiles",
                ],
                datasets: [
                    {
                        data: [80, 50, 100, 121],
                        backgroundColor: [
                            "#194a8b",
                            "#00264a",
                            "#e3eaef",
                            "#44a2d2",
                        ],
                        borderColor: "#333858",
                        hoverBackgroundColor: [
                            "#194a8b",
                            "#00264a",
                            "#e3eaef",
                            "#44a2d2",
                        ],
                        hoverBorderColor: "#fff"
                    }]
            };
            this.respChart($("#pie"), 'Pie', pieChart);
        }
}(window.jQuery),

    //initializing
    function ($) {
        "use strict";
        $.ChartJs.init()
    }(window.jQuery);