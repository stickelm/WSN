$(document).ready(function () {
    $.ajax({
        url: "http://xianlin.ami-lab.org/proxy.php?url=meshliuma.ami-lab.org/sensors/A03/hour/tca/",
        dataType: "xml",
        success: function (xml) {
            initChart(xml);
            }
    });
});

function initChart(xml) {
            var label_array = [];
            var value_array = [];

            $(xml).find('sensor').each(function () {

                var label = $(this).attr("hour");
                var value = $(this).attr("value");

            //    data.push([label, parseFloat(value)]);
                label_array.push(label);
                value_array.push(parseFloat(value).toFixed(2));
            });
            console.log(value_array[0]);

var data = {
    labels : label_array,
    datasets : [
        {
            label: "My First dataset",
            fillColor: "rgba(152, 196, 44, 0.4)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: value_array
        }
    ]
};

var options = {
    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
};



var ctx = $("#chart").get(0).getContext("2d"); 
new Chart(ctx).Line(data, options);

}
