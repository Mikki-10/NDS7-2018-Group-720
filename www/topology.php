<?php

?>


<!DOCTYPE html>
<meta charset="utf-8">
<script src="http://d3js.org/d3.v2.min.js?2.9.3"></script>
<style>

.link {
  stroke: #aaa;
}

.node text {
stroke:#333;
cursos:pointer;
}

.node circle{
stroke:#fff;
stroke-width:3px;
fill:#555;
}

</style>
<body>

<script>

var width = 1400,
    height = 850

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

var force = d3.layout.force()
    .gravity(0.05)
    .distance(200)
    .charge(-100)
    .size([width, height]);


var inter = setInterval(function() {
				d3.selectAll("svg > *").remove();
                
                updateData();
        }, 15000); 

function updateData() {


d3.json("graphFile.json", function(json) {
  force
      .nodes(json.nodes)
      .links(json.links)
      .start();

  var link = svg.selectAll(".link")
      .data(json.links)
    .enter().append("line")
      .attr("class", "link")
    .style("stroke-width", function(d) { return Math.sqrt(d.weight); });

  var node = svg.selectAll(".node")
      .data(json.nodes)
    .enter().append("g")
      .attr("class", "node")
      .call(force.drag);

  node.append("circle")
      .attr("r","10");

  node.append("text")
      .attr("dx", 12)
      .attr("dy", ".35em")
      .text(function(d) { return d.name });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
  });
});
}

</script>