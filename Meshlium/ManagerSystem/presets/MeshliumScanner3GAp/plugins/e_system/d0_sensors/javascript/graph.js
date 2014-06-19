//<![CDATA[
var graph;
var data = [];
var labels;

function start_graph(){
    submit_data="section="+section+"&plugin="+plugin+"&type=data_request";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            // A JSON array is expected
            var ret = eval('(' + datos + ')');
            $.each(ret.item, function(i,item){
                if (item['type']=="graph_data")
                {
                    for(i=1;i<item['value'].length;i++){
                        item['value'][i]=parseInt(item['value'][i]);
                    }
                    var tmp = new Date();
                    item['value'][0]=new Date(tmp.getTime());                   
                    //data=item['value'];
                    data.push(item['value']);           
                }
                else if (item['type']=="labels")
                {                    
                    labels=item['value'];
                }    
            });            
            //alert(labels);
            //alert(data);
            graph = new Dygraph(document.getElementById("evolution_graph"), data,
            {
                drawPoints: true,
                showRoller: true,
                'labels': labels,
                labelsDiv: document.getElementById("labels_div"),
                strokeWidth:2,
                labelsSeparateLines:true,
                highlightCircleSize:5,
                axisLabelFontSize:10,
                rightGap:10,
                showRoller:false
            });
            load_data(section,plugin);
        }
    });
    
}

function get_data(section,plugin){
    submit_data="section="+section+"&plugin="+plugin+"&type=data_request";

    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            // A JSON array is expected
            var ret = eval('(' + datos + ')');
            $.each(ret.item, function(i,item){
                if (item['type']=="graph_data")
                {
                    for(i=1;i<item['value'].length;i++){
                        item['value'][i]=parseInt(item['value'][i]);
                    }
                    var tmp = new Date();
                    item['value'][0]=new Date(tmp.getTime());
                    data.push(item['value']);                    
                }
                else if (item['type']=="labels")
                {
                    labels=item['value'];
                }
            });
        }
    });
}

function load_data(section,plugin)
{
    get_data(section,plugin);
    graph.updateOptions( {
        'labels': labels,
        'file': data
    } );
    if (recargame)
    {
        setTimeout("load_data('"+section+"','"+plugin+"')", reloadtime );
    }
};
start_graph();
//]]>